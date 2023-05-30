<?php

namespace App\Repositories;

use App\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class BaseRepository implements BaseRepositoryInterface
{
    /**      
     * @var Model      
     */
    protected $model;

    /**      
     * BaseRepository constructor.      
     *      
     * @param Model $model      
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     *
     * @return Collection
     */
    public function get()
    {
        return $this->model->all();
    }

    public function getModel()
    {
        return $this->model;
    }

    public function paginate($page)
    {
        return $this->model->paginate($page);
    }

    /**
     * @param array $attributes
     *
     * @return model
     */

    public function store($input)
    {
        return $this->model->create($input);
    }

    public function update($id, $input)
    {
        return $this->model->find($id)->update($input);
    }

    /**
     * @param $id
     * @return Model
     */
    public function find($id): ?Model
    {
        return $this->model->find($id);
    }

    public function where($column,  $operator, $value)
    {
        return $this->model->where($column,  $operator, $value);
    }

    public function whereMultiple($cinditions)
    {
        return $this->model->where([$cinditions]);
    }

    public function destroy($id)
    {
        return $this->model->find($id)->delete();
    }
}
