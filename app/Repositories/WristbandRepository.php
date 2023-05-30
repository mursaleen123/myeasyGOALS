<?php

namespace App\Repositories;

use App\Models\Wristband;
use App\Repositories\BaseRepository;
use App\Interfaces\WristbandRepositoryInterface;

class WristbandRepository extends BaseRepository implements WristbandRepositoryInterface
{

   /**
    * ClaimRepository constructor.
    *
    * @param Claim $model
    */
   public function __construct(Wristband $model)
   {
      parent::__construct($model);
   }

   public function store($input)
   {
      return $this->model->create($input);
   }
}
