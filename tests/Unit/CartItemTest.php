<?php

namespace Tests\Unit;

use App\Cart\CartItem;
use App\Models\Movie;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;

// Por defecto, todas las clases de test deben llevar en el nombre el sufijo "Test", así como heredar de
// la clase "TestCase" de phpUnit.
// Dentro de la clase, los métodos que queramos que representen un test deben en su nombre contener el
// prefijo "test". Si no tienen ese prefijo, no son considerados como métodos de test por phpUnit.
// Por convención en Laravel, los nombres de los métodos de los tests se escriben en snake_case.
// Idealmente, el nombre del método debería indicar qué es lo que se está testeando, de la manera más clara
// posible. Sin importar si esto implica que nos queden nombres kilométricos.
class CartItemTest extends TestCase
{
    public function createMovie(int $id = 1, int $price = 1999): Movie
    {
        $movie = new Movie();
        $movie->movie_id = 1;
        $movie->price = 1999;
        return $movie;
    }

    // Como primer test, vamos a hacer que se verifique que podamos instanciar la clase.
    // Esto generalmente no es útil de hacer, pero nos va a servir para ver un poco cómo esto funciona.
    public function test_can_instantiate_a_cartitem_with_a_default_quantity_of_1(): CartItem
    {
        // 1. Definimos lo que necesitamos para probar la funcionalidad.
//        $movie = new Movie();
//        $movie->movie_id = 1;
//        $movie->price = 1999;
        $id = 1;
        $price = 1999;
        $movie = $this->createMovie($id, $price);

        // 2. Ejecutamos lo que queremos testear.
        $item = new \App\Cart\CartItem($movie);

        // 3. Verificamos que el resultado sea correcto.
        // Para verificar las expectativas, phpUnit nos ofrece métodos llamados "assertions" (afirmaciones o
        // verificaciones). Estos métodos pertenecen a TestCase, y llevan como prefijo "assert".
        // Si la instancia se creó correctamente, yo debería poder afirmar 3 cosas:
        // 1. Que $item sea una instancia de CartItem (normalmente esto no necesitamos verificarlo).
        // 2. Que si hacemos un getProduct nos retorne la película que le pasamos como argumento.
        // 3. Que la cantidad sea 1.
        $this->assertInstanceOf(\App\Cart\CartItem::class, $item);
        $this->assertSame($movie, $item->getProduct());
        $this->assertSame($id, $item->getId());
        $this->assertSame($price, $item->getPrice());
        $this->assertSame(1, $item->getQuantity());

        // Retornamos el item. De esta forma, otro test va a poder pedir depender de éste, y recibir como
        // argumento el Item que estamos retornando.
        return $item;
    }

    public function test_can_instantiate_a_cartitem_with_a_custom_quantity(): void
    {
//        $movie = new Movie();
//        $movie->movie_id = 1;
//        $movie->price = 1999;
        $quantity = 5;

        $item = new CartItem($this->createMovie(), $quantity);

        $this->assertSame($quantity, $item->getQuantity());
    }

    public function test_can_set_the_quantity_of_a_cartitem()
    {
//        $movie = new Movie();
//        $movie->movie_id = 1;
//        $movie->price = 1999;
        $item = new CartItem($this->createMovie());
        $quantity = 3;

        $item->setQuantity($quantity);

        $this->assertSame($quantity, $item->getQuantity());
    }

    // https://docs.phpunit.de/en/10.2/writing-tests-for-phpunit.html#test-dependencies
    // Para este test, vamos a pedir que se utilice de base el retorno generado por el test
    //  test_can_instantiate_a_cartitem_with_a_default_quantity_of_1
    // Esto requiere que ese test tenga un return del valor que queremos recibir.
    // Para marcar esa dependencia, y recibir el argumento, tenemos que usar un Attribute de phpUnit
    // llamado "Depends".
    // ¿Qué son los Attributes de php?
    // Se llaman así unas instrucciones que podemos agregar antes de la definición de una función o método
    // que nos permiten pasarle información adicional al mismo, y potencialmente, modificar su
    // comportamiento.
    // Para agregar una atributo, lo tenemos que poner justo antes del método, con la sintaxis:
    // #[Attribute] (sin ser un comentario, por supuesto).
    #[Depends('test_can_instantiate_a_cartitem_with_a_default_quantity_of_1')]
    public function test_can_increase_a_cartitem_quantity_by_a_default_of_1(CartItem $item): CartItem
    {
        // El CartItem viene con una cantidad de 1.
        // Si lo incrementamos en 1, debería darnos 2.
        $item->increaseQuantity();

        $this->assertSame(2, $item->getQuantity());

        return $item;
    }

    #[Depends('test_can_increase_a_cartitem_quantity_by_a_default_of_1')]
    public function test_can_increase_a_cartitem_quantity_by_a_custom_quantity(CartItem $item): CartItem
    {
        // El ítem vino con una cantidad de 2, y lo incrementamos en 3.
        $item->increaseQuantity(3);

        $this->assertSame(5, $item->getQuantity());

        return $item;
    }

    #[Depends('test_can_increase_a_cartitem_quantity_by_a_custom_quantity')]
    public function test_can_decrease_a_cartitem_quantity_by_a_default_of_1(CartItem $item): CartItem
    {
        $item->decreaseQuantity();

        $this->assertSame(4, $item->getQuantity());

        return $item;
    }

    #[Depends('test_can_decrease_a_cartitem_quantity_by_a_default_of_1')]
    public function test_can_decrease_a_cartitem_quantity_by_a_custom_quantity(CartItem $item)
    {
        $item->decreaseQuantity(3);

        $this->assertSame(1, $item->getQuantity());
    }

    public function test_can_get_the_cartitem_subtotal()
    {
//        $expectedSubtotal = 1999 * 3;

        $item = new CartItem($this->createMovie(), 3);

        $this->assertSame(5997, $item->getSubtotal());
    }
}
