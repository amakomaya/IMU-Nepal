<?php  

namespace App\Services\Reports;

use App\Repositories\Reports\DashboardRepository;
use Illuminate\attributesbase\Eloquent\Model;

class DashboardService
{
    protected $repository;

    public function __construct(DashboardRepository $repository){
        $this->repository = $repository;
    }

    public function all($response){
        return $this->repository->all($response);
    }
}