<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Reports\FilterRequest;
use Illuminate\Support\Str;
use Carbon\Carbon;
use QrCode;
use Zip;
use App\Jobs\QrCodeGenerate;
use Illuminate\Filesystem\Filesystem;

class AamakomayaGenerateQrcode extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function get(Request $request)
    {
        $response = FilterRequest::filter($request);
        $municipality_id = $response['municipality_id'];
        $path = storage_path('app/tmp/'.$municipality_id.'/');
        (new Filesystem)->cleanDirectory($path);
        return view('backend.qr-codes.index');
    }

    public function download(Request $request)
    {
        $response = FilterRequest::filter($request);
        $municipality_id = $response['municipality_id'];      
        $no_of_qrcodes = $request->number ?? 0;
        $tokens = [];
        $path = storage_path('app/tmp/'.$municipality_id.'/');

        if($no_of_qrcodes > 0){
            (new Filesystem)->cleanDirectory($path);
            for ($i=0; $i < $no_of_qrcodes; $i++) { 
                $time = time();
                $uuid = (string) Str::uuid();
                $token = $municipality_id."-".$uuid."-".$time;
                array_push($tokens, $token);
            }
            foreach ($tokens as $qrcode) {
                $img = 'data:image/png;base64,'.base64_encode(
                    QrCode::format('png')
                        ->size(200)
                        ->color(170,21,21)
                        ->margin(2)
                        ->generate($qrcode)
                    );
                $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $img));
                Storage::disk('local')->put('/tmp/'.$municipality_id.'/'.$qrcode.'.png', $data);
            }
            $zip_file = $municipality_id.'_qr_codes.zip';
            return Zip::create($zip_file,
                glob($path.'*')
            );
        }
    }
}