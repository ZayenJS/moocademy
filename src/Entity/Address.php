<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
#[ORM\Table(name: "addresses")]
class Address
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  private $id;

  #[ORM\Column(type: 'string', length: 255)]
  private $address;

  #[ORM\Column(type: 'string', length: 255)]
  private $city;

  #[ORM\Column(type: 'string', length: 255)]
  private $country;

  #[ORM\Column(type: 'string', length: 255)]
  private $postalCode;

  #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'addresses')]
  private $residents;

  public function __construct()
  {
    $this->residents = new ArrayCollection();
  }


  public function getId(): ?int
  {
    return $this->id;
  }

  public function getAddress(): ?string
  {
    return $this->address;
  }

  public function setAddress(string $address): self
  {
    $this->address = $address;

    return $this;
  }

  public function getCity(): ?string
  {
    return $this->city;
  }

  public function setCity(string $city): self
  {
    $this->city = $city;

    return $this;
  }

  public function getCountry(): ?string
  {
    return $this->country;
  }

  public function setCountry(string $country): self
  {
    $this->country = $country;

    return $this;
  }

  public function getPostalCode(): ?string
  {
    return $this->postalCode;
  }

  public function setPostalCode(string $postalCode): self
  {
    $this->postalCode = $postalCode;

    return $this;
  }

  /**
   * @return Collection|User[]
   */
  public function getResidents(): Collection
  {
    return $this->residents;
  }

  public function addUser(User $user): self
  {
    if (!$this->residents->contains($user)) {
      $this->residents[] = $user;
      $user->addAddress($this);
    }

    return $this;
  }

  public function removeUser(User $user): self
  {
    if ($this->residents->removeElement($user)) {
      $user->removeAddress($this);
    }

    return $this;
  }
}