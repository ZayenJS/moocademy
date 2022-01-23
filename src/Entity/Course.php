<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use App\Service\Time;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Faker\Factory;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: "courses")]
class Course
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  private $id;

  #[ORM\Column(type: 'string', length: 255)]
  private $title;

  #[ORM\Column(type: 'string', length: 255)]
  private $subtitle;

  #[ORM\Column(type: 'float')]
  private $price;

  #[ORM\Column(type: 'text')]
  private $description;

  #[ORM\Column(type: 'array')]
  private $requirements = [];

  #[ORM\Column(type: 'array')]
  private $goals = [];

  #[ORM\Column(type: 'datetime_immutable')]
  private $createdAt;

  #[ORM\Column(type: 'datetime', nullable: true)]
  private $updatedAt;

  #[ORM\OneToMany(mappedBy: 'course', targetEntity: Comment::class, orphanRemoval: true)]
  private $comments;

  #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'courses')]
  #[ORM\JoinColumn(nullable: false)]
  private $author;

  #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'enrolledCourses')]
  private $participants;

  #[ORM\Column(type: 'array')]
  private $targetAudience = [];

  #[ORM\OneToMany(mappedBy: 'course', targetEntity: Section::class, orphanRemoval: true)]
  private $sections;

  #[ORM\ManyToOne(targetEntity: Language::class, inversedBy: 'courses')]
  #[ORM\JoinColumn(nullable: false)]
  private $language;

  #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'courses')]
  #[ORM\JoinColumn(nullable: false)]
  private $category;

  #[ORM\ManyToOne(targetEntity: SubCategory::class, inversedBy: 'courses')]
  #[ORM\JoinColumn(nullable: false)]
  private $subCategory;

  #[ORM\Column(type: 'string', length: 255)]
  private $thumbnail;

  #[ORM\Column(type: 'string', length: 255)]
  private $slug;

  #[ORM\ManyToOne(targetEntity: Level::class, inversedBy: 'course')]
  #[ORM\JoinColumn(nullable: false)]
  private $level;

  public function getRating(): ?float
  {
    $sum = 0;
    foreach ($this->comments as $comment) {
      $sum += $comment->getRating();
    }
    if (count($this->comments) > 0) {
      return $sum / count($this->comments);
    }
    return 0;
  }

  public function getTotalHours(): int
  {
    return Time::getHoursFromSeconds($this->getTotalSeconds());
  }

  public function getTotalHoursAndMinutes(): string
  {
    return Time::getHoursAndMinutesFromSeconds($this->getTotalSeconds());
  }

  public function getTotalSeconds(): ?int
  {
    $sum = 0;
    foreach ($this->sections as $section) {
      $sum += $section->getTotalSeconds();
    }
    return $sum;
  }

  public function getTotalSessions(): ?int
  {
    $sum = 0;
    foreach ($this->sections as $section) {
      $sum += count($section->getLectures());
    }
    return $sum;
  }

  #[ORM\PrePersist]
  public function onPrePersist()
  {
    $slugifier = new Slugify();
    if (!empty($this->title)) {
      $this->slug = $slugifier->slugify($this->title);
    }
  }

  public function __construct()
  {
    $this->comments = new ArrayCollection();
    $this->participants = new ArrayCollection();
    $this->sections = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getTitle(): ?string
  {
    return $this->title;
  }

  public function setTitle(string $title): self
  {
    $this->title = $title;

    return $this;
  }

  public function getSubtitle(): ?string
  {
    return $this->subtitle;
  }

  public function setSubtitle(string $subtitle): self
  {
    $this->subtitle = $subtitle;

    return $this;
  }


  public function getPrice(): ?float
  {
    return $this->price;
  }

  public function setPrice(float $price): self
  {
    $this->price = $price;

    return $this;
  }

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(string $description): self
  {
    $this->description = $description;

    return $this;
  }

  public function getRequirements(): ?array
  {
    return $this->requirements;
  }

  public function setRequirements(array $requirements): self
  {
    $this->requirements = $requirements;

    return $this;
  }

  public function getGoals(): ?array
  {
    return $this->goals;
  }

  public function setGoals(array $goals): self
  {
    $this->goals = $goals;

    return $this;
  }

  public function getCreatedAt(): ?\DateTimeImmutable
  {
    return $this->createdAt;
  }

  public function setCreatedAt(\DateTimeImmutable $createdAt): self
  {
    $this->createdAt = $createdAt;

    return $this;
  }

  public function getUpdatedAt(): ?\DateTimeInterface
  {
    return $this->updatedAt;
  }

  public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
  {
    $this->updatedAt = $updatedAt;

    return $this;
  }

  /**
   * @return Collection|Comment[]
   */
  public function getComments(): Collection
  {
    return $this->comments;
  }

  public function addComment(Comment $comment): self
  {
    if (!$this->comments->contains($comment)) {
      $this->comments[] = $comment;
      $comment->setCourse($this);
    }

    return $this;
  }

  public function removeComment(Comment $comment): self
  {
    if ($this->comments->removeElement($comment)) {
      // set the owning side to null (unless already changed)
      if ($comment->getCourse() === $this) {
        $comment->setCourse(null);
      }
    }

    return $this;
  }

  public function getAuthor(): ?User
  {
    return $this->author;
  }

  public function setAuthor(?User $author): self
  {
    $this->author = $author;

    return $this;
  }

  /**
   * @return Collection|User[]
   */
  public function getParticipants(): Collection
  {
    return $this->participants;
  }

  public function addParticipant(User $participant): self
  {
    if (!$this->participants->contains($participant)) {
      $this->participants[] = $participant;
    }

    return $this;
  }

  public function removeParticipant(User $participant): self
  {
    $this->participants->removeElement($participant);

    return $this;
  }

  public function getTargetAudience(): ?array
  {
    return $this->targetAudience;
  }

  public function setTargetAudience(array $targetAudience): self
  {
    $this->targetAudience = $targetAudience;

    return $this;
  }

  /**
   * @return Collection|Section[]
   */
  public function getSections(): Collection
  {
    return $this->sections;
  }

  public function addSection(Section $section): self
  {
    if (!$this->sections->contains($section)) {
      $this->sections[] = $section;
      $section->setCourse($this);
    }

    return $this;
  }

  public function removeSection(Section $section): self
  {
    if ($this->sections->removeElement($section)) {
      // set the owning side to null (unless already changed)
      if ($section->getCourse() === $this) {
        $section->setCourse(null);
      }
    }

    return $this;
  }

  public function getLanguage(): ?Language
  {
    return $this->language;
  }

  public function setLanguage(?Language $language): self
  {
    $this->language = $language;

    return $this;
  }

  public function getCategory(): ?Category
  {
    return $this->category;
  }

  public function setCategory(?Category $category): self
  {
    $this->category = $category;

    return $this;
  }

  public function getSubCategory(): ?SubCategory
  {
    return $this->subCategory;
  }

  public function setSubCategory(?SubCategory $subCategory): self
  {
    $this->subCategory = $subCategory;

    return $this;
  }

  public function getThumbnail(): ?string
  {
    return $this->thumbnail;
  }

  public function setThumbnail(string $thumbnail): self
  {
    $this->thumbnail = $thumbnail;

    return $this;
  }

  public function getSlug(): ?string
  {
    return $this->slug;
  }

  public function setSlug(string $slug): self
  {
    $this->slug = $slug;

    return $this;
  }

  public function getLevel(): ?Level
  {
    return $this->level;
  }

  public function setLevel(?Level $level): self
  {
    $this->level = $level;

    return $this;
  }
}