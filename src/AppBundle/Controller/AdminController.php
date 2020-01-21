<?php
/**
 * Created by PhpStorm.
 * User: faris
 * Date: 10/02/19
 * Time: 23:32
 */


namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use AppBundle\Entity\MailForNews;
use AppBundle\Form\ContactType;
use phpDocumentor\Reflection\Types\Array_;
use phpDocumentor\Reflection\Types\This;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Form\PartyType;

use AppBundle\Entity\User;
use AppBundle\Entity\Party;
use Symfony\Component\Validator\Constraints\NotBlank;

class AdminController extends Controller
{

    /**
     * @Route("/admin/", name="admin_homepage")
     */
    public function adminHomeAction(Request $request){
        return $this->redirectToRoute('admin_users');
    }

    /**
     * @Route("/admin/users", name="admin_users")
     */
    public function adminUsersAction(Request $request){
        $repository = $this->getDoctrine()->getManager()
            ->getRepository(User::class);


        return $this->render('admin/users.html.twig', [
            'users' => $repository->findAll()
        ]);
    }


    /**
     * @Route("admin/users/confirmDelete/{id}", name="admin_users_confirm_delete")
     */
    public function adminUsersConfirmDeleteAction(Request $request, $id){
        $repository = $this->getDoctrine()->getManager()
            ->getRepository(User::class);

        $user = $repository->find($id);

        return $this->render('admin/deleteUser.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("admin/users/delete/{id}", name="admin_users_delete")
     */
    public function adminUsersDeleteAction(Request $request, $id){
        $repository = $this->getDoctrine()->getManager()
            ->getRepository(User::class);

        $user = $repository->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();


        $request->getSession()->getFlashBag()->add('info', "L'utilisateur a bien été supprimé.");
        return $this->redirectToRoute('admin_party');
    }

    /**
     * @Route("/admin/contact", name="admin_contact")
     */
    public function adminContactAction(Request $request){
        $repository = $this->getDoctrine()->getManager()->getRepository(Contact::class);
        $contacts = array_reverse($repository -> findAll());

        return $this->render('admin/contact.html.twig', [
            'contacts' => $contacts
        ]);
    }

    /**
     * @Route("/admin/contact/confirmDelete/{id}", name="admin_contact_confirm_delete")
     */
    public function adminContactConfirmDeleteAction(Request $request, $id){
        $repository = $this->getDoctrine()->getManager()->getRepository(Contact::class);
        $contact = $repository->find($id);

        return $this->render('admin/deleteContact.html.twig', [
            'contact' => $contact
        ]);
    }

    /**
     * @Route("/admin/contact/delete/{id}", name="admin_contact_delete")
     */
    public function adminContactDeleteAction(Request $request, $id){
        $repository = $this->getDoctrine()->getManager()->getRepository(Contact::class);
        $contact = $repository->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($contact);
        $em->flush();

        $request->getSession()->getFlashBag()->add('info', "Le message a bien été supprimé.");

        return $this->redirectToRoute('admin_contact');
    }
}
