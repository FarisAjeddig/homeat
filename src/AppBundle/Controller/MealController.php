<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Meal;
use AppBundle\Form\MealType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/meal")
 */
class MealController extends Controller
{
    // Calcule la distance en entre deux points et renvoie sa valeur en kilomètre
    public static function distance($startLat, $startLng, $endLat, $endLng) {
        $earth_radius = 6378137;   // Terre = sphère de 6378km de rayon
        $rlo1 = deg2rad($startLng);
        $rla1 = deg2rad($startLat);
        $rlo2 = deg2rad($endLng);
        $rla2 = deg2rad($endLat);
        $dlo = ($rlo2 - $rlo1) / 2;
        $dla = ($rla2 - $rla1) / 2;
        $a = (sin($dla) * sin($dla)) + cos($rla1) * cos($rla2) * (sin($dlo) * sin($dlo));
        $d = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $meter = ($earth_radius * $d);
        return $meter / 1000;
    }


    /**
     * @Route("/create", name="createMeal")
     */
    public function createMealAction(Request $request)
    {
        // TODO : Vérifier si l'utilisateur est authentifié
        $user = $this->getUser();
        $meal = new Meal();

        $form = $this->createForm(MealType::class, $meal)->add('Créer', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()){

            // TODO : Check quel est le bouton cliqué.

            $meal->setCooker($user);

            $url = 'https://maps.googleapis.com/maps/api/geocode/json?';
            $options = array("address"=>$meal->getAdress(),"key"=>"AIzaSyAuZviasKN0VON99Nz4I8b_tu6YZDcmrsw");
            $url .= http_build_query($options,'','&');

            if (json_decode(file_get_contents(htmlspecialchars_decode($url)))->results == []) {
                dd('L\'adresse est pourrie boloss'); // TODO : Handle exception
            } else {
                $coord = json_decode((file_get_contents(htmlspecialchars_decode($url))))->results[0]->geometry->location;
            }
            $meal->setLongAdress($coord->lng);
            $meal->setLatAdress($coord->lat);

            $file = $meal->getPictures();

            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('pictures_directory'),
                    $fileName
                );
            } catch (FileException $e) {
            }
            $meal->setPictures($fileName);
            $meal->setEnabled(true);//TODO : adapter

            $em = $this->getDoctrine()->getManager();
            $em->persist($meal);
            $em->flush();
        }

        return $this->render('Meal/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/research", name="researchMeal")
     */
    public function researchMealsAction(Request $request)
    {

        $meals = $this->getDoctrine()->getRepository(Meal::class)->findAll();

        $form = $this->createFormBuilder([])
            ->add('ville', TextType::class, ['label' => 'Indiquez nous juste le lieu, on s\'occupe du reste'])
            ->add('rechercher', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $filterMeals = [];

            $url = 'https://maps.googleapis.com/maps/api/geocode/json?';
            $options = array("address"=>$data['ville'],"key"=>"AIzaSyAuZviasKN0VON99Nz4I8b_tu6YZDcmrsw");
            $url .= http_build_query($options,'','&');

            if (json_decode(file_get_contents(htmlspecialchars_decode($url)))->results == []) {
                dd('L\'adresse est pourrie boloss'); // TODO : Handle exception
            } else {
                $coord = json_decode((file_get_contents(htmlspecialchars_decode($url))))->results[0]->geometry->location;
            }

            /** @var Meal $meal */
            foreach ($meals as $meal){
                if (self::distance($coord->lat, $coord->lng, $meal->getLatAdress(), $meal->getLongAdress()) < 50){
                    $filterMeals[] = $meal;
                }
            }
            return $this->render('Meal/research.html.twig', array(
                'meals' => $filterMeals,
                'form' => $form->createView()
            ));
        }

        return $this->render('Meal/research.html.twig', array(
            'meals' => $meals,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/edit/{id}", name="editMeal")
     */
    public function editMealAction(Request $request, $id)
    {
        return $this->render('Meal/edit.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/show/{id}", name="showMeal")
     */
    public function showMealAction(Request $request, $id)
    {
        /** @var Meal $meal */
        $meal = $this->getDoctrine()->getRepository(Meal::class)->find($id);
        $em = $this->getDoctrine()->getManager();

        // TODO : check si c'est pas juste une actualisation / réfléchir à comment évaluer ça correctement.
        $meal->setTimesViewed($meal->getTimesViewed()+1);
        $em->persist($meal);
        $em->flush();

        return $this->render('Meal/show.html.twig', array(
            'meal' => $meal
        ));
    }


    /**
     * @Route("/orderMeal/{mealID}", name="orderMealModal")
     */
    public function orderMealModalAction(Request $request, $mealID){
        /** @var Meal $meal */
        $meal = $this->getDoctrine()->getRepository(Meal::class)->find($mealID);

        $options = [];
        if ($meal->getTakeAway()){
            $options['À emporter'] = 'À emporter';
        }
        if ($meal->getOnTheSpot()){
            $options['Sur place'] = 'Sur place';
        }
        if ($meal->getDelivery()){
            $options['En livraison'] = 'En livraison';
        }

        $form = $this->createFormBuilder([])
            ->add('numberPlaces', IntegerType::class)
            ->add('a',ChoiceType::class,array(
            'choices'  => $options,
            'expanded' => true,
            'multiple' => false
        ))
            ->add('reserver', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // TODO : check si le nombre de place est dispo
            return $this->redirectToRoute("orderMeal", [
                'mealID' => $mealID,
                'numberPlaces' => $form->getData()['numberPlaces']
            ]);
        }
        return $this->render('modal/OrderMeal.html.twig', array(
            'form'=>$form->createView(),
            'mealID'=>$mealID,
            'meal' => $meal
            ));
    }

    /**
     * @Route("/order/{mealID}/{numberPlaces}", name="orderMeal")
     */
    public function orderMealAction(Request $request, $mealID, $numberPlaces)
    {
        $user = $this->getUser();
        $meal = $this->getDoctrine()->getRepository(Meal::class)->find($mealID);

        $amount = $meal->getPricePerPerson()*$numberPlaces;

        if ($request->isMethod('post') && ($request->request->get('stripeToken') != null)){

            \Stripe\Stripe::setApiKey('sk_test_5TA6ChJTqt9yw9THW75V1oFl');

            \Stripe\Charge::create([
                "amount" => $amount * 100,
                "currency" => "eur",
                "source" => $request->request->get('stripeToken'),
                "receipt_email" => $user->getEmail(),
                "description" => ""
            ]/*, [
                "idempotency_key" => "Qx9ynl4xdel41yIc",
            ]*/);
            $request->getSession()->getFlashBag()->add('info', "Votre paiement a bien été pris en compte. Vous avez reçu une facture dans le mail que nous venons de vous envoyer");

//            $em->flush();

            return $this->redirectToRoute('fos_user_profile_show');
        }


        return $this->render('Meal/order.html.twig', compact('amount'));
    }

    /**
     * @Route("/delete/{id}", name="deleteMeal")
     */
    public function deleteMealAction(Request $request, $id)
    {
        return $this->render('Meal/delete.html.twig', array(
            // ...
        ));
    }

}
