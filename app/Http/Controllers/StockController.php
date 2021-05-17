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
        $assets = Asset::latest()->get();
        foreach($assets as $asset) {
            array_push($assetIds, $asset->id);
        };
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
            'stocks' => $stocks
        ]);
    }
  
    public function updateStock(Request $request)
    {
      $asset_id = $request->asset_id;
      $stock_id = $request->stock_id;
      $remove_stock = (int)$request->stock_id;
      $new_stock = (int)$request->new_stock;
      $hp_code = $request->hp_code;
        return response()->json([
            'message' => 'Successfully updated stock'
        ]);
      if(!$stock_id){
        $current_stock = 0 + $new_stock - $remove_stock;
        \DB::insert('insert into stocks (hp_code, asset_id, current_stock) values (?, ?, ?)', [$hp_code, $asset_id, $current_stock ]);
      } else {
        $stock = \DB::table('stocks')->where('id', $stock_id);
        $current_stock = 0;
        $data = collect($stock)->map(function ($row) {
          $current_stock = $row->current_stock;
        });
        dd($row->current_stock); 
        $current_stock = $stock->get()->current_stock + $new_stock - $remove_stock;
        $stock->update(array('current_stock' => $current_stock));  // update the record in the DB. 
      }
      return response()->json([ 
        'message' => 'Successfully updated stock'
      ]);
      
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