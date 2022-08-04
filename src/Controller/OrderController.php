<?php

namespace App\Controller;

use App\DTO\paiment;
use App\Entity\User;
use App\Entity\Order;
use App\Form\PaimentType;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    #[Route('/commander', name: 'app_order_display')]
    public function display(Request $request, OrderRepository $repository): Response
    {
    /** @var User $user */
    $user = $this->getUser();

    $payment = new paiment();
    $payment->address = $user->getAddress();

    $form = $this->createForm(PaimentType::class, $payment);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Création de la commande !
        $order = new Order();
        $order->setUser($user);
        $order->setAddress($payment->address);

        foreach ($user->getBasket()->getArticles() as $article) {
            $order->addArticle($article);
        }

        $repository->add($order, true);

        return $this->redirectToRoute('app_order_validate', [
        'id' => $order->getId(),
        ]);
    }

    return $this->render('front/order/display.html.twig', [
    'form' => $form->createView(),
    ]);
}

    #[Route('/commander/{id}/validation', name: 'app_order_validate')]
    public function validate(Order $order): Response
    {
        return $this->render('front/order/validate.html.twig', [
            'order' => $order,
        ]);
    }





       
    
}
