<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Pizza;
use App\Entity\Article;
use App\Repository\BasketRepository;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



#[IsGranted('ROLE_USER')]
class BasketController extends AbstractController
{
    #[Route('/mon-panier', name: 'app_basket_display')]
    public function display(): Response
    {
        return $this->render('front/basket/display.html.twig');
    }

    #[Route('/mon-panier/{id}/ajouter', name: 'app_basket_addArticle')]
    public function addArticle(Pizza $pizza, BasketRepository $repository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $basket = $user->getBasket();

        $article = new Article();
        $article->setQuantity(1);
        $article->setBasket($basket);
        $article->setPizza($pizza);

        $basket->addArticle($article);

        $repository->add($basket, true);

        return $this->redirectToRoute('app_basket_display');
    }
    
    #[Route('/mon-panier/{id}/plus', name: 'app_basket_increase')]
    public function increase(Article $article, ArticleRepository $repository): Response
    {
        $article->setQuantity($article->getQuantity() + 1);

        $repository->add($article, true);

        return $this->redirectToRoute('app_basket_display');
    }



    #[Route('/mon-panier/{id}/diminuer', name: 'app_basket_decrease')]
    public function decrease(Article $article, ArticleRepository $repository, BasketRepository $basketRepo): Response
    {
        $article->setQuantity($article->getQuantity() - 1);

        if ($article->getQuantity() <= 0) {
            /** @var User $user */
            $user = $this->getUser();
            $basket = $user->getBasket();

            $basket->removeArticle($article);

            $basketRepo->add($basket, true);

            return $this->redirectToRoute('app_basket_display');
        }

        $repository->add($article, true);

        return $this->redirectToRoute('app_basket_display');
    }



}


