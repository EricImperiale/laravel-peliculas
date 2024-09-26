<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
 * Como único requisito de Eloquent, heredamos de la clase "Model".
 */
/**
 * App\Models\Movie
 *
 * @property int $movie_id
 * @property string $title
 * @property int $price
 * @property string $release_date
 * @property string $synopsis
 * @property string|null $cover
 * @property string|null $cover_description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Movie newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Movie newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Movie query()
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereCoverDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereMovieId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereReleaseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereSynopsis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereUpdatedAt($value)
 * @property int $country_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Country $country
 * @method static \Illuminate\Database\Eloquent\Builder|Movie onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Movie withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Movie withoutTrashed()
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Genre> $genres
 * @property-read int|null $genres_count
 * @property int $classification_id
 * @property-read \App\Models\Classification $classification
 * @method static \Illuminate\Database\Eloquent\Builder|Movie whereClassificationId($value)
 * @mixin \Eloquent
 */
class Movie extends Model
{
    // El "trait" "HasFactory" solo es relevante si usamos factories para seedear la base de datos.
    // No lo estamos usando ahora, así que lo comento.
//    use HasFactory;
    // Agregamos el trait para usar SoftDeletes.
    use SoftDeletes;

    // Por defecto, Laravel asume que la tabla a la que este modelo referencia va a ser una que se llame
    // exactamente igual, pero en plural (del inglés) y snake_case en vez de PascalCase.
    // En este caso, estamos cumpliendo con eso, así que no necesitamos configurar la tabla.
    // Pero si no fuera el caso, y el nombre de la tabla fuera otro, entonces podemos usar la propiedad
    // $table para indicarla.
    protected $table = 'movies';

    // Por defecto, Laravel asume que la PK se llama "id".
    // Si no es el caso, entonces debemos aclararlo usando la propiedad $primaryKey.
    protected $primaryKey = 'movie_id';

    // Listamos qué campos permitimos que se usen para un create/update con asignación masiva.
    protected $fillable = ['country_id', 'classification_id', 'title', 'price', 'release_date', 'synopsis', 'cover', 'cover_description'];

    public static function validationRules(): array
    {
        return [
            'title' => 'required|min:2',
            'price' => 'required|numeric',
            'release_date' => 'required',
            'synopsis' => 'required',
            'country_id' => 'required|numeric|exists:countries',
        ];
    }

    public static function validationMessages(): array
    {
        return [
            'title.required' => 'Tenés que escribir el título.',
            'title.min' => 'El título tiene que tener al menos 2 caracteres.',
            'price.required' => 'Tenés que escribir el precio.',
            'price.numeric' => 'El precio tiene que ser un número.',
            'release_date.required' => 'Tenés que escribir la fecha de estreno.',
            'synopsis.required' => 'Tenés que escribir la sinopsis.',
            'country_id.required' => 'Tenés que elegir el país de origen.',
            'country_id.numeric' => 'El valor seleccionado para el país de origen no es correcto. Por favor, elegí uno de la lista.',
            'country_id.exists' => 'El valor seleccionado para el país de origen no es correcto. Por favor, elegí uno de la lista.',
        ];
    }

    public function getGenreIds(): array
    {
        // Para obtener los ids de los géneros, necesitamos consultar la tabla pivot de la relación,
        // obtener los ids, y retornar un array con solo esa data.
        // Con el método genres() de la relación podemos acceder a los géneros asociados, pero son los
        // modelos, no solo los ids.
        // Por lo que vamos a necesitar procesar un poco los datos para convertirlos.
        // Los métodos de las collections de Laravel nos van a ser muy útiles para esto.
        return $this->genres->pluck('genre_id')->all();
    }

    /*
     |--------------------------------------------------------------------------
     | Accessors & Mutators
     |--------------------------------------------------------------------------
     | Los Accessors nos permiten modificar los valores que leemos de la base de
     | en nuestro modelo de Eloquent.
     | Creamos una función que llame igual que la propiedad, pero en camelCase, y
     | hacemos que retorne un [Attribute::make()].
     | Dentro de la clase Attribute() tenemos el método make() que recibe hasta
     | 2 parámetros:
     | - get: callback a ejecutar cuando leamos este valor desde Eloquent.
     | - set: callback a ejecutar cuando escribamos algún valor a este modelo.
     */
    protected function price(): Attribute
    {
//        return Attribute::make(
//            // El callback recibe com argumento el valor actual de la propiedad.
//            // Debe retornar el nuevo valor.
//            function(float $price) {
//                return $price / 100;
//            },
//            function(float $price) {
//                return $price * 100;
//            }
//        );
        return Attribute::make(
            // Podemos usar arrow functions para transformar la propiedad.
            // Las arrow function de php son similares a las JS en cuanto a su sintaxis, pero con algunas
            // importantes diferencias:
            // - La sintaxis es:
            //  fn (props) => return
            // - No pueden llevar un cuerpo de función. Solo pueden tener una expresión de retorno.
            // - Como no definen un cuerpo de función, tampoco definen un ámbito ("scope") de variables.
            //  Esto significa que en las arrow functions podemos referir a variables que estén definidas
            // en la función que las contiene.
            fn (float $price) => $price / 100,
            fn (float $price) => $price * 100
        );
    }

    /*
     |--------------------------------------------------------------------------
     | Relaciones
     |--------------------------------------------------------------------------
     | En Eloquent, definimos cada relación como un método.
     | El nombre del método va a usarse para crear una propiedad que nos permita
     | acceder al modelo relacionado.
     |
     | Por ejemplo, si el método es:
     |  public function country()
     |  { ... }
     |
     | Lo usaremos de la siguiente forma:
     |  $movie->country->propiedadDelModelo
     |
     | Estos métodos deben retornar la relación, incluyendo el tipo y con qué
     | modelo de Eloquent se realiza.
     */
    public function country(): BelongsTo
    {
        // Las relaciones de 1:n se definen con uno de dos métodos, dependiendo de qué lado de la relación
        // esté:
        // - hasMany
        //  Se utiliza en el modelo de la tabla referenciada (la del lado de "1").
        // - belongsTo
        //  Se utiliza en el modelo de la tabla referenciante (la del lado de "n").
        // En este caso necesitamos belongsTo.
        // Este método debe retornar el tipo de la relación.
        // belongsTo recibe algunos parámetros:
        // 1. String. El FQN del modelo de Eloquent con el que se relaciona.
        // 2. Opcional. String|null. El nombre del campo de la FK.
        // 3. Opcional. String|null. El nombre del campo de la PK referenciada.
        return $this->belongsTo(Country::class, 'country_id', 'country_id');
    }

    public function classification(): BelongsTo
    {
        return $this->belongsTo(Classification::class, 'classification_id', 'classification_id');
    }

    public function genres(): BelongsToMany
    {
        // Las relaciones de n:m se definen con el método belongsToMany.
        // Este método recibe varios parámetros:
        // 1. String. El FQN del modelo de Eloquent con el que se relaciona.
        // 2. Opcional. String|null. El nombre de la tabla pivot.
        // 3. Opcional. String|null. El nombre de la FK para esta tabla en la tabla pivot.
        // 4. Opcional. String|null. El nombre de la FK para la *otra* tabla en la tabla pivot.
        // 5. Opcional. String|null. El nombre de la PK de esta tabla.
        // 6. Opcional. String|null. El nombre de la PK de la *otra* tabla.
        return $this->belongsToMany(
            Genre::class,
            'movies_has_genres',
            'movie_id', // o 'movie_fk' o similar si la FK tuviera otro nombre.
            'genre_id', // o 'genre_fk' o similar si la FK tuviera otro nombre.
            'movie_id',
            'genre_id',
        );
    }
}
