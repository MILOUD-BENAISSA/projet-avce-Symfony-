<?php

namespace App\Controller;

use App\Form\RegistrationType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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
}
