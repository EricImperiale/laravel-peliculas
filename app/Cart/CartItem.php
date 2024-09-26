<?php

namespace App\Cart;


use App\Models\Movie;

class CartItem
{
    public function __construct(
        private Movie $product,
        private int $quantity = 1
    )
    {}

    public function getProduct(): Movie
    {
        return $this->product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function increaseQuantity(int $quantity = 1): void
    {
        $this->quantity += $quantity;
    }

    public function decreaseQuantity(int $quantity = 1): void
    {
        $this->quantity -= $quantity;
    }

    public function getId(): int
    {
        return $this->getProduct()->movie_id;
    }

    public function getPrice(): int
    {
        return $this->getProduct()->price;
    }

    public function getSubtotal(): int
    {
        return $this->getPrice() * $this->quantity;
    }
}
