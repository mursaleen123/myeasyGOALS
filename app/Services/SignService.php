<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Interfaces\SignRepositoryInterface;
use App\Services\BaseService;

class SignService extends BaseService
{

    public function __construct(SignRepositoryInterface $signRepositoryInterface)
    {
        parent::__construct($signRepositoryInterface);
    }
}
