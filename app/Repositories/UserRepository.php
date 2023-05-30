<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;
use App\Interfaces\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{

   /**
    * ClaimRepository constructor.
    *
    * @param Claim $model
    */
   public function __construct(User $model)
   {
      parent::__construct($model);
   }

   public function store($input)
   {
      return $this->model->create($input);
   }
}
