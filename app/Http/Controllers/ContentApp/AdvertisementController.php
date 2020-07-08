<?php

namespace App\Http\Controllers\ContentApp;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ContentApp\AdvertisementService;
use App\Http\Requests\ContentApp\Advertisement\PostAdvertisementRequest;


class AdvertisementController extends Controller
{
  protected $service;
  protected $view_path = 'content-app.advertisement';
  protected  $base_route = 'advertisement.index';

  public function __construct(AdvertisementService $service){
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

  public function store(PostAdvertisementRequest $request){
    $this->service->store($request->only($this->service->fillable()));
    $request->session()->flash('message', 'Advertisement created successfully');
    return redirect()->route($this->base_route);
  }

  public function show($token){
    $row = $this->service->show($token);
    return view($this->view_path.'.edit', compact('row'));
  }

  public function update(Request $request, $token){
    $this->service->update($request->only($this->service->fillable()), $token);
    $request->session()->flash('message', 'Advertisement updated successfully');
    return redirect()->route($this->base_route);
  }

  public function destroy(Request $request, $token){
    $this->service->delete($token);
    $request->session()->flash('message', 'Advertisement deleted');
    return redirect()->route($this->base_route);
  }
}
