<?php

namespace App\Http\Controllers\ContentApp;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ContentApp\MultimediaService;
use App\Http\Requests\ContentApp\Multimedia\PostMultimediaRequest;

class MultimediaController extends Controller
{
  protected $service;
  protected $view_path = 'content-app.multimedia';
  protected  $base_route = 'multimedia.index';

  public function __construct(MultimediaService $service){
    $this->service = $service;
  }

  public function index(){
    $rows = $this->service->all();
    return view($this->view_path.'.index', compact('rows'));
  }

  public function create()
  {
    return view($this->view_path.'.create');
  }

  public function store(PostMultimediaRequest $request){
    $this->service->store($request->only($this->service->fillable()));
    $request->session()->flash('message', 'Multimedia created successfully');
    return redirect()->route($this->base_route);
  }

  public function show($id){
    $row = $this->service->show($id);
    return view($this->view_path.'.edit', compact('row'));
  }

  public function update(Request $request, $id){
    $this->service->update($request->only($this->service->fillable()), $id);
    $request->session()->flash('message', 'Multimedia updated successfully');
    return redirect()->route($this->base_route);
  }

  public function destroy(Request $request, $id){
    $this->service->delete($id);
    $request->session()->flash('message', 'Multimedia deleted');
    return redirect()->route($this->base_route);
  }
}