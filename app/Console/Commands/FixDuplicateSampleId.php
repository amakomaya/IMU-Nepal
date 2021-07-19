<?php

namespace App\Console\Commands;

use Symfony\Component\Process\Process;
use App\Models\LabTest;
use App\Models\SampleCollection;
use DB;
use Exception;

use Illuminate\Console\Command;

class FixDuplicateSampleId extends Command
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
      $duplicateSids = DB::table('ancs')
      ->select('token')
      ->whereNull('deleted_at')
      ->groupBy('token')
      ->havingRaw('COUNT(*) > 1')
      ->get()->pluck('token');
      foreach($duplicateSids as $sId){
        $samples = SampleCollection::where('token', $sId)->get();
        //change rest of the id of the sample
        foreach($samples as $index=>$sample){
          //ignore fist sample id
          if($index==0) continue;
          $this->info($sample->token);
          try {
            $sample->update(['token' => DB::raw('CONCAT(token,"_dup'.$index.'")')]);
            if($sample->lab_token) {
              //change sample id in lab_token as well.
            }
            // if()
            SampleCollection::where('id', $sampleId)->update(['lab_token' => DB::raw('CONCAT(lab_token,"_dup'.$index.'")')]);
            LabTest::where('sample_token', $sampleId)->update(['token' => DB::raw('CONCAT(token,"_dup'.$index.'")')]);
            $this->info('success');
          } catch (Exception $e){
            $this->error($e->getMessage());
          }
        }
      }
      // dd($duplicateLabIds);
    }
}
