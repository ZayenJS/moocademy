<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Course;
use App\Entity\Language;
use App\Entity\Lecture;
use App\Entity\Level;
use App\Entity\Section;
use App\Entity\SubCategory;
use App\Entity\User;
use App\Repository\AddressRepository;
use App\Repository\CourseRepository;
use App\Repository\LanguageRepository;
use App\Repository\LevelRepository;
use App\Repository\SubCategoryRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
  private Generator $faker;

  private array $_languages = [
    'Français',
    'Anglais',
    'Espagnol',
    'Allemand',
    'Portugais',
  ];
  private array $_categories = [
    [
      'name' => 'Développement',
      'subCategories' => [
        "Développement web",
        "Développement mobile",
        "Développement de jeux",
        "Développement logiciel",
        "Conception et développement de bases de données",
      ],
    ],
    [
      'name' => 'Business',
      'subCategories' => [
        "Entreprenariat",
        "Communication",
        "Gestion",
        "Ventes"
      ],
    ],
    [
      'name' => 'Finance et comptabilité',
      'subCategories' => [
        'Comptabilité et tenue de comptes',
        'Finance',
        'Cryptomonnaie et blockchain',
        'Contrôle de gestion',
      ]
    ],
    [
      'name' => 'Informatique et logiciels',
      'subCategories' => [
        'Informatique',
        'Logiciels',
        'Sécurité',
        'Réseaux et réseaux sociaux',
        'Multimédia',
      ]
    ],
    [
      'name' => 'Musique',
      'subCategories' => [
        'Instruments',
        'Production musicale',
        'Chant',
        'Autres',
      ]
    ]
  ];
  private array  $_levels = [
    'Débutant',
    'Intermédiaire',
    'Confirmé',
    'Tous les niveaux'
  ];


  public function __construct(
    private UserPasswordHasherInterface $passwordHasher,
    private AddressRepository $addressRepository,
    private UserRepository $userRepository,
    private CourseRepository $courseRepository,
    private LanguageRepository $languageRepository,
    private SubCategoryRepository $subCategoryRepository,
    private LevelRepository $levelRepository,
  ) {
    $this->faker = Factory::create('fr_FR');
  }

  public function load(ObjectManager $manager): void
  {
    // $this->loadAddresses($manager);
    // $this->loadUsers($manager);
    // $this->loadCategories($manager);
    // $this->loadLanguages($manager);
    // $this->loadLevels($manager);
    $this->loadCourses($manager);
    $this->loadComments($manager);

    $manager->flush();
  }

  public function loadAddresses(ObjectManager $manager): void
  {
    for ($i = 1; $i < $this->faker->numberBetween(70, 100); $i++) {
      $address = new Address();
      $address->setAddress($this->faker->buildingNumber() . ' ' . $this->faker->streetName());
      $address->setPostalCode($this->faker->postcode());
      $address->setCity($this->faker->city());
      $address->setCountry($this->faker->country());
      $manager->persist($address);

      echo "Address" . $i . " created\n";
    }

    $manager->flush();
  }

  public function loadUsers(ObjectManager $manager)
  {
    $addresses = $this->addressRepository->findAll();

    $user = new User();
    $user->setFirstName('John');
    $user->setLastName('Doe');
    $user->setEmail('admin@test.com');
    $user->setRoles(['ROLE_ADMIN']);
    $user->setAvatar('https://www.gravatar.com/avatar/dba6bae8c566f9d4041fb9cd9ada7741?d=identicon');
    $user->setPassword($this->passwordHasher->hashPassword($user, 'admin'));
    $user->addAddress($this->faker->randomElement($addresses));
    $manager->persist($user);

    for ($i = 1; $i < $this->faker->numberBetween(75, 120); $i++) {
      $user = new User();
      $user->setFirstName($this->faker->firstName());
      $user->setLastName($this->faker->lastName());
      $user->setEmail($this->faker->email());
      $user->setRoles(['ROLE_USER']);
      $user->setAvatar($this->faker->imageUrl(640, 480, 'people'));
      $user->setPassword($this->passwordHasher->hashPassword($user, 'user'));
      $user->addAddress($this->faker->randomElement($addresses));
      $manager->persist($user);

      echo "User " . $i . " created\n";
    }

    $manager->flush();
  }

  public function loadCategories(ObjectManager $manager)
  {
    foreach ($this->_categories as $category) {
      $categoryEntity = new Category();
      $categoryEntity->setName($category['name']);
      $manager->persist($categoryEntity);

      foreach ($category['subCategories'] as $subCategory) {
        $subCategoryEntity = new SubCategory();
        $subCategoryEntity->setName($subCategory);
        $subCategoryEntity->setCategory($categoryEntity);
        $manager->persist($subCategoryEntity);
      }

      echo "Category " . $category['name'] . " created\n";
    }

    $manager->flush();
  }

  public function loadLanguages(ObjectManager $manager)
  {
    foreach ($this->_languages as $language) {
      $languageEntity = new Language();
      $languageEntity->setName($language);
      $manager->persist($languageEntity);

      echo "Language " . $language . " created\n";
    }

    $manager->flush();
  }

  public function loadLevels(ObjectManager $manager)
  {
    foreach ($this->_levels as $level) {
      $levelEntity = new Level();
      $levelEntity->setName($level);
      $manager->persist($levelEntity);

      echo "Level " . $level . " created\n";
    }

    $manager->flush();
  }

  public function loadCourses(ObjectManager $manager)
  {
    $lectureType = [
      'video',
      'text',
      'quizz'
    ];
    $context = stream_context_create([
      'http' => [
        'header' => "Authorization: {$_ENV["PEXELS_AUTHORIZATION"]}"
      ]
    ]);

    $per_page = 80;

    $coursesNumber = $this->faker->numberBetween($per_page * 6, $per_page * 15);

    $photos = [];
    $videos = [];

    for ($i = 1; $i <= ceil($coursesNumber / $per_page); $i++) {
      echo "Loading images page " . $i . " of " . ceil($coursesNumber / $per_page) . "\n";
      $pexelsApiResponse = file_get_contents("https://api.pexels.com/v1/search?query=code&size=medium&orientation=landscape&per_page=$per_page&page=$i", false, $context);
      $json = json_decode($pexelsApiResponse, true);

      for ($j = 0; $j < count($json['photos']); $j++) {
        $photos[] = $json['photos'][$j]['src']['medium'];
      }
    }

    for ($i = 1; $i <= ceil($coursesNumber / $per_page); $i++) {
      echo "Loading video urls page " . $i . " of " . ceil($coursesNumber / $per_page) . "\n";
      // using pexels video api
      $pexelsApiResponse = file_get_contents("https://api.pexels.com/v1/videos/search?query=code&orientation=landscape&size=medium&per_page=$per_page&page=$i", false, $context);
      $json = json_decode($pexelsApiResponse, true);

      for ($j = 0; $j < count($json['videos']); $j++) {
        // search in associative array for key quality
        $hdVideo = $json['videos'][$j]['video_files'][array_search('hd', array_column($json['videos'][$j]['video_files'], 'quality'))];

        $videos[] = [
          'link' =>  $hdVideo['link'],
          'duration' => $json['videos'][$j]['duration'],
        ];
      }
    }

    for ($i = 1; $i < $coursesNumber; $i++) {
      $audience = [
        'All',
        'Adults',
        'Children',
        'Teenagers',
        'Teens',
        'Young',
        'Seniors',
        'Friends',
        'Students',
        'Professionals',
        'Others',
      ];
      $targetAudience = [];


      for ($j = 0; $j < $this->faker->numberBetween(2, 5); $j++) {
        $targetAudience[] = $this->faker->randomElement($audience);
      }


      $subCategories = $this->subCategoryRepository->findAll();
      $languages = $this->languageRepository->findAll();
      $users = $this->userRepository->findAll();
      $levels = $this->levelRepository->findAll();

      $courseSubCategory = $this->faker->randomElement($subCategories);
      $courseCategory = $courseSubCategory->getCategory();
      $description = $this->faker->paragraphs($this->faker->numberBetween(3, 8), true);
      $title = $this->faker->sentence($this->faker->numberBetween(5, 8));
      $subtitle = $this->faker->sentence($this->faker->numberBetween(7, 23));

      $courseThumbnail = $this->faker->randomElement($photos);

      $courseEntity = new Course();
      $courseEntity->setTitle($title);
      $courseEntity->setSubtitle($subtitle);
      $courseEntity->setPrice($this->faker->randomFloat(2, 9, 199.99));
      $courseEntity->setDescription($description);
      $courseEntity->setRequirements($this->faker->paragraphs(3));
      $courseEntity->setGoals($this->faker->paragraphs(6));
      $courseEntity->setLevel($this->faker->randomElement($levels));
      $courseEntity->setCategory($courseCategory);
      $courseEntity->setSubCategory($courseSubCategory);
      $courseEntity->setThumbnail($courseThumbnail);
      $courseEntity->setCreatedAt($this->randomDateTimeImmutable());
      $courseEntity->setLanguage($this->faker->randomElement($languages));
      $courseEntity->setAuthor($this->faker->randomElement($users));
      $courseEntity->setTargetAudience($targetAudience);
      $manager->persist($courseEntity);

      for ($j = 0; $j < $this->faker->numberBetween(8, 44); $j++) {
        $section = new Section();
        $section->setTitle($this->faker->sentence(1));
        $section->setCourse($courseEntity);
        $manager->persist($section);

        for ($k = 0; $k < $this->faker->numberBetween(6, 22); $k++) {
          $video = $this->faker->randomElement($videos);
          $lecture = new Lecture();
          $lecture->setTitle($this->faker->sentence(1));
          $lecture->setDuration($video['duration'] * 60); // this is seconds
          $lecture->setSection($section);
          $lecture->setVideoUrl($video['link']);
          $lecture->setDescription($this->faker->paragraph(2));
          $lecture->setType($this->faker->randomElement($lectureType));
          $manager->persist($lecture);
        }
      }

      echo "Course " . $i . " created\n";
      $manager->flush();
    }
  }

  public function loadComments(ObjectManager $manager)
  {
    $users = $this->userRepository->findAll();
    $courses = $this->courseRepository->findAll();

    for ($i = 1; $i < $this->faker->numberBetween(450, 800); $i++) {
      $comment = new Comment();
      $comment->setAuthor($this->faker->randomElement($users));
      $comment->setContent($this->faker->paragraph(5));
      $comment->setCreatedAt($this->randomDateTimeImmutable());
      $comment->setCourse($this->faker->randomElement($courses));
      $comment->setRating($this->faker->numberBetween(1, 5));
      $manager->persist($comment);

      echo "Comment " . $i . " created\n";
    }

    $manager->flush();
  }

  public function randomDateTimeImmutable($startDate = '-6 months', $endDate = 'now')
  {
    $randomTimestamp = mt_rand(strtotime($startDate), strtotime($endDate));
    return new \DateTimeImmutable('@' . $randomTimestamp);
  }
}