<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
  #[Route('/', name: 'home')]
  public function index(CategoryRepository $categoryRepo): Response
  {
    $categories = $categoryRepo->findAll();

    return $this->render('home/index.html.twig', [
      'categories' => $categories,
    ]);
  }
}