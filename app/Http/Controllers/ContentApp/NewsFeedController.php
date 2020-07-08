<?php

namespace App\Http\Controllers\ContentApp;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ContentApp\NewsFeedService;
use App\Http\Requests\ContentApp\NewsFeed\PostNewsFeedRequest;

class NewsFeedController extends Controller
{
  protected $service;
  protected $view_path = 'content-app.news-feed';
  protected  $base_route = 'news-feed.index';

  public function __construct(NewsFeedService $service){
    $this->service = $service;
  }

  public function index(){
    $user = \Auth::user();
    if($user->role == 'healthpost'){
      $rows = $this->service->all()->where('token', $user->token);
    }
    if($user->role == 'municipality'){
      $municipality_id = \App\Models\MunicipalityInfo::where('token', $user->token)->first()->municipality_id;
      $healthpost_token_in_municipality = \App\Models\Healthpost::where('municipality_id', $municipality_id)->pluck('token');
      $tokens = $healthpost_token_in_municipality->merge($user->token);
      $rows = $this->service->all()->whereIn('token', $tokens);
    }
    if ($user->role == 'main') {
      $rows = $this->service->all();
    }
    
    return view($this->view_path.'.index', compact('rows'));
  }

  public function create()
  {
    return view($this->view_path.'.create');
  }

  public function store(PostNewsFeedRequest $request){
    $this->service->store($request->only($this->service->fillable()));
    $request->session()->flash('message', 'NewsFeed created successfully');
    return redirect()->route($this->base_route);
  }

  public function show($id){
    $row = $this->service->show($id);
    return view($this->view_path.'.edit', compact('row'));
  }

  public function update(Request $request, $id){
    // dd($request->all());
    $this->service->update($request->only($this->service->fillable()), $id);
    $request->session()->flash('message', 'NewsFeed updated successfully');
    return redirect()->route($this->base_route);
  }

  public function destroy(Request $request, $id){
    $this->service->delete($id);
    $request->session()->flash('message', 'NewsFeed deleted');
    return redirect()->route($this->base_route);
  }
}