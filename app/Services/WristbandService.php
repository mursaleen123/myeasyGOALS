<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Interfaces\WristbandRepositoryInterface;
use App\Services\BaseService;

class WristbandService extends BaseService
{

    public function __construct(WristbandRepositoryInterface $wristbandRepositoryInterface)
    {
        parent::__construct($wristbandRepositoryInterface);
    }
}
