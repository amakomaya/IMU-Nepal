<?php

namespace App\Console\Commands;

use Symfony\Component\Process\Process;
use App\Models\LabTest;
use App\Models\SampleCollection;
use DB;

use Illuminate\Console\Command;

class FixDuplicateLabId extends Command
{
    protected $signature = 'fix:dup:labId';

    protected $description = 'Fix duplicate lab-ids';

    protected $process;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
      $duplicateLabIds = DB::table('ancs')
      ->select('lab_token')
      ->whereNotNull('lab_token')
      ->groupBy('lab_token')
      ->havingRaw('COUNT(*) > 1')
      ->get()->pluck('lab_token');
      foreach($duplicateLabIds as $labId){
        $sampleIds = SampleCollection::where('lab_token', $labId)->get()->pluck('token');
        //change rest of the id of the sample
        foreach($sampleIds as $index=>$sampleId){
          //ignore fist sample id
          if($index==0) continue;
          SampleCollection::where('token', $sampleId)->update(['lab_token' => DB::raw('CONCAT(lab_token,"_dup'.$index.'")')]);
          LabTest::where('sample_token', $sampleId)->update(['token' => DB::raw('CONCAT(token,"_dup'.$index.'")')]);
        }
      }
      // dd($duplicateLabIds);
    }
}
