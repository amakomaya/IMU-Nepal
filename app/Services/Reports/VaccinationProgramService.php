<?php


namespace App\Services\Reports;
use App\Repositories\Reports\VaccinationProgramRepository;


class VaccinationProgramService
{
    protected $repository;

    public function __construct(VaccinationProgramRepository $repository){
        $this->repository = $repository;
    }

    public function all($response){
        return $this->repository->all($response);
    }
}