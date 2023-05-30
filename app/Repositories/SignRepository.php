<?php

namespace App\Repositories;

use App\Models\Sign;
use App\Repositories\BaseRepository;
use App\Interfaces\SignRepositoryInterface;

class SignRepository extends BaseRepository implements SignRepositoryInterface
{

   /**
    * ClaimRepository constructor.
    *
    * @param Claim $model
    */
   public function __construct(Sign $model)
   {
      parent::__construct($model);
   }

   public function store($input)
   {
      return $this->model->create($input);
   }
}
