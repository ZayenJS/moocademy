<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
#[ORM\Table(name: "carts")]
class Cart
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  private $id;

  #[ORM\OneToOne(inversedBy: 'cart', targetEntity: User::class, cascade: ['persist', 'remove'])]
  #[ORM\JoinColumn(nullable: false)]
  private $owner;

  #[ORM\OneToMany(mappedBy: 'cart', targetEntity: CartItem::class, orphanRemoval: true)]
  private $items;

  public function __construct()
  {
    $this->items = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getOwner(): ?User
  {
    return $this->owner;
  }

  public function setOwner(User $owner): self
  {
    $this->owner = $owner;

    return $this;
  }

  /**
   * @return Collection|CartItem[]
   */
  public function getItems(): Collection
  {
    return $this->items;
  }

  public function addItem(CartItem $item): self
  {
    if (!$this->items->contains($item)) {
      $this->items[] = $item;
      $item->setCart($this);
    }

    return $this;
  }

  public function removeItem(CartItem $item): self
  {
    if ($this->items->removeElement($item)) {
      // set the owning side to null (unless already changed)
      if ($item->getCart() === $this) {
        $item->setCart(null);
      }
    }

    return $this;
  }
}