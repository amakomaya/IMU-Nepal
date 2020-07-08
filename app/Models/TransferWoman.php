<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class TransferWoman extends Model
{
    public static function healthpostTransferMessage(){
        $messages = array();
        if(Auth::user()->role=="healthpost"){
            $loggedInToken = Auth::user()->token;
            $healthpost = Healthpost::where('token', $loggedInToken)->get()->first();
            $messages = TransferWoman::where([['to_hp_code', $healthpost->hp_code], ['status', '1']])->get();
        }
        return $messages;
    }
}
