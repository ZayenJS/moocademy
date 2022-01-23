<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: "users")]
#[UniqueEntity("email", message: "Cet email est déjà utilisé. Veuillez en choisir un autre")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  private $id;

  #[ORM\Column(type: 'string', length: 180, unique: true)]
  private $email;

  #[ORM\Column(type: 'json')]
  private $roles = [];

  #[ORM\Column(type: 'string')]
  private $password;

  #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Order::class)]
  private $orders;

  #[ORM\OneToOne(mappedBy: 'owner', targetEntity: Cart::class, cascade: ['persist', 'remove'])]
  private $cart;

  #[ORM\Column(type: 'datetime_immutable')]
  private $createdAt;

  #[ORM\Column(type: 'datetime', nullable: true)]
  private $updatedAt;

  #[ORM\Column(type: 'string', length: 255)]
  private $firstName;

  #[ORM\Column(type: 'string', length: 255)]
  private $lastName;

  #[ORM\Column(type: 'string', length: 255)]
  private $avatar;

  #[ORM\ManyToMany(targetEntity: Address::class, inversedBy: 'residents')]
  private $addresses;

  #[ORM\OneToMany(mappedBy: 'author', targetEntity: Comment::class)]
  private $comments;

  #[ORM\OneToMany(mappedBy: 'author', targetEntity: Course::class)]
  private $courses;

  #[ORM\ManyToMany(targetEntity: Course::class, mappedBy: 'participants')]
  private $enrolledCourses;

  public function __construct()
  {
    $this->orders = new ArrayCollection();
    $this->addresses = new ArrayCollection();
    $this->comments = new ArrayCollection();
    $this->courses = new ArrayCollection();
    $this->enrolledCourses = new ArrayCollection();
    $this->createdAt = new \DateTimeImmutable();
  }

  public function getFullname(): string
  {
    return $this->firstName . ' ' . $this->lastName;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getEmail(): ?string
  {
    return $this->email;
  }

  public function setEmail(string $email): self
  {
    $this->email = $email;

    return $this;
  }

  /**
   * A visual identifier that represents this user.
   *
   * @see UserInterface
   */
  public function getUserIdentifier(): string
  {
    return (string) $this->email;
  }

  /**
   * @see UserInterface
   */
  public function getRoles(): array
  {
    $roles = $this->roles;
    // guarantee every user at least has ROLE_USER
    $roles[] = 'ROLE_USER';

    return array_unique($roles);
  }

  public function setRoles(array $roles): self
  {
    $this->roles = $roles;

    return $this;
  }

  /**
   * @see PasswordAuthenticatedUserInterface
   */
  public function getPassword(): string
  {
    return $this->password;
  }

  public function setPassword(string $password): self
  {
    $this->password = $password;

    return $this;
  }

  /**
   * @see UserInterface
   */
  public function eraseCredentials()
  {
    // If you store any temporary, sensitive data on the user, clear it here
    // $this->plainPassword = null;
  }

  /**
   * @return Collection|Order[]
   */
  public function getOrders(): Collection
  {
    return $this->orders;
  }

  public function addOrder(Order $order): self
  {
    if (!$this->orders->contains($order)) {
      $this->orders[] = $order;
      $order->setOwner($this);
    }

    return $this;
  }

  public function removeOrder(Order $order): self
  {
    if ($this->orders->removeElement($order)) {
      // set the owning side to null (unless already changed)
      if ($order->getOwner() === $this) {
        $order->setOwner(null);
      }
    }

    return $this;
  }

  public function getCart(): ?Cart
  {
    return $this->cart;
  }

  public function setCart(Cart $cart): self
  {
    // set the owning side of the relation if necessary
    if ($cart->getOwner() !== $this) {
      $cart->setOwner($this);
    }

    $this->cart = $cart;

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

  public function getFirstName(): ?string
  {
    return $this->firstName;
  }

  public function setFirstName(string $firstName): self
  {
    $this->firstName = $firstName;

    return $this;
  }

  public function getLastName(): ?string
  {
    return $this->lastName;
  }

  public function setLastName(string $lastName): self
  {
    $this->lastName = $lastName;

    return $this;
  }

  public function getAvatar(): ?string
  {
    return $this->avatar;
  }

  public function setAvatar(string $avatar): self
  {
    $this->avatar = $avatar;

    return $this;
  }

  /**
   * @return Collection|Address[]
   */
  public function getAddresses(): Collection
  {
    return $this->addresses;
  }

  public function addAddress(Address $address): self
  {
    if (!$this->addresses->contains($address)) {
      $this->addresses[] = $address;
    }

    return $this;
  }

  public function removeAddress(Address $address): self
  {
    $this->addresses->removeElement($address);

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
      $comment->setAuthor($this);
    }

    return $this;
  }

  public function removeComment(Comment $comment): self
  {
    if ($this->comments->removeElement($comment)) {
      // set the owning side to null (unless already changed)
      if ($comment->getAuthor() === $this) {
        $comment->setAuthor(null);
      }
    }

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
      $course->setAuthor($this);
    }

    return $this;
  }

  public function removeCourse(Course $course): self
  {
    if ($this->courses->removeElement($course)) {
      // set the owning side to null (unless already changed)
      if ($course->getAuthor() === $this) {
        $course->setAuthor(null);
      }
    }

    return $this;
  }

  /**
   * @return Collection|Course[]
   */
  public function getEnrolledCourses(): Collection
  {
    return $this->enrolledCourses;
  }

  public function addEnrolledCourse(Course $enrolledCourse): self
  {
    if (!$this->enrolledCourses->contains($enrolledCourse)) {
      $this->enrolledCourses[] = $enrolledCourse;
      $enrolledCourse->addParticipant($this);
    }

    return $this;
  }

  public function removeEnrolledCourse(Course $enrolledCourse): self
  {
    if ($this->enrolledCourses->removeElement($enrolledCourse)) {
      $enrolledCourse->removeParticipant($this);
    }

    return $this;
  }
}