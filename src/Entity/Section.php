<?php

namespace App\Entity;

use App\Repository\SectionRepository;
use App\Service\Time;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SectionRepository::class)]
#[ORM\Table(name: "sections")]
class Section
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  private $id;

  #[ORM\Column(type: 'string', length: 255)]
  private $title;

  #[ORM\ManyToOne(targetEntity: Course::class, inversedBy: 'sections')]
  #[ORM\JoinColumn(nullable: false)]
  private $course;

  #[ORM\OneToMany(mappedBy: 'section', targetEntity: Lecture::class, orphanRemoval: true)]
  private $lectures;

  public function __construct()
  {
    $this->lectures = new ArrayCollection();
  }

  public function getHoursAndMinutes()
  {
    return Time::getHoursAndMinutesFromSeconds($this->getTotalSeconds());
  }

  public function getTotalSeconds(): int
  {
    $duration = 0;

    foreach ($this->lectures as $lecture) {
      $duration += $lecture->getDuration();
    }

    return $duration;
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

  public function getCourse(): ?Course
  {
    return $this->course;
  }

  public function setCourse(?Course $course): self
  {
    $this->course = $course;

    return $this;
  }

  /**
   * @return Collection|Lecture[]
   */
  public function getLectures(): Collection
  {
    return $this->lectures;
  }

  public function addLecture(Lecture $lecture): self
  {
    if (!$this->lectures->contains($lecture)) {
      $this->lectures[] = $lecture;
      $lecture->setSection($this);
    }

    return $this;
  }

  public function removeLecture(Lecture $lecture): self
  {
    if ($this->lectures->removeElement($lecture)) {
      // set the owning side to null (unless already changed)
      if ($lecture->getSection() === $this) {
        $lecture->setSection(null);
      }
    }

    return $this;
  }
}