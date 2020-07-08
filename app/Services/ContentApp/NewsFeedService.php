<?php  

namespace App\Services\ContentApp;

use App\Repositories\ContentApp\NewsFeedRepository;
use Illuminate\attributesbase\Eloquent\Model;

class NewsFeedService
{
    protected $repository;

    public function __construct(NewsFeedRepository $repository){
        $this->repository = $repository;
    }

    public function all(){
        return $this->repository->all();
    }

    public function store(array $attributes){
        return $this->repository->store($attributes);
    }

    public function update(array $attributes, $id){
        return $this->repository->update($attributes, $id);
    }

    public function delete($id){
        return $this->repository->delete($id);
    }

    public function show($id){
        return $this->repository->show($id);
    }

    public function getModel(){
        return $this->repository;
    }

    public function fillable(){
        return $this->repository->fillable();
    }
}