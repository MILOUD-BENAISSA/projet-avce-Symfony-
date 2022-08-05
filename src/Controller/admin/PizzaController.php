<?php

namespace App\Controller\admin;

use App\Entity\Pizza;
use App\Form\PizzaType;
use App\Repository\PizzaRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



#[IsGranted('ROLE_ADMIN')]
#[ROUTE('/admin/pizza')]
class PizzaController extends AbstractController
{

    #[ROUTE('/', name: 'app_admin_pizza_list')]
    public function list( PizzaRepository $repository): Response
    {
        $pizzas= $repository->findAll();

        return $this->render('admin/pizza/list.html.twig', [
            'pizzas' => $pizzas,
        ]);



    }

    #[ROUTE('/nouvelle', name: 'app_admin_pizza_create')]
    public function create(Request $request, PizzaRepository $repository): Response
    {
        $form = $this->createForm(PizzaType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $pizza = $form->getData();
            $repository->add($pizza, true);

            return $this->redirectToRoute('app_admin_pizza_list');

        }


        return $this->render('admin/pizza/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }




    #[ROUTE('/{id}/modifier', name: 'app_admin_pizza_update')]
    public function update(Request $request, PizzaRepository $repository, Pizza $pizza): Response
    {

        $form = $this->createForm(PizzaType::class, $pizza);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $repository->add($form->getData(), true);

            return $this->redirectToRoute('app_admin_pizza_list');

        }

        return $this->render('admin/pizza/update.html.twig', [
            'form' => $form->createView(),
            'pizza' => $pizza,
        ]);


    }


    #[ROUTE('/{id}/supprimer', name: 'app_admin_pizza_delete')]
    public function delete( PizzaRepository $repository, Pizza $pizza): Response
    {
        $repository->remove($pizza, true);
        return $this->redirectToRoute('app_admin_pizza_list');



    }


}
