<div>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Título</th>
            <th>Fecha de Estreno</th>
            <th>Precio</th>
            <th>Clasificación</th>
            <th>País de Origen</th>
            <th>Géneros</th>
            <th>Sinopsis</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach($model as $movie)
            <tr>
                <td>{{ $movie->title }}</td>
                <td>{{ $movie->release_date }}</td>
                <td>${{ $movie->price }}</td>
                <td>{{ $movie->classification->abbreviation }}</td>
                <td>{{ $movie->country->alpha3 }}</td>
                <td>
                    @forelse($movie->genres as $genre)
                        <span class="badge bg-secondary">{{ $genre->name }}</span>
                    @empty
                        Sin géneros.
                    @endforelse
                </td>
                <td>{{ $movie->synopsis }}</td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('movies.view', ['id' => $movie->movie_id]) }}" class="btn btn-primary">Ver</a>
                        @auth
                            <form action="{{ route('movies.processReservation', ['id' => $movie->movie_id]) }}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-secondary">Reservar</button>
                            </form>
                            <a href="{{ route('movies.formUpdate', ['id' => $movie->movie_id]) }}" class="btn btn-primary">Editar</a>
                            <a href="{{ route('movies.confirmDelete', ['id' => $movie->movie_id]) }}" class="btn btn-danger">Eliminar</a>
                        @endauth
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{-- Paginación --}}
    {{ $model->links() }}
</div>
