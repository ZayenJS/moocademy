<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
  #[Route('/panier', name: 'cart_index')]
  public function index(CategoryRepository $categoryRepository): Response
  {
    $categories = $categoryRepository->findAll();

    return $this->render('cart/index.html.twig', [
      'controller_name' => 'CartController',
      'categories' => $categories,
    ]);
  }
}