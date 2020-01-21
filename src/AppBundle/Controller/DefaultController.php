<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use AppBundle\Entity\MailForNews;
use AppBundle\Form\ContactType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use phpDocumentor\Reflection\Types\Array_;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Form\PartyType;

use AppBundle\Entity\User;
use AppBundle\Entity\Party;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;

class DefaultController extends Controller
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
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contactAction(Request $request, \Swift_Mailer $mailer){

        $em = $this->getDoctrine()->getManager();
        $newContact = new Contact();
        /** @var User $user */
        $user = $this->getUser();

        if ($user != null){
            $newContact->setMail($user->getEmail());
        }
        $form = $this -> get('form.factory')->create(ContactType::class, $newContact);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
            $em->persist($newContact);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', "Le message a bien été envoyé.");
            return $this -> render('default/contact.html.twig');
        }

        return $this -> render('default/contact.html.twig', [
            'form' => $form -> createView()
        ]);
    }

    /**
     * @Route("/profile/delete", name="delete_my_account")
     */
    public function deleteMyAccountAction(Request $request){
        $user = $this->getUser();

        $user->setEnabled(false);
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirectToRoute('fos_user_security_logout');
    }

    /**
     * @Route(name="FormRegisterConfirmed")
     */
    public function FormRegisterConfirmedAction(Request $request){
        $user = $this->getUser();

        $form = $this->createFormBuilder($user)
            ->add('firstName', TextType::class, ['label' => 'Prénom'])
            ->add('lastName', TextType::class, ['label' => 'Nom'])
            ->add('phoneNumber', TextType::class, ['label' => 'Numéro de téléphone'])
            ->add('adress', TextType::class, ['label' => 'Adresse'])
            ->add('Envoyer', SubmitType::class)
            ->getForm();

        if ($request->isMethod('POST')){
            dump($form->getData());die;
        }


        return $this -> render('default/register_confirmed_form.html.twig', [
            'form' => $form -> createView()
        ]);
    }

    /**
     * @Route("/register/confirmed", name="registerConfirmed")
     */
    public function registerConfirmedAction(Request $request){
        /** @var User $user */
        $user = $this->getUser();

        if ($user->getFirstName() !== null){
            return $this->redirectToRoute('registerConfirmedStepTwo');
        }

        $form = $this->createFormBuilder($user)
            ->add('firstName', TextType::class, ['label' => 'Prénom'])
            ->add('lastName', TextType::class, ['label' => 'Nom'])
            ->add('phoneNumber', TelType::class, ['label' => 'Numéro de téléphone' ])
            ->add('adress', TextType::class, ['label' => 'Quelle est votre ville ?'])
            ->add('Enregistrer', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($request->isMethod('POST')){

            $url = 'https://maps.googleapis.com/maps/api/geocode/json?';
            $options = array("address"=>$user->getAdress(),"key"=>"AIzaSyAuZviasKN0VON99Nz4I8b_tu6YZDcmrsw");
            $url .= http_build_query($options,'','&');

            if (json_decode(file_get_contents(htmlspecialchars_decode($url)))->results == []) {
                dd('L\'adresse est pourrie boloss'); // TODO : Handle exception
            } else {
                $coord = json_decode((file_get_contents(htmlspecialchars_decode($url))))->results[0]->geometry->location;
            }
            $user->setLongAdress($coord->lng);
            $user->setLatAdress($coord->lat);
            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('registerConfirmedStepTwo');
        }


        return $this -> render('default/register_confirmed_form.html.twig', [
            'form' => $form -> createView()
        ]);
    }
    /**
     * @Route("/register/confirmed/2", name="registerConfirmedStepTwo")
     */
    public function registerConfirmedStepTwoAction(Request $request){
        $user = $this->getUser();

        if ($user->getDescription() !== null){
            return $this->redirectToRoute('fos_user_profile_show');
        }

        $form = $this->createFormBuilder($user)
            ->add('description', TextareaType::class, ['label' => 'Parlez-nous un peu de vous'])
            ->add('allergy', TextareaType::class, ['label' => 'Avez vous des allérgies ?'])
            ->add('specialScheme', TextareaType::class, ['label' => 'Un régime particulier ? (halal, cacher, végétarien, etc.)' ])
            ->add('birthDate', DateType::class, ['label' => 'Votre date de naissance', 'years' => range(date('Y')-17, date('Y')-70)])
            ->add('Enregistrer', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($request->isMethod('POST')){

            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('fos_user_profile_show');
        }

        return $this -> render('default/register_confirmed_form_2.html.twig', [
            'form' => $form -> createView()
        ]);
    }

    /**
     * @Route("/profile/edit", name="profileEdit")
     */
    public function profileEditAction(Request $request){
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createFormBuilder($user)
            ->add('firstName', TextType::class, ['label' => 'Prénom'])
            ->add('lastName', TextType::class, ['label' => 'Nom'])
            ->add('phoneNumber', TelType::class, ['label' => 'Numéro de téléphone' ])
            ->add('adress', TextType::class, ['label' => 'Quelle est votre ville ?'])
            ->add('description', TextareaType::class, ['label' => 'Parlez-nous un peu de vous'])
            ->add('allergy', TextareaType::class, ['label' => 'Avez vous des allérgies ?'])
            ->add('specialScheme', TextareaType::class, ['label' => 'Un régime particulier ? (halal, cacher, végétarien, etc.)' ])
            ->add('birthDate', DateType::class, ['label' => 'Votre date de naissance', 'years' => range(date('Y')-17, date('Y')-70)])
//            ->add('Enregistrer', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($request->isMethod('POST')){

            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('fos_user_profile_show');
        }

        return $this -> render('@FOSUser/Profile/edit.html.twig', [
            'form' => $form -> createView()
        ]);
    }

    /**
     * @Route("/addPicture/{userID}", name="addPicture")
     */
    public function AddPictureAction(Request $request, $userID){

        $user = $this->getDoctrine()->getRepository(User::class)->find($userID);

        $form = $this ->createFormBuilder($user)
            ->add('picture', FileType::class, ['data_class' => null])
            ->add('Modifier', SubmitType::class, array('attr' => array('class' => 'btn btn-default')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $user->getPicture();

            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('pictures_directory'),
                    $fileName
                );
            } catch (FileException $e) {
            }
            $user->setPicture($fileName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $referer = $request->headers->get('referer');
            return $this->redirect($referer);
        }
        return $this->render('modal/UpdatePicture.html.twig', array('form'=>$form->createView(), 'userID'=>$userID));
    }

}


