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
        $data = $request->all();
        $stock_data = [];
        $stock_data['current_stock'] = $data['new_stock'] - $data['remove_stock'];
        $stock_data['hp_code'] = $data['hp_code'];
        $stock_data['asset_id'] = $data['asset_id'];
      if($data['stock_id'] == 0){
          Stock::create($stock_data);
      } else {
          $stock = Stock::where('id', $data['stock_id'])->first();
          $update_data = $stock->current_stock + $stock_data['current_stock'];
          $stock->update(['current_stock' => $update_data]);
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