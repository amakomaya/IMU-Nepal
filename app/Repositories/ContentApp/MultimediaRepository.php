<?php 

namespace App\Repositories\ContentApp;

use App\Models\ContentApp\Multimedia;
use App\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class MultimediaRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct(Multimedia $model){
        $this->model = $model;
    }

    public function all(){
        return $this->model->latest()->get();
    }

    public function store(array $attributes){
        return $this->model->create($attributes);
    }

    public function update(array $attributes, $id){
        $record = $this->model->find($id);
        return $record->update($attributes);
    }
 
    public function delete($id){
        $row = $this->model->findOrFail($id);
        return $row->delete();
    }

    public function show($id){
        return $this->model->findOrFail($id);
    }

    public function getModel(){
        return $this->model;
    }

    public function setModel($model){
        $this->model = $model;
        return $this;
    }

    public function with($relations){
        return $this->model->with($relations);
    }

    public function fillable(){
        return $this->model->getFillable();
    }
}