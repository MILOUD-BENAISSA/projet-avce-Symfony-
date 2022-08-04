<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
      /**
     * Correspond à la page d'inscription du site internet
     */
    #[Route('/inscription', name: 'app_user_registration')]
    public function registration(Request $request, SluggerInterface $slugger, UserPasswordHasherInterface $hasher, UserRepository $repository): Response
    {
         $user= new User();
        // créer le formulaire d'inscription
        $form = $this->createForm(RegistrationType::class, $user );

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



// if ($form->isSubmitted() && $form->isValid()) {
//     /** @var UploadedFile $brochureFile */
//     $brochureFile = $form->get('brochure')->getData();

//     // this condition is needed because the 'brochure' field is not required
//     // so the PDF file must be processed only when a file is uploaded
//     if ($brochureFile) {
//         $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
//         // this is needed to safely include the file name as part of the URL
//         $safeFilename = $slugger->slug($originalFilename);
//         $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

//         // Move the file to the directory where brochures are stored
//         try {
//             $brochureFile->move(
//                 $this->getParameter('brochures_directory'),
//                 $newFilename
//             );
//         } catch (FileException $e) {
//             // ... handle exception if something happens during file upload
//         }

//         // updates the 'brochureFilename' property to store the PDF file name
//         // instead of its contents
//         $user->setBrochureFilename($newFilename);


//        // on récupére l'utilisateur du formulaire
//             $user = $form->getData();
//             // crypter le mot de passe de l'utilisateur
//             $user->setPassword($hasher->hashPassword($user, $user->getPassword()));

//             // enregistrer l'utilisateur en base de données
//             $repository->add($user, true);
//     }
//}





        // afficher la page d'inscription
        return $this->render('front/user/registration.html.twig', [
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

        return $this->render('front/security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/deconnexion', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }



    #[Route('/mon-profil', name: 'app_user_profile')]
    #[IsGranted('ROLE_USER')]
    public function profile(
        Request $request,
        UserRepository $repository,
        UserPasswordHasherInterface $hasher,
    ): Response {
        $form = $this->createForm(RegistrationType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            if ($form->get('password')->getData()) {
                $user->setPassword($hasher->hashPassword(
                    $user,
                    $form->get('password')->getData(),
                ));
            }

            $repository->add($user);

            return $this->redirectToRoute('app_user_logout');
        }

        return $this->render('front/user/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }



}
