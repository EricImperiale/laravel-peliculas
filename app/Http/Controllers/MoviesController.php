<?php

namespace App\Http\Controllers;

use App\Models\Classification;
use App\Models\Country;
use App\Models\Genre;
use App\Models\Movie;
use App\Repositories\Interfaces\MovieRepository;
use App\Repositories\MovieEloquentRepository;
use App\Searches\MovieSearchParams;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class MoviesController extends Controller
{
    protected MovieRepository $repo;

    protected array $rules = [
        'title' => 'required|min:2',
        'price' => 'required|numeric',
        'release_date' => 'required',
    ];

    protected array $messages = [
        'title.required' => 'Tenés que escribir el título',
        'title.min' => 'El título tiene que tener al menos 2 caracteres',
        'price.required' => 'Tenés que escribir el precio',
        'price.numeric' => 'El precio tiene que ser un número',
        'release_date.required' => 'Tenés que escribir la fecha de estreno',
    ];

    /*
     * Como los controllers de Laravel cuentan con la funcionalidad de inyección de dependencias del
     * framework, podemos pedirle que automáticamente nos pase una instancia de la clase
     * MovieRepository.
     *
     * Para que Laravel sepa qué clase concreta queremos cuando pidamos la interfaz MovieRepository,
     * lo "bindeamos" (vinculamos) en alguno de los Service Providers, como AppServiceProvider.
     */
    public function __construct(MovieRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index(Request $request)
    {
        // Obtenemos una colección ("Collection") de todas las películas que tenemos en la base, con ayuda
        // de nuestro modelo de Eloquent.
        // Esto lo hacemos usando el método all().
//        $movies = Movie::all();
        // Update 03/05/2023
        // Ahora que agregamos las relaciones, el método all() no es el adecuado.
        // En general, de hecho, all() no es el mejor método para un listado, ya que trae _todos_ los
        // registros. Si tenemos 100000 películas, traería las 100000.
        // En este caso, queremos traer las películas, pero pidiendo que se incluyan las relaciones con
        // otras tablas (ej: countries). Esto nos va a permitir evitar el problema de consultas "N + 1"
        // que Laravel detalla en su documentación:
        // https://laravel.com/docs/10.x/eloquent-relationships#eager-loading
        //
        // Usamos el método with() para decirle qué relaciones queremos que cargue, y luego invocamos el
        // query con el método get().
//        $movies = Movie::with(['country', 'classification', 'genres'])->get();
        // Update 24/05/2023
        // Cambiamos ahora a leer con el repository.
        $builder = $this->repo->withRelations(['country', 'classification', 'genres']);

        // # Buscador
        // Para buscar, la idea va a ser similar a la que usamos en Programación 2: ir agregando las
        // condiciones de búsqueda al query según los datos que nos hayan pedido.
        // Las diferencias van a ser:
        // - En vez de pedir los datos a $_GET, vamos a pedírselos a la clase Request. Las razones son las
        //  mismas que hablamos cuando pedimos a Request los datos del form en vez de a $_POST.
        // - En vez de armar de a pedazos manualmente el query de SQL que vamos a ejecutar, lo vamos a
        //  ir pidiendo a la clase Builder de Laravel. Por esto es que necesitamos tener separado lo que
        //  es el inicio del query (como el llamado al with()) de la ejecución (como el get()).
//        $searchParams = new MovieSearchParams([
//            // Con el método query() de Request podemos pedir datos del query string.
//            'title' => $request->query('t'),
//        ]);
        $searchParams = new MovieSearchParams(
            title: $request->query('t'),
        );

        if($searchParams->getTitle()) {
            $builder->where('title', 'LIKE', '%' . $searchParams->getTitle() . '%');
        }

//        $movies = $builder->get();
        // # Paginación
        // Para traer los datos paginados desde Laravel, solo tenemos que reemplazar la llamada al método
        // get() con el método paginate().
        // Para poder mostrarlo en funcionamiento, vamos a poner 2 registros por página.
        $movies = $builder->paginate(2);

        // Ahora queremos poder darle a la vista el contenido de la variable $movies para que pueda
        // mostrarlos en pantalla.
        // Los datos que queremos entregarle a una vista se los podemos brindar a través del segundo
        // parámetro de la función view().
        // Este parámetro recibe un array asociativo con los valores, donde las claves van a ser los
        // nombres de las variables que queremos crear en la vista.
        // Para referenciar a una vista en una subcarpeta, podemos separar con "/" o con ".".
//        return view('movies/index');
        return view('movies.index', [
            'movies' => $movies,
            'searchParams' => $searchParams,
        ]);
    }

    // Este método está asociado a una ruta que tiene un segmento dinámico llamado {id}.
    // Para obtener el valor de ese segmento dinámico, podemos hacerlo pidiéndolo con un argumento del
    // método que se llame igual que el segmento. En este caso, sería `$id`.
    public function view(int $id)
    {
        // Usando el id que nos pidieron, vamos a buscar la película para listarla en pantalla.
        // El método find() busca un registro por su PK. Si no encuentra el registro, retorna null.
//        $movie = Movie::find($id);

        // Para casos donde si el registro no existe, implicaría que la página que no existe, Laravel
        // tiene un método findOrFail(), que hace que se retorne 404.
//        $movie = Movie::findOrFail($id);
        $movie = $this->repo->findOrFail($id);

        return view('movies.view', [
            'movie' => $movie,
        ]);
    }

    public function formNew()
    {
        return view('movies.create-form', [
            'countries' => Country::orderBy('name')->get(),
            'classifications' => Classification::orderBy('name')->get(),
            'genres' => Genre::orderBy('name')->get(),
        ]);
    }

    // # Recibiendo datos de la petición con Laravel
    // Si bien cuando recibimos datos de la petición, como por ejemplo un formulario, podemos usar las
    // formas habituales como $_POST para hacerlo (esto es php, después de todo), no es la manera
    // recomendada de hacerlo.
    // En su lugar, se recomienda utilizar la capa de abstracción que Laravel provee: la clase Request.
    // Para obtener la instancia de Request, y poder sacar la información que buscamos, solo tenemos que
    // "inyectarla" en el método del controller.
    public function processNew(Request $request)
    {
        $this->authorize('create', Movie::class);
        // dd => dump and die
//        dd($request);

        // Desde la clase $request, podemos pedir que se nos den los datos del formulario de varias
        // maneras. Una es pedir que nos dé todos los valores con el método all().
//        $data = $request->all();

        // Podemos también pedir todos los datos, salvo algunos, como por ejemplo el '_token'.
        $data = $request->except(['_token']);

        // O también podemos pedir la lista exacta de los campos que queremos traer.
//        $data = $request->only(['title', 'release_date', 'price', 'synopsis', 'cover', 'cover_description']);

        // ## Validación
        // Laravel hace que la validación de formularios sea muchísimo más sencilla que con php nativo.
        // De hecho, tiene una amplia documentación al respecto: https://laravel.com/docs/10.x/validation
        // Si bien hay varias maneras de ejecutar una validación, la forma más fácil es llamando al método
        // "validate" de la clase Request.
        // Este método recibe 1 parámetro obligatorio, y 1 opcional:
        // 1. Array. Las reglas de validación.
        // 2. Array. Los mensajes de error para cada regla.
        // El array debe como clave, tener los nombres de los campos, y como valor, las reglas que queremos
        // aplicarle. Una regla es una validación que quiero que el campo deba cumplir. Por ejemplo,
        // 'required'.
        // La lista de reglas puede indicarse como un array, o como un string que separe las reglas
        // usando "|".
        // Hay casos de reglas que pueden necesitar "parámetros", como es el caso de "min". Para darles
        // el valor del parámetro, agregamos un ":".
        // Si la validación tiene éxito, el método validate retorna un array con los campos que pasaron
        // la validación.
        // Si la validación falla, y la petición no es por Ajax, sino que es una petición común, entonces
        // automáticamente va a:
        // - Flashear en la sesión los mensajes de error (que podemos obtener con la variable $errors).
        // - Flashear en la sesión los datos actuales del form (que podemos obtener con el método old()).
        // - Redireccionar al usuario a la pantalla de la que vino.
        // Si la validación falla, y la petición es por Ajax, entonces:
        // - Imprime un JSON con todos los mensajes de error.
        // - Termina la ejecución.
//        $request->validate([
//            'title' => 'required|min:2',
//            'price' => 'required|numeric',
//            'release_date' => 'required',
//        ], [
//            'title.required' => 'Tenés que escribir el título',
//            'title.min' => 'El título tiene que tener al menos 2 caracteres',
//            'price.required' => 'Tenés que escribir el precio',
//            'price.numeric' => 'El precio tiene que ser un número',
//            'release_date.required' => 'Tenés que escribir la fecha de estreno',
//        ]);
//        $request->validate($this->rules, $this->messages);
        $request->validate(Movie::validationRules(), Movie::validationMessages());

        /*
         |--------------------------------------------------------------------------
         | Upload de archivos
         |--------------------------------------------------------------------------
         | Vamos a probar subir el archivo de las dos formas que podemos hacerlo con
         | Laravel: manejando "manualmente" el archivo, y con la API File System.
         | Empecemos con la forma manual.
         | Esto es parecido a lo que hacíamos con php nativo. Es decir, mover el
         | archivo de su ubicación temporal a su ubicación permanente.
         | Primero, preguntamos si el archivo existe con hasFile(), y luego obtenemos
         | el archivo con file().
         */
        if($request->hasFile('cover')) {
            $data['cover'] = $this->uploadCover($request);
        }

        // Pedimos que se grabe la película al modelo de Eloquent.
        // Como vamos a necesitar interactuar con la película que creamos (para insertar los géneros),
        // capturamos el modelo creado.
        // Una vez grabada la película, tenemos que agregar los géneros.
        // Esto implica crear un registro en la tabla pivot por cada género.
        // En Eloquent, tenemos unos muy útiles métodos para manejar los datos de la tabla pivot.
        // https://laravel.com/docs/10.x/eloquent-relationships#updating-many-to-many-relationships
        // En este caso, vamos a usar el método attach().
        // Este método se encadena a una llamada del método de la relación (*debe* ser el método, no la
        // propiedad dinámica).
        //  $movie->genres->attach(); // Esto está mal, faltan los paréntesis de la relación.
        //  $movie->genres()->attach(); // Esto está bien :D
        // attach() recibe como parámetro el id o lista de ids que queremos insertar en la tabla pivot
        // asociados a esta película.

        // Como estamos ejecutando dos o más consultas que modifiquen datos en las tablas, deberíamos
        // envolverlas en una transacción de SQL.
        // Tenemos 2 maneras de ejecutar transacciones con Laravel.
        // 1. Ejecutando manualmente las llamadas a los métodos correspondientes en la clase de conexión.
        // 2. Usando un método que ejecute la transacción en un callback.

        // Versión 1: Ejecutando los métodos manualmente.
        // Empezamos por iniciar la transacción a partir de la clase DB (la misma fachada que usamos para
        // insertar registros en los Seeders).
        // Como si algo falla Laravel va a lanzar una Exception, para manejar la transacción deberíamos
        // englobar el código en un try/catch.
//        try {
//            // Iniciamos la transacción.
//            DB::beginTransaction();
//            $movie = Movie::create($data);
//
//            $movie->genres()->attach($data['genre_id']);
////            $movie->genres()->attach(['asd', 1, 4]);
//
//            // La data se grabó correctamente, así que podemos confirmar los cambios.
//            DB::commit();
//        } catch(\Throwable $e) {
//            // Ocurrió algún error, así que cancelamos cualquier cambio realizado y redireccionamos al
//            // usuario al form con un mensaje informando ocurrido y los datos del form.
//            DB::rollBack();
//
//            return redirect()
//                ->route('movies.formNew')
//                ->withInput()
//                ->with('status.message', 'Ocurrió un error al grabar la información. Por favor, probá de nuevo en un rato. Si el problema persiste, comunicate con nosotros.')
//                ->with('status.type', 'error');
//        }

        // Versión 2: Usando el callback.
        // La fachada DB de Laravel nos ofrece un método DB::transaction que recibe un closure/callback.
        // Esta función se ejecuta dentro de una transacción. Si no ocurre ningún error, se confirma
        // automáticamente.
        // Si algo sale mal, se hace el rollback automáticamente.
        // Es importante notar que como estamos creando un closure, es decir, una nueva función, esta
        // sujeta a todas las reglas de ámbitos de funciones de php.
        // Es decir, las variables que definimos dentro de la función solo van a existir en la función,
        // y asimismo, las variables que existan fuera de la función *no* van a existir en la función.
        // $data, por ejemplo, está fuera de la función, y por lo tanto, no tenemos acceso a ese valor.
        // Claramente, esto es un problema. Sin $data no podemos grabar la película.
        // Para sortear este inconveniente, podemos ayudarnos con la keyword "use()".
        // Las funciones anónimas o "closures" permiten que nosotros le pasemos valores del ámbito que
        // las contiene para que puedan usarlas. Esto es lo que la keyword "use()" nos permite hacer.
        try {
//            DB::transaction(function() use ($data) {
//                $movie = Movie::create($data);
//
//                $movie->genres()->attach($data['genre_id'] ?? []);
////                $movie->genres()->attach(['asd', 1, 4]);
//            });
            $this->repo->create($data);
        } catch(\Exception $e) {
            Debugbar::log($e);
            return redirect()
                ->route('movies.formNew')
                ->withInput()
                ->with('status.message', 'Ocurrió un error al grabar la información. Por favor, probá de nuevo en un rato. Si el problema persiste, comunicate con nosotros.')
                ->with('status.type', 'danger');
        }

        // Redireccionamos al listado.
        // Para enviar mensajes de feedback, que queremos que solo se muestren una vez, podemos usar una
        // variable "flash" de sesión (o lo que llamamos "flashear" una variable).
        // En Laravel, tenemos métodos para crear variables de sesión que se eliminen automáticamente
        // luego de un renderizado.
        // Por ejemplo, cuando hacemos un redireccionamiento, podemos encadenar el método with() para
        // este fin.
        return redirect()
            ->route('movies.index')
            // Escapamos el título de la película para evitar inyección de HTML, ya que el mensaje se
            // renderiza con los tags de Blade {!! !!}
            ->with('status.message', 'La película <b>' . e($data['title']) . '</b> fue publicada con éxito.')
            ->with('status.type', 'success');
    }

    public function formUpdate(int $id)
    {
        return view('movies.update-form', [
            'movie' => Movie::findOrFail($id),
            'countries' => Country::orderBy('name')->get(),
            'classifications' => Classification::orderBy('name')->get(),
            'genres' => Genre::orderBy('name')->get(),
        ]);
    }

    public function processUpdate(int $id, Request $request)
    {
        $movie = Movie::findOrFail($id);

        $this->authorize('update', $movie);

        $request->validate(Movie::validationRules(), Movie::validationMessages());

        $data = $request->except(['_token']);

        if($request->hasFile('cover')) {
            $data['cover'] = $this->uploadCover($request);

            // Guardamos el nombre de la portada anterior (si existe) para poder eliminarla luego.
            $oldCover = $movie->cover;
        }

        // Para actualizar los datos de una relación n:m, podemos usar el método sync() de la relación.
        // sync recibe un id o lista de ids que son los que queramos que queden como registros
        // relacionados.
        // Cualquier id que no esté en esa lista, es removido de la tabla pivot.
        try {
            DB::transaction(function() use ($movie, $data) {
                $movie->update($data);

                $movie->genres()->sync($data['genre_id'] ?? []);
            });
        } catch(\Exception $e) {
            return redirect()
                ->route('movies.formUpdate', ['id' => $movie->movie_id])
                ->withInput()
                ->with('status.message', 'Ocurrió un error al actualizar la información. Por favor, probá de nuevo en un rato. Si el problema persiste, comunicate con nosotros.')
                ->with('status.type', 'error');
        }

        // Si no hubo inconvenientes, entonces eliminamos la portada antigua.
        $this->deleteCover($oldCover ?? null);

        return redirect()
            ->route('movies.index')
            ->with('status.message', 'La película <b>' . e($movie->title) . '</b> fue editada con éxito.')
            ->with('status.type', 'success');
    }

    public function confirmDelete(int $id)
    {
        return view('movies.confirm-delete', [
            'movie' => Movie::findOrFail($id),
        ]);
    }

    public function processDelete(int $id)
    {
        $movie = Movie::findOrFail($id);

        $this->authorize('delete', $movie);

        $movie->delete();

        $this->deleteCover($movie->cover);

        return redirect()
            ->route('movies.index')
            ->with('status.message', 'La película <b>' . e($movie->title) . '</b> fue eliminada con éxito.')
            ->with('status.type', 'success');
    }

    /**
     * Sube la portada (cover), y retorna el nombre generado para el archivo.
     *
     * @param Request $request
     * @return string
     */
    protected function uploadCover(Request $request): string
    {
        // file() retorna una instancia de la clase UploadedFile
        $cover = $request->file('cover');

        // Creamos el nombre del archivo.
        // Por ejemplo, podemos usar la fecha actual, más un "slug" del título, más la extensión
        // del archivo.
        // De paso, ya podemos agregar el nombre a la info que $data debe grabar.
        // Str es la clase de método "helpers" de Laravel para Strings.
        // guessExtension() trata de deducir cuál es la extensión correcta del archivo según el
        // tipo MIME del mismo.
        $coverName = date('YmdHis-') . \Str::slug($request->input('title')) . "." . $cover->guessExtension();

        // Movemos el archivo con la función move de UploadedFile.
//        $cover->move(public_path('imgs'), $coverName);
        // Usando la API de Storage.
        $cover->storeAs('imgs', $coverName);

        return $coverName;
    }

    /**
     * Elimina la imagen de portada (cover) de la película, si existe.
     *
     * @param string|null $cover
     * @return void
     */
    protected function deleteCover(?string $cover): void
    {
        // Versión local.
//        if($movie->cover !== null && file_exists(public_path('imgs/' . $movie->cover))) {
//            unlink(public_path('imgs/' . $movie->cover));
//        }
        // Versión API Storage.
        if($cover !== null && Storage::has('imgs/' . $cover)) {
            Storage::delete('imgs/' . $cover);
        }
    }
}
