<?php

namespace App\Http\Controllers\Api\v3;

use App\Models\BabyDetail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BabyController
{
    public function index(Request $request)
    {
        $baby_token = $request->token;
        $baby = BabyDetail::where('token', $baby_token)->isAlive()->active()
                    ->withAll()
//                    ->whereHas('vaccinations', function (Builder $query) {
//                        $query->where('status', 1);
//                    })
                    ->get();
        return response()->json($baby);
    }
}