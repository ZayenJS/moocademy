<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
  #[Route('/login', name: 'auth_login')]
  public function login(CategoryRepository $categoryRepository, AuthenticationUtils $utils): Response
  {
    $categories = $categoryRepository->findAll();

    $error = $utils->getLastAuthenticationError();

    return $this->render('auth/login.html.twig', [
      'categories' => $categories,
      'hasError' => $error !== null,
    ]);
  }

  #[Route('/register', name: 'auth_register')]
  public function register(
    Request $request,
    EntityManagerInterface $manager,
    UserPasswordHasherInterface $hasher,
    CategoryRepository $categoryRepository
  ): Response {
    $user = new User();
    $form = $this->createForm(RegisterType::class, $user);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $hash = $hasher->hashPassword($user, $user->getPassword());
      $user->setPassword($hash);
      $manager->persist($user);
      $manager->flush();

      $this->addFlash('success', "Votre inscription est validée! Connectez vous au site!");

      return $this->redirectToRoute("auth_login");
    }

    $categories = $categoryRepository->findAll();

    return $this->render('auth/register.html.twig', [
      'form' => $form->createView(),
      'categories' => $categories,
    ]);
  }

  #[Route('/logout', name: 'auth_logout')]
  public function logout(): void
  {
    // the logout is handled by the security system of symfony
  }
}