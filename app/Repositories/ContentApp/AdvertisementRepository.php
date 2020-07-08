<?php 

namespace App\Repositories\ContentApp;

use App\Models\ContentApp\Advertisement;
use App\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class AdvertisementRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct(Advertisement $model){
        $this->model = $model;
    }

    public function all(){
        return $this->model->get();
    }

    public function store(array $attributes){
        $attributes['token'] = 'ad-'.Str::uuid();
        return $this->model->create($attributes);
    }

    public function update(array $attributes, $token){
        $record = $this->model->where('token', $token)->first();
        return $record->update($attributes);
    }
 
    public function delete($id){
        $row = $this->model->where('token', $id)->first();
        return $row->delete();
    }

    public function show($token){
        return $this->model->where('token',$token)->first();
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