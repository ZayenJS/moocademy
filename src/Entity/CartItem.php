<?php

namespace App\Entity;

use App\Repository\CartItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartItemRepository::class)]
#[ORM\Table(name: "cart_items")]
class CartItem
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  private $id;

  #[ORM\ManyToOne(targetEntity: Cart::class, inversedBy: 'items')]
  #[ORM\JoinColumn(nullable: false)]
  private $cart;

  #[ORM\Column(type: 'integer')]
  private $quantity;

  #[ORM\Column(type: 'datetime_immutable')]
  private $createdAt;

  #[ORM\Column(type: 'datetime', nullable: true)]
  private $updatedAt;


  public function getId(): ?int
  {
    return $this->id;
  }

  public function getCart(): ?Cart
  {
    return $this->cart;
  }

  public function setCart(?Cart $cart): self
  {
    $this->cart = $cart;

    return $this;
  }

  public function getQuantity(): ?int
  {
    return $this->quantity;
  }

  public function setQuantity(int $quantity): self
  {
    $this->quantity = $quantity;

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