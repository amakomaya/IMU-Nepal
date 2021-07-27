<?php

namespace App\Console\Commands;

use Symfony\Component\Process\Process;
use App\Models\SuspectedCase;
use App\Models\OrganizationMember;
use Yagiten\Nepalicalendar\Calendar;
use Carbon\Carbon;
use DB;
use Exception;
use DateTime;

use Illuminate\Console\Command;

class FixEmptyCaseId extends Command
{
    protected $signature = 'fix:null:caseId {limit}';

    protected $description = 'Fix Null Case Id data';

    protected $process;

    public function __construct()
    {
        parent::__construct();
    }

    public function generateCaseId($caseData) {
      $dateCode = Carbon::now()->format('ymd');
      $caseId = OrganizationMember::where('token', $caseData->created_by)->first()->id . '-' . $dateCode . '-' . strtoupper(bin2hex(random_bytes(3)));
      return $caseId;
    }

    public function handle()
    {
      $limit = $this->argument('limit');
      $corruptedCaseData = SuspectedCase::whereNull('case_id');
      if($limit) {
        $corruptedCaseData =  $corruptedCaseData->take($limit);
      }
      $corruptedCaseData =  $corruptedCaseData->get();
      DB::beginTransaction();
      foreach($corruptedCaseData as $caseData) {
        try {
          $this->info('Suspected Case token: '.$caseData->token);
          $caseId = $this->generateCaseId($caseData);
          $caseData->update(
            [
              'case_id' => $caseId
            ]
          );
          $this->info('Successfully assigned CaseId '. $caseId);
        } catch (Exception $e){
          DB::rollback();
          $this->error($e->getMessage());
          break;
        }
      }
      DB::commit();
    }
}
