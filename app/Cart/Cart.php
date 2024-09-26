<?php

namespace App\Cart;

use App\Models\Movie;

class Cart
{
    /** @var array|CartITem[] */
    private array $items = [];

    public function addItem(CartItem $newItem)
    {
        foreach($this->items as $item) {
            if($item->getId() === $newItem->getId()) {
                $item->increaseQuantity();
                return;
            }
        }

        $this->items[] = $newItem;
    }

    public function removeItem(int $id)
    {
        foreach($this->items as $key => $item) {
            if($item->getId() === $id) {
                // Removemos el ítem usando la clave que le corresponde.
                unset($this->items[$key]);
                // Tenemos que usar la key como hicimos recién para poder eliminar el elemento. Si tratamos
                // de eliminarlo con unset($item), lo que hacemos es borrar la variable $item, no el valor
                // del array.
//                unset($item); // No funciona.

                // Como borramos un elemento por su clave, esto me puede dejar huecos en la numeración
                // del array.
                // Por ejemplo, si tenemos un array de 3 elementos:
                //  $libros = ['Señor de los Anillos', 'Juego de Tronos', 'Drácula'];
                // Tenemos las claves:
                //  $libros[0]; // 'Señor de los Anillos'
                //  $libros[1]; // 'Juego de Tronos'
                //  $libros[2]; // 'Drácula'
                // Si borramos el elemento 1 con un unset:
                //  unset($libros[1]);
                // Nos quedan las claves 0 y 2 en el array. Es decir, un hueco.
                // Si queremos "resetear" las claves del array para evitar eso, podemos usar la función
                // array_values().
                $this->items = array_values($this->items);

                // Terminamos así no sigue loopeando.
                return;
            }
        }
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getItem(int $id): ?CartItem
    {
        foreach($this->items as $item) {
            if($item->getId() === $id) {
                return $item;
            }
        }

        return null;
    }

    public function setQuantity(int $id, int $quantity): void
    {
        $this->getItem($id)->setQuantity($quantity);
    }

    public function increaseQuantity(int $id, int $quantity = 1): void
    {
        $this->getItem($id)->increaseQuantity($quantity);
    }

    public function decreaseQuantity(int $id, int $quantity = 1): void
    {
        $this->getItem($id)->decreaseQuantity($quantity);
    }

    public function getTotal(): int|float
    {
        $total = 0;

        foreach($this->items as $item) {
            $total += $item->getSubtotal();
        }

        return $total;
    }
}
