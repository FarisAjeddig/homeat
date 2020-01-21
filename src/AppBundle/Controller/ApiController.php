<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/api")
 */
class ApiController extends Controller
{
    /**
     * @Route("/login", name="api_login")
     * @Method({"POST"})
     */
    public function apiLoginAction(Request $request){
        // Récupération des données envoyées par le client en POST
        $data = $request->getContent();
        /** @var User $user */
        $result = $this->get('jms_serializer')->deserialize($data, User::class, 'json');

        $repo = $this->getDoctrine()->getRepository(User::class);

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');


        // On check si l'utilisateur existe bien
        $userByEmail = $repo->findOneBy(['username' => $result->getEmail()]);
        if ($userByEmail == null){
            $user = $repo->findOneBy(['email' => $result->getEmail()]);
            if ($user == null){
                return $response->setContent(json_encode(["statut" => "NOT_FOUND", "message" => "Aucun compte n'est associé à cette adresse mail / pseudo"]));
            }
        } else {
            $user = $userByEmail;
        }

        // Cryptage du password
        $pass = $result->getPassword();
        $salt = $user->getSalt();
        $iterations = 5000; // Par défaut
        $salted = $pass.'{'.$salt.'}';
        $digest = hash('sha512', $salted, true);
        for ($i = 1; $i < $iterations; $i++) {
            $digest = hash('sha512', $digest.$salted, true);
        }
        $cryptedPass = base64_encode($digest);

        // Vérification des identifiants
        if ($cryptedPass == $user->getPassword()){
            return $response->setContent(json_encode(["statut" => "OK", "user" => json_decode($this->get('jms_serializer')->serialize($user, 'json'))]));
        } else {
            return $response->setContent(json_encode(["statut" => "WRONG_PASSWD", "message" => "Le mot de passe ne correspond pas à ce compte."]));
        }
    }

    /**
     * @Route("/contact", name="api_contact")
     * @Method({"POST"})
     */
    public function apiContactAction(Request $request){
        /** @var Contact $contact */
        $contact = new Contact();

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        /** @var User $user */
        $user = $this->getDoctrine()->getRepository(User::class)->find($request->get('id'));
        $contact->setContent($request->get('message'));
        $contact->setSubject($request->get('subject'));
        $contact->setMail($user->getEmail());

        $em = $this->getDoctrine()->getManager();
        $em->persist($contact);
        $em->flush();

        // TODO : Envoyer un e-mail à Eric lors de la reception d'un message.

        return $response->setContent(json_encode(["statut" => "OK"]));
    }

    /**
     * @Route("/profile/edit", name="api_profile_edit")
     * @Method({"POST"})
     */
    public function apiProfileEditAction(Request $request){
        // Récupération des données envoyées par le client en POST
        $data = $request->getContent();
        /** @var User $result */
        $result = $this->get('jms_serializer')->deserialize($data, User::class, 'json');


        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        $repo = $this->getDoctrine()->getRepository(User::class);
        /** @var User $user */
        $user = $repo->find($result->getId());

        // On check si l'adresse mail ou le pseudo n'est pas déjà utilisé, sauf par l'utilisateur
        $userAlreadyExist = $repo->findOneBy(['emailCanonical' => strtolower($result->getEmail())]);
        if ($userAlreadyExist != null && ($user->getEmailCanonical() !== strtolower($result->getEmail())) ){
            return $response->setContent(json_encode(["statut" => "EMAIL_ALREADY_USED", "message" => "L'adresse mail est déjà utilisée, choisissez-en une autre."]));
        } else {
            $userAlreadyExist = $repo->findOneBy(['usernameCanonical' => strtolower($result->getUsername())]);
            if ($userAlreadyExist != null && ($userAlreadyExist->getId() != $result->getId())){
                return $response->setContent(json_encode(["statut" => "USERNAME_ALREADY_USED", "message" => "Le pseudo est déjà utilisé, choisissez-en un autre."]));
            } else {

                // Cryptage du password
                $pass = $result->getPassword();
                $salt = $user->getSalt();
                $iterations = 5000; // Par défaut
                $salted = $pass.'{'.$salt.'}';
                $digest = hash('sha512', $salted, true);
                for ($i = 1; $i < $iterations; $i++) {
                    $digest = hash('sha512', $digest.$salted, true);
                }
                $cryptedPass = base64_encode($digest);

                // On vérifie que le mot de passe correspond bien au compte, sinon on met un message d'erreur
                if ($cryptedPass == $user->getPassword()){
                    $user->setEmail($result->getEmail());
                    $user->setEmailCanonical(strtolower($result->getEmail()));
                    $user->setUsername($result->getUsername());
                    $user->setUsernameCanonical(strtolower($result->getUsername()));

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();

                    return $response->setContent(json_encode(["statut" => "OK"]));
                } else {
                    return $response->setContent(json_encode(["statut" => "WRONG_PASSWD", "message" => "Le mot de passe ne correspond pas à ce compte."]));
                }
            }
        }
    }

    /**
     * @Route("/profile/editPassword", name="api_profile_edit_password")
     * @Method({"POST"})
     */
    public function apiProfileEditPasswordAction(Request $request){
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        /** @var User $user */
        $user = $this->getDoctrine()->getRepository(User::class)->find($request->get('id'));
        // Cryptage du password
        $pass = $request->get('actualPassword');
        $salt = $user->getSalt();
        $iterations = 5000; // Par défaut
        $salted = $pass.'{'.$salt.'}';
        $digest = hash('sha512', $salted, true);
        for ($i = 1; $i < $iterations; $i++) {
            $digest = hash('sha512', $digest.$salted, true);
        }
        $cryptedPass = base64_encode($digest);

        // On vérifie que le mot de passe correspond bien au compte, sinon on met un message d'erreur
        if ($cryptedPass == $user->getPassword()){
            $pass = $request->get('newPassword');
            $salt = $user->getSalt();
            $iterations = 5000; // Par défaut
            $salted = $pass.'{'.$salt.'}';
            $digest = hash('sha512', $salted, true);
            for ($i = 1; $i < $iterations; $i++) {
                $digest = hash('sha512', $digest.$salted, true);
            }
            $cryptedPass = base64_encode($digest);

            $user->setPassword($cryptedPass);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $response->setContent(json_encode(["statut" => "OK"]));
        } else {
            return $response->setContent(json_encode(["statut" => "WRONG_PASSWD", "message" => "Le mot de passe ne correspond pas au compte."]));
        }
    }
}
