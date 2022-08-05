<?php

namespace App\Controller\admin;

use App\Form\UserSearchType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



#[IsGranted('ROLE_ADMIN')]
#[ROUTE('/admin/users')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_admin_user_list')]
    public function list(Request $request, UserRepository $repository): Response
    {

        $form = $this->createForm(UserSearchType::class);
        $form->handleRequest($request);

        $users = $repository->findBySearch($form->getData());

        
        return $this->render('admin/user/list.html.twig', [
           "users" => $users,
           'form' => $form->createView(),
        ]);
    }
}
