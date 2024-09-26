<?php

namespace App\Repositories\Interfaces;

interface MovieRepository
{
    public function all();

    public function get();

    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null);

    public function where($column, $operator = null, $value = null, $boolean = 'and'): self;

    public function withRelations(array $relations): self;

    public function findOrFail(int $id);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);
}
