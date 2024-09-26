<?php

namespace App\Repositories;

use App\Models\Movie;
use App\Repositories\Interfaces\MovieRepository;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class MovieEloquentRepository implements MovieRepository
{
    private Movie $movie;
    private Builder $builder;

    public function __construct()
    {
        $this->movie = new Movie;
        // Preparamos la instancia del query builder.
        $this->builder = $this->movie->query();
    }

    public function all()
    {
        return Movie::all();
    }

    public function get()
    {
        return $this->builder->get();
    }

    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        return $this->builder->paginate($perPage, $columns, $pageName, $page);
    }

    public function where($column, $operator = null, $value = null, $boolean = 'and'): self
    {
        $this->builder->where($column, $operator, $value, $boolean);
        return $this;
    }

    public function withRelations(array $relations): self
    {
        $this->builder->with($relations);
        return $this;
    }

    public function findOrFail(int $id)
    {
        return Movie::findOrFail($id);
    }

    public function create(array $data)
    {
        DB::transaction(function() use ($data) {
            $movie = Movie::create($data);
            $movie->genres()->attach($data['genre_id'] ?? []);
        });
    }

    public function update(int $id, array $data)
    {
        return Movie::findOrFail($id)->update($data);
    }

    public function delete(int $id)
    {
        return Movie::findOrFail($id)->delete();
    }
}
