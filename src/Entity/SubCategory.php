<?php

namespace App\Entity;

use App\Repository\SubCategoryRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubCategoryRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: "sub_categories")]
class SubCategory
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  private $id;

  #[ORM\Column(type: 'string', length: 255)]
  private $name;

  #[ORM\Column(type: 'datetime_immutable')]
  private $createdAt;

  #[ORM\Column(type: 'datetime', nullable: true)]
  private $updatedAt;

  #[ORM\Column(type: 'string', length: 255)]
  private $slug;

  #[ORM\OneToMany(mappedBy: 'subCategory', targetEntity: Course::class, orphanRemoval: true)]
  private $courses;

  #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'subCategories')]
  #[ORM\JoinColumn(nullable: false)]
  private $category;

  public function __construct()
  {
    $this->courses = new ArrayCollection();
    $this->createdAt = new \DateTimeImmutable();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getName(): ?string
  {
    return $this->name;
  }

  public function setName(string $name): self
  {
    $this->name = $name;

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

  #[ORM\PrePersist]
  public function completeNonNullable(): void
  {
    if (empty($this->slug)) {
      $slugify = new Slugify();
      $this->slug = $slugify->slugify($this->name);
    }
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

  public function getSlug()
  {
    return $this->slug;
  }

  public function setSlug(string $slug): self
  {
    $this->slug = $slug;

    return $this;
  }

  /**
   * @return Collection|Course[]
   */
  public function getCourses(): Collection
  {
    return $this->courses;
  }

  public function addCourse(Course $course): self
  {
    if (!$this->courses->contains($course)) {
      $this->courses[] = $course;
      $course->setSubCategory($this);
    }

    return $this;
  }

  public function removeCourse(Course $course): self
  {
    if ($this->courses->removeElement($course)) {
      // set the owning side to null (unless already changed)
      if ($course->getSubCategory() === $this) {
        $course->setSubCategory(null);
      }
    }

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
}