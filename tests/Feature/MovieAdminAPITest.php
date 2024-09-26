<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class MovieAdminAPITest extends TestCase
{
    // Con el trait RefreshDatabase pedimos que Laravel corra las migrations desde 0 para cada test.
    // Esto borra la base de datos, la recrea, y corre los tests en una transacción.
    // No corre, por defecto, los seeders. Así que la base empieza vacía.
    // Si queremos que los seeders corran, tenemos que aclararlo con la propiedad $seed.
    use RefreshDatabase;

    protected bool $seed = true;

    public function withUser(): self
    {
        $user = new User();
        $user->user_id = 1;
        // El actingAs nos permite pedirle a Laravel que considere a este usuario como autenticado para la
        // petición.
        return $this->actingAs($user);
    }

    public function test_unauthenticated_root_movies_path_returns_all_movies_returns_401()
    {
        $response = $this->getJson('/api/movies');

        $response->assertStatus(401);
    }

    public function test_root_movies_path_returns_all_movies(): void
    {
        /*
         * Para el testeo de la aplicación (puede ser una API o un test de la página), Laravel agrega
         * métodos que nos permiten realizar peticiones que simulen una petición común. No imita el
         * comportamiento completo de un browser, ni mucho menos, pero es suficiente para que Laravel
         * arranque la aplicación de manera normal y nos retorne los pedidos. Incluye también algunos métodos
         * para interactuar con el HTML y analizar su contenido, pero no nos son relevantes en este caso,
         * ya que vamos a probarlo como una API REST.
         *
         * Para realizar peticiones, Laravel agrega métodos que se llaman igual que los métodos de HTTP que
         * queremos simular:
         * - get
         * - post
         * - put
         * - patch
         * - delete
         * - options
         *
         * Y también tiene las variantes para probar JSONs:
         * - getJson
         * - postJson
         * - putJson
         * - patchJson
         * - deleteJson
         * - optionsJson
         *
         * La diferencia entre unos y otros es lo que esperan recibir, y cómo simulan la petición.
         * Los primeros buscan simular la petición como la haría un browser para obtener HTML.
         * Los segundos simulan una petición para una API REST (ej: Ajax) y esperan recibir JSON.
         *
         * Todos reciben como primer argumento la ruta a partir de la raíz del sitio/API.
         * En el caso de una API, que tenga sus rutas definidas en [routes/api.php], van a empezar con el
         * prefijo 'api/'.
         *
         * Todos esos métodos retornan un objeto respuesta, que tiene algunos métodos de ayuda de
         * depuración, y assertions extras para verificar el resultado.
         *
         * Noten que aprovechamos para crear un archivo [.env.testing] que sobrescriba alos valores del
         * [.env] que no nos sirven para el testing, como es el caso de [APP_URL].
         */
        $response = $this->withUser()->getJson('/api/movies');

        /*
         * Las verificaciones para la API aceptan una interfaz fluida.
         *
         * Para las respuestas, vamos a esperar recibir un JSON con este formato:
         *
         * {
         *     status: 0,
         *     data: [
         *         {
         *             movie_id: 1,
         *             title: '...',
         *             ...
         *         },
         *         ...
         *     ]
         * }
         *
         * El status va a ser un campo para indicar el éxito o error de la respuesta.
         * El campo data va a ser el que contenga la data asociada con la respuesta.
         */
        $response
            // Verificamos que el resultado llegue correctamente.
            ->assertStatus(200)
            // Lo siguiente que queremos verificar es que lleguen toda la cantidad de películas que debe
            // existir.
            ->assertJsonCount(4, 'data')
            ->assertJsonStructure([
                // Indicamos que tiene que haber un campo 'status', sin importar su valor.
                'status',
                // Indicamos que tiene que haber un campo 'data' que sea un objeto/array.
                'data' => [
                    // Indicamos que este valor es un array, compuesto por los siguientes objetos.
                    '*' => [
                        'movie_id',
                        'country_id',
                        'classification_id',
                        'title',
                        'synopsis',
                        'price',
                        'release_date',
                        'cover',
                        'cover_description',
                        'created_at',
                        'updated_at',
                        'deleted_at',
                    ],
                ]
            ])
            // Verificamos que el status sea 0
            ->assertJsonPath('status', 0);
    }

    public function test_requesting_a_movie_by_its_id_returns_the_movie()
    {
        $id = 1;
        $response = $this->withUser()->getJson('/api/movies/' . $id);

//        $response->dump();

        $response
            ->assertStatus(200)
            // Usamos la API fluida: https://laravel.com/docs/10.x/http-tests#fluent-json-testing
            ->assertJson(fn (AssertableJson $json) =>
                $json
                    ->where('status', 0)
                    ->where('data.movie_id', $id)
                    ->where('data.title', 'El Señor de los Anillos: La Comunidad del Anillo')
                    ->whereAllType([
                        'data.movie_id' => 'integer',
                        'data.country_id' => 'integer',
                        'data.classification_id' => 'integer',
                        'data.title' => 'string',
                        'data.price' => 'integer|double',
                        'data.release_date' => 'string',
                        'data.synopsis' => 'string',
                        'data.cover' => 'string|null',
                        'data.cover_description' => 'string|null',
                        'data.created_at' => 'string',
                        'data.updated_at' => 'string',
                        'data.deleted_at' => 'string|null',
                    ])
            );
    }

    public function test_requesting_a_movie_by_an_id_that_doesnt_exists_returns_404()
    {
        $response = $this->withUser()->getJson('/api/movies/5');

        $response->assertStatus(404);
    }

    public function test_can_create_a_movie_by_using_a_post_request_and_data()
    {
        $postData = [
            'country_id' => 2,
            'classification_id' => 1,
            'title' => 'Corazón de Dragón',
            'synopsis' => 'La aventura de un caballero con el último dragón.',
            'release_date' => '1998-02-16',
            'price' => 19.99,
        ];
        $response = $this->withUser()->postJson('/api/movies', $postData);

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json
                    ->where('status', 0)
                    ->where('data.movie_id', 5)
            );

        // Podríamos incluso hacer una segunda petición para verificar que llegue la película 5 con los datos
        // que mandamos.
        $response = $this->getJson('/api/movies/5');

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json
                    ->where('status', 0)
                    ->where('data.movie_id', 5)
                    ->where('data.country_id', $postData['country_id'])
                    ->where('data.classification_id', $postData['classification_id'])
                    ->where('data.title', $postData['title'])
                    ->where('data.synopsis', $postData['synopsis'])
                    ->where('data.release_date', $postData['release_date'])
                    ->where('data.price', $postData['price'])
                    ->where('data.cover', null)
                    // etc() significa que puede haber otras propiedades más en el objeto JSON además de las
                    // que estamos pidiendo.
                    ->etc()
            );
    }
}
