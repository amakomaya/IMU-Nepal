<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\Asset;
use App\Models\Organization;
use App\Reports\FilterRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Helpers\GetHealthpostCodes;
use App\Models\StockTransaction;

class StockController extends Controller
{

  public function listStock(Request $request)
  {
      $response = FilterRequest::filter($request);
      $hpCodes = GetHealthpostCodes::filter($response);
      $stocks = Stock::whereIn('hp_code', $hpCodes)
         ->withAll()
         ->join('assets', 'assets.id', '=', 'stocks.asset_id')
        ->get(['stocks.*', 'assets.name'])
        ->toArray();
      $stockAssetIds = [];
      $assetIds = [];
      foreach($stocks as $stock) {
        array_push($stockAssetIds, $stock['asset_id']);
      }
      $assets = Asset::latest()->get();
      foreach($assets as $asset) {
          array_push($assetIds, $asset->id);
      };
      
      if(count($stocks)!== count($assets)) {
        foreach($assetIds as $index=>$assetId){
          if(!in_array($assetId, $stockAssetIds)) {
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

  public function listAdminStock(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $availableAssets = Asset::all()->pluck('name')->toArray();
        $groupedStocks = Stock::whereIn('hp_code', $hpCodes)
           ->withAll()
           ->join('assets', 'assets.id', '=', 'stocks.asset_id')
          ->get(['stocks.*', 'assets.name'])
          ->groupBy('hp_code')
          ->toArray();
        $stockList = [];
        $count = 0;
        foreach($groupedStocks as $stocks) {
          $stockList[$count] = [
            'organization_name' => $stocks[0]['healthpost']['name']
          ];

          foreach($stocks as $stock ) {
            $stockList[$count][$stock['name']] = $stock['current_stock'];
          }
          $count++;
        }
        return view('backend.stock.admin', [
            'stockList' => $stockList,
            'availableAssets' => $availableAssets
        ]);
    }
  
    public function updateStock(Request $request)
    {
        $user = auth()->user();
        $data = $request->all();
        $stock_transaction_data = $stock_data = [];
        $stock_transaction_data['current_stock'] = $stock_data['current_stock'] = $data['new_stock'] - $data['remove_stock'];
        $stock_transaction_data['hp_code'] = $stock_data['hp_code'] = $data['hp_code'];
        $stock_transaction_data['asset_id'] = $stock_data['asset_id'] = $data['asset_id'];
        $stock_transaction_data['new_stock'] = $data['new_stock'];
        $stock_transaction_data['used_stock'] = $data['remove_stock'];
        $stock_transaction_data['user_id'] = $user->id;
      if($data['stock_id'] == 0){
          $stock = Stock::create($stock_data);
          $stock_transaction_data['stock'] = $stock->id;
          StockTransaction::create($stock_transaction_data);
      } else {
          $stock = Stock::where('id', $data['stock_id'])->first();
          $update_data = $stock->current_stock + $stock_data['current_stock'];
          $stock_transaction_data['current_stock'] = $update_data;
          $stock->update(['current_stock' => $update_data]);
          StockTransaction::create($stock_transaction_data);
      }

      return response()->json([
        'message' => 'Successfully updated stock'
      ]);
    }
  
    public function stockTransactionList(Request $request)
    {
      $user = auth()->user();
      $healthpostInfo = Organization::where('token', $user->token)->first();
      $hpCode = $healthpostInfo->hp_code;
      $stock_transactions = StockTransaction::join('assets','assets.id','=','stock_transactions.asset_id')->select(['stock_transactions.*','assets.name',]) ->where('stock_transactions.hp_code', $hpCode)->orderBy('stock_transactions.id', 'DESC')->get()->toArray();
        return view('backend.stock.transaction', [
            'stock_transactions' => $stock_transactions,
        ]);
    }
}