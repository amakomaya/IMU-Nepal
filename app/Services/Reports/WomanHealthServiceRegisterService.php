<?php


namespace App\Services\Reports;
use App\Repositories\Reports\WomanHealthServiceRegisterRepository;


class WomanHealthServiceRegisterService
{
    protected $repository;

    public function __construct(WomanHealthServiceRegisterRepository $repository){
        $this->repository = $repository;
    }

    public function all($response){
        return $this->repository->all($response);
    }
}