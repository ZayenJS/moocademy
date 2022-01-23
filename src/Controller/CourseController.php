<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Course;
use App\Entity\SubCategory;
use App\Repository\CategoryRepository;
use App\Repository\LanguageRepository;
use App\Repository\LevelRepository;
use App\Service\Pagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractController
{
  #[Route('/courses/{slug}', name: 'course_category')]
  public function categoryPage(
    Category $category,
    CategoryRepository $categoryRepository,
    LevelRepository $levelRepository,
    LanguageRepository $languageRepository,
    Pagination $paginator,
    Request $request
  ): Response {
    $categories = $categoryRepository->findAll();
    $levels = $levelRepository->findAll();
    $languages = $languageRepository->findAll();

    $page = $request->query->getInt('page', 1);

    $data = $paginator->setEntityClass(Course::class)
      ->setCurrentPage($page)
      ->setCriteria([
        'category' => $category->getId(),
      ])
      ->getData();

    return $this->render('course/index.html.twig', [
      'categories' => $categories,
      'levels' => $levels,
      'languages' => $languages,
      'category' => $category,
      'courses' => $data['data'],
      'total' => $data['total'],
      'pages' => $data['pages'],
      'currentPage' => $page,
      'route' => 'course_category',
      'routeParams' => [
        'slug' => $category->getSlug(),
      ]
    ]);
  }

  #[Route('/courses/{slug}/{sub_category_slug}', name: 'course_sub_category')]
  #[ParamConverter('subCategory', options: ['mapping' => ['sub_category_slug' => 'slug']])]
  public function subCategoryPage(
    Category $category,
    SubCategory $subCategory,
    CategoryRepository $categoryRepo,
    LevelRepository $levelRepository,
    LanguageRepository $languageRepository,
    Pagination $paginator,
    Request $request
  ): Response {
    $categories = $categoryRepo->findAll();
    $levels = $levelRepository->findAll();
    $languages = $languageRepository->findAll();

    $page = $request->query->getInt('page', 1);

    $data = $paginator->setEntityClass(Course::class)
      ->setCurrentPage($page)
      ->setCriteria([
        'subCategory' => $subCategory->getId(),
      ])
      ->getData();

    return $this->render('course/index.html.twig', [
      'categories' => $categories,
      'levels' => $levels,
      'languages' => $languages,
      'category' => $subCategory,
      'courses' => $data['data'],
      'total' => $data['total'],
      'pages' => $data['pages'],
      'currentPage' => $page,
      'route' => 'course_sub_category',
      'routeParams' => [
        'slug' => $category->getSlug(),
        'sub_category_slug' => $subCategory->getSlug(),
      ]
    ]);
  }

  #[Route('/courses/{slug}/{sub_category_slug}/{course_slug}', name: 'course_show')]
  #[ParamConverter('subCategory', options: ['mapping' => ['sub_category_slug' => 'slug']])]
  #[ParamConverter('course', options: ['mapping' => ['course_slug' => 'slug']])]
  public function show(
    Category $category,
    SubCategory $subCategory,
    Course $course,
    CategoryRepository $categoryRepo
  ): Response {
    $categories = $categoryRepo->findAll();



    return $this->render('course/show.html.twig', [
      'categories' => $categories,
      'course' => $course,
    ]);
  }
}