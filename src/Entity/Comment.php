<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ORM\Table(name: "comments")]
class Comment
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  private $id;

  #[ORM\Column(type: 'text')]
  private $content;

  #[ORM\Column(type: 'integer')]
  private $rating;

  #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'comments')]
  #[ORM\JoinColumn(nullable: false)]
  private $author;

  #[ORM\ManyToOne(targetEntity: Course::class, inversedBy: 'comments')]
  #[ORM\JoinColumn(nullable: false)]
  private $course;

  #[ORM\Column(type: 'datetime_immutable')]
  private $createdAt;

  #[ORM\Column(type: 'datetime', nullable: true)]
  private $updatedAt;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getContent(): ?string
  {
    return $this->content;
  }

  public function setContent(string $content): self
  {
    $this->content = $content;

    return $this;
  }

  public function getRating(): ?int
  {
    return $this->rating;
  }

  public function setRating(int $rating): self
  {
    $this->rating = $rating;

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

  public function getCourse(): ?Course
  {
    return $this->course;
  }

  public function setCourse(?Course $course): self
  {
    $this->course = $course;

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
}