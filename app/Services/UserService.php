<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Interfaces\UserRepositoryInterface;
use App\Services\BaseService;

class UserService extends BaseService
{

    protected $userRepositoryInterface;

    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        parent::__construct($userRepositoryInterface);
    }
}
