<?php

namespace App\Controller;

use App\Form\RegistrationType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
      /**
     * Correspond à la page d'inscription du site internet
     */
    #[Route('/inscription', name: 'app_user_registration')]
    public function registration(Request $request, UserPasswordHasherInterface $hasher, UserRepository $repository): Response
    {
        // créer le formulaire d'inscription
        $form = $this->createForm(RegistrationType::class);

        // remplir les données du formulaire d'inscription
        $form->handleRequest($request);

        // tester si le formulaire est envoyé et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // on récupére l'utilisateur du formulaire
            $user = $form->getData();
            // crypter le mot de passe de l'utilisateur
            $user->setPassword($hasher->hashPassword($user, $user->getPassword()));

            // enregistrer l'utilisateur en base de données
            $repository->add($user, true);

            // rediriger vers la page de connexion
            return $this->redirectToRoute('app_user_registration');
        }

        // afficher la page d'inscription
        return $this->render('user/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/deconnexion', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
