<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\Asset;
use App\Reports\FilterRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Helpers\GetHealthpostCodes;

class StockController extends Controller
{
  public function listStock(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $user = auth()->user();
        $stocks = Stock::whereIn('hp_code', $hpCodes)
           ->withAll()
           ->join('assets', 'assets.id', '=', 'stocks.asset_id')
          ->get(['stocks.*', 'assets.name'])
          ->toArray();
        $stockIds = [];
        $assetIds = [];
        foreach($stocks as $stock) {
          array_push($stockIds, $stock['id']);
        }
        $assets = Asset::all()->sortByDesc('created_at')->map(function ($data){
            $data['data'] = json_decode($data->data, true);
            array_push($assetIds, $data->id);
            return $data;
        });
        if(count($stocks)!== count($assets)) {
          foreach($assetIds as $index=>$assetId){
            if(!in_array($assetId, $stockIds)) {
              array_push($stocks, [
                'current_stock' => 0, 
                'asset_id' => $assetId,
                'hp_code' => $hpCodes[0],
                'name' => $assets[$index]['name']
              ]);
            }
          }
        }
        return view('backend.stock.list', [
            'stocks' => $stocks,
            'hpCode' => $hpCodes[0] 
        ]);
    }
  
    public function updateStock(Request $request)
    {
      $stock_id = $request['stock_id'];
      echo($stock_id);
      // hp_code: "{{hpCode}}",
      // new_stock: $("input[name='new_stock"+iteration+"']").value,
      // remove_stock: $("input[name='remove_stock"+iteration+"']").value, dd($request['new_stock']);
      
    }
    public function stockTransactionList(Request $request)
    {
    //     $response = FilterRequest::filter($request);
    //     $hpCodes = GetHealthpostCodes::filter($response);
    //     $user = auth()->user();
    //     $stockList = Stock::whereIn('hp_code', $hpCodes)->active()
    //        ->withAll();
    //     // return response()->json([
    //     //     'collection' => $data->advancedFilter()
    //     // ]);
    //     dd($stocks);
        return view('backend.stock.transaction', [
            // 'stockList' => $stockList
        ]);
    }
}