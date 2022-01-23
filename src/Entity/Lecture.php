<?php

namespace App\Entity;

use App\Repository\LectureRepository;
use App\Service\Time;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LectureRepository::class)]
#[ORM\Table(name: "lectures")]
class Lecture
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  private $id;

  #[ORM\Column(type: 'string', length: 255)]
  private $title;

  #[ORM\Column(type: 'integer')]
  private $duration;

  #[ORM\Column(type: 'datetime_immutable')]
  private $createdAt;

  #[ORM\Column(type: 'datetime', nullable: true)]
  private $updatedAt;

  #[ORM\ManyToOne(targetEntity: Section::class, inversedBy: 'lectures')]
  #[ORM\JoinColumn(nullable: false)]
  private $section;

  #[ORM\Column(type: 'text', nullable: true)]
  private $videoUrl;

  #[ORM\Column(type: 'string', length: 255)]
  private $type;

  #[ORM\Column(type: 'string', length: 255, nullable: true)]
  private $description;

  public function __construct()
  {
    $this->createdAt = new \DateTimeImmutable();
  }

  public function getFormattedHoursMinutesSeconds(): string
  {
    return Time::getFormattedHoursMinutesSeconds($this->duration);
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


  public function getDuration(): ?int
  {
    return $this->duration;
  }

  public function setDuration(int $duration): self
  {
    $this->duration = $duration;

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

  public function getSection(): ?Section
  {
    return $this->section;
  }

  public function setSection(?Section $section): self
  {
    $this->section = $section;

    return $this;
  }

  public function getVideoUrl(): ?string
  {
    return $this->videoUrl;
  }

  public function setVideoUrl(?string $videoUrl): self
  {
    $this->videoUrl = $videoUrl;

    return $this;
  }

  public function getType(): ?string
  {
    return $this->type;
  }

  public function setType(string $type): self
  {
    $this->type = $type;

    return $this;
  }

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(?string $description): self
  {
    $this->description = $description;

    return $this;
  }
}