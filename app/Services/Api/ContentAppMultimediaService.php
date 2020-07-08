<?php  

namespace App\Services\Api;

use App\Repositories\ContentApp\MultimediaRepository;
use Illuminate\attributesbase\Eloquent\Model;
use App\Models\ContentApp\Multimedia;
use App\Http\Resources\ContentApp\TextCollection;
use App\Http\Resources\ContentApp\VideoCollection;
use App\Http\Resources\ContentApp\AudioCollection;

class ContentAppMultimediaService
{
   	public function __construct(MultimediaRepository $repository, Multimedia $model){
        $this->repository = $repository;
        $this->model = $model;
    }

    public function getText(){
    	return new TextCollection($this->model->type(3)->latest()->get());
    }

    public function getVideo(){
    	return new VideoCollection($this->model->type(1)->latest()->get());
    }

    public function getAudio(){
    	return new AudioCollection($this->model->type(2)->latest()->get());
    }

}