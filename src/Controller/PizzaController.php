<?php

namespace App\Controller;

use App\Repository\PizzaRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PizzaController extends AbstractController
{
    #[Route('/', name: 'app_pizza_home')]
    public function home(PizzaRepository $repository): Response
    {

        $pizzas= $repository -> findAll();



        return $this->render('front/pizza/home.html.twig', [
            'pizzas' => $pizzas,
        ]);
    }
}
