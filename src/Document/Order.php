<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ODM\Document]
class Order
{
    #[ODM\Id]
    #[Groups(['listOrder', 'showOrder'])]
    private string $id;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Groups(['listOrder', 'showOrder'])]
    private string $productName;

    #[Assert\NotNull]
    #[Assert\Positive]
    #[Groups(['listOrder', 'showOrder'])]
    private int $quantity;

    #[Assert\NotNull]
    #[Assert\Positive]
    #[Groups(['listOrder', 'showOrder'])]
    private float $unitPrice;

    #[Assert\NotNull]
    #[Assert\PositiveOrZero]
    #[Groups(['listOrder', 'showOrder'])]
    private float $discount;

    #[ODM\NotBlank]
    #[ODM\NotNull]
    #[Assert\Email]
    private string $userEmail;

    /**
     * @return string
     */
    public function getProductName(): string
    {
        return $this->productName;
    }

    /**
     * @param string $productName
     * @return Order
     */
    public function setProductName(string $productName): static
    {
        $this->productName = $productName;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return Order
     */
    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return float
     */
    public function getUnitPrice(): float
    {
        return $this->unitPrice;
    }

    /**
     * @param float $unitPrice
     * @return Order
     */
    public function setUnitPrice(float $unitPrice): static
    {
        $this->unitPrice = $unitPrice;
        return $this;
    }

    /**
     * @return float
     */
    public function getDiscount(): float
    {
        return $this->discount;
    }

    /**
     * @param float $discount
     * @return Order
     */
    public function setDiscount(float $discount): static
    {
        $this->discount = $discount;
        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    #[Groups(['showOrder'])]
    public function getTotal()
    {
        return ($this->quantity * $this->unitPrice) * $this->discount;
    }

    /**
     * @return string
     */
    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

    public function setUserEmail(string $userEmail): static
    {
        $this->userEmail = $userEmail;

        return $this;
    }
}