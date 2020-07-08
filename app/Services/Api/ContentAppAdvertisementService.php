<?php  

namespace App\Services\Api;

use Illuminate\attributesbase\Eloquent\Model;
use App\Models\ContentApp\Advertisement;
use App\Http\Resources\ContentApp\AdvertisementCollection;

class ContentAppAdvertisementService
{
   	public function __construct(Advertisement $model){
        $this->model = $model;
    }

    public function getAll(){
    	return new AdvertisementCollection($this->model->get());
    }
}