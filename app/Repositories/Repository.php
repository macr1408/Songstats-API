<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class Repository implements RepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model::all();
    }

    public function create(array $data = []): Model
    {
        return $this->model->create($data);
    }

    public function update(array $data, $id): bool
    {
        return $this->model->where('id', $id)
            ->update($data);
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    public function get($value, $key = 'id', $condition = '=')
    {
        return $this->model->where($key, $condition, $value)->first();
    }
}
