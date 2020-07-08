<?php  

namespace App\Services\Api;

use App\Repositories\ContentApp\NewsFeedRepository;
use Illuminate\attributesbase\Eloquent\Model;
use App\Models\ContentApp\NewsFeed;
use App\Http\Resources\ContentApp\NewsFeedCollection;

/**
 * @property NewsFeedRepository repository
 * @property NewsFeed model
 */
class ContentAppNewsFeedService
{
   	public function __construct(NewsFeedRepository $repository, NewsFeed $model){
        $this->repository = $repository;
        $this->model = $model;
    }

    public function getNewsFeed($tokens = []){
        if (!empty($tokens)) {
            $data = $this->model->active()->whereIn('token', $tokens)->latest()->get();
        }else{
            $data = $this->model->active()->latest()->get();
        }
    	return new NewsFeedCollection($data);
    }
}