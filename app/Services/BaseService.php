<?php

namespace App\Services;
use Illuminate\Http\Request;

class BaseService
{
    protected $repository;
 
    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function store(Request $request)
    {
        $input = $request->all();
        return $this->repository->store($input);
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        return $this->repository->update($id, $input);
    }

    public function get()
    {
        return $this->repository->get();
    }

    public function getModel()
    {
        return $this->repository->getModel();
    }

    public function paginate($page = 10)
    {
        return $this->repository->paginate($page);
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }

    public function where($column, $operator, $value)
    {
        return $this->repository->where($column, $operator, $value);
    }

    public function whereMultiple($cinditions)
    {
        return $this->repository->whereMultiple([$cinditions]);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }

    public function getLawyer()
    {
        return auth()->guard('lawyer')->user() ? auth()->guard('lawyer')->user() : null;        
    }

    public function checkAuthLawyer()
    {
        return $this->getLawyer() ? true : false;
    }
    
}
