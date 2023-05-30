<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
* Interface BaseRepositoryInterface
* @package App\Interfaces
*/
interface BaseRepositoryInterface
{
    /**
    * @param array $collection
    * @return Collection
    */
   public function get();
   /**
    * @param $id
    * @return Model
    */
   public function find($id);
}