<?php

namespace App\Console;

use App\Models\CaseManagement;
use App\Models\ClinicalParameter;
use App\Models\ContactDetail;
use App\Models\ContactFollowUp;
use App\Models\ContactTracing;
use App\Models\LaboratoryParameter;
use App\Models\LabTest;
use App\Models\Message;
use App\Models\OrganizationMember;
use App\Models\SampleCollection;
use App\Models\SuspectedCase;
use App\Models\Symptoms;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Spatie\Activitylog\Models\Activity;
use Yagiten\Nepalicalendar\Calendar;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\MySqlDump::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('db:dump')
            ->dailyAt('01:00');
//    ->everyMinute();
        $schedule->call(function () {
            SuspectedCase::query()
                ->where('created_at', '<=', now()->subDays(15))
                ->each(function ($oldRecord) {
                    $newRecord = $oldRecord->replicate();
                    try{
                        $newRecord->setConnection('mysqldump')->save();
                        $oldRecord->forceDelete();
                    }catch (\Exception $e){}
                });
            SampleCollection::query()
                ->where('created_at', '<=', now()->subDays(15))
                ->each(function ($oldRecord) {
                    $newRecord = $oldRecord->replicate();
                    try{
                        $newRecord->setConnection('mysqldump')->save();
                        $oldRecord->forceDelete();
                    }catch (\Exception $e){}
                });
            LabTest::query()
                ->where('created_at', '<=', now()->subDays(15))
                ->each(function ($oldRecord) {
                    $newRecord = $oldRecord->replicate();
                    try{
                        $newRecord->setConnection('mysqldump')->save();
                        $oldRecord->forceDelete();
                    }catch (\Exception $e){}
                });
            Activity::query()
                ->where('created_at', '<=', now()->subDays(1))
                ->each(function ($oldRecord) {
                    $newRecord = $oldRecord->replicate();
                    try{
                        $newRecord->setConnection('mysqldump')->save();
                        $oldRecord->delete();
                    }catch (\Exception $e){}
                });
            CaseManagement::query()
                ->where('created_at', '<=', now()->subDays(15))
                ->each(function ($oldRecord) {
                    $newRecord = $oldRecord->replicate();
                    try{
                        $newRecord->setConnection('mysqldump')->save();
                        $oldRecord->delete();
                    }catch (\Exception $e){}
                });
            ClinicalParameter::query()
                ->where('created_at', '<=', now()->subDays(15))
                ->each(function ($oldRecord) {
                    $newRecord = $oldRecord->replicate();
                    try{
                        $newRecord->setConnection('mysqldump')->save();
                        $oldRecord->delete();
                    }catch (\Exception $e){}
                });
            ContactDetail::query()
                ->where('created_at', '<=', now()->subDays(15))
                ->each(function ($oldRecord) {
                    $newRecord = $oldRecord->replicate();
                    try{
                        $newRecord->setConnection('mysqldump')->save();
                        $oldRecord->delete();
                    }catch (\Exception $e){}
                });
            ContactFollowUp::query()
                ->where('created_at', '<=', now()->subDays(15))
                ->each(function ($oldRecord) {
                    $newRecord = $oldRecord->replicate();
                    try{
                        $newRecord->setConnection('mysqldump')->save();
                        $oldRecord->delete();
                    }catch (\Exception $e){}
                });
            ContactTracing::query()
                ->where('created_at', '<=', now()->subDays(15))
                ->each(function ($oldRecord) {
                    $newRecord = $oldRecord->replicate();
                    try{
                        $newRecord->setConnection('mysqldump')->save();
                        $oldRecord->delete();
                    }catch (\Exception $e){}
                });
            LaboratoryParameter::query()
                ->where('created_at', '<=', now()->subDays(15))
                ->each(function ($oldRecord) {
                    $newRecord = $oldRecord->replicate();
                    try{
                        $newRecord->setConnection('mysqldump')->save();
                        $oldRecord->delete();
                    }catch (\Exception $e){}
                });
            Message::query()
                ->where('created_at', '<=', now()->subDays(2))
                ->each(function ($oldRecord) {
                    $newRecord = $oldRecord->replicate();
                    try{
                        $newRecord->setConnection('mysqldump')->save();
                        $oldRecord->forceDelete();
                    }catch (\Exception $e){}
                });
            Symptoms::query()
                ->where('created_at', '<=', now()->subDays(15))
                ->each(function ($oldRecord) {
                    $newRecord = $oldRecord->replicate();
                    try{
                        $newRecord->setConnection('mysqldump')->save();
                        $oldRecord->delete();
                    }catch (\Exception $e){}
                });
        })->dailyAt('02:00');
//        })->everyMinute();

        $schedule->call(function (){
            SuspectedCase::where('age_unit', '')->update(['age_unit' => '0']);
            SuspectedCase::whereNull('register_date_en')->get()
                ->map(function ($item){
                    $item->register_date_en = $item->created_at->toDateString();
                    $collection_date_en = explode("-", Carbon::parse($item->created_at)->toDateString());
                    $item->register_date_np = Calendar::eng_to_nep($collection_date_en[0], $collection_date_en[1], $collection_date_en[2])->getYearMonthDayEngToNep();
                    $item->update();
            });
        })->everyTenMinutes();

        $schedule->call(function (){
            SampleCollection::where('service_for', '')->update(['service_for' => '1']);
            SampleCollection::where('infection_type', '')->update(['infection_type' => '2']);
            SampleCollection::whereNull('infection_type')->update(['infection_type' => '2']);
            SampleCollection::whereNull('sample_type')->update(['sample_type' => '[]']);
            SampleCollection::where('sample_type', '')->update(['sample_type' => '[]']);
        })->everyTenMinutes();

        $schedule->call(function (){
            SampleCollection::whereNull('checked_by')->get()->groupBy('hp_code')
                ->map(function ($item, $key){
                    $org_mem = OrganizationMember::where('hp_code', $key)->first();
                    if($org_mem){
                        $ids = $item->pluck('id');
                        SampleCollection::whereIn('id', $ids)->update([
                            'checked_by' => $org_mem->token,
                            'checked_by_name' => $org_mem->name
                        ]);
                    }
                });
        })->everyThirtyMinutes();

        $schedule->call(function (){

            SampleCollection::whereNull('collection_date_en')->get()
                ->map(function ($item){
                    $item->collection_date_en = $item->created_at->toDateString();
                    $collection_date_en = explode("-", Carbon::parse($item->created_at)->toDateString());
                    $item->collection_date_np = Calendar::eng_to_nep($collection_date_en[0], $collection_date_en[1], $collection_date_en[2])->getYearMonthDayEngToNep();
                    $item->update();
                });
        })->everyFifteenMinutes();

        $schedule->call(function (){

            LabTest::whereNull('sample_recv_date')->get()->map(function ($item){
                $item->sample_recv_date = $item->sample_test_date;
                $item->update();
            });

            LabTest::where('sample_test_result', '')->update(['sample_test_result' => '9']);

            LabTest::whereNull('sample_recv_date')->get()->map(function ($item){
                $item->sample_recv_date = $item->sample_test_date;
                $item->update();
            });

            LabTest::where('sample_test_result', '')->update(['sample_test_result' => '9']);
        })->everyTenMinutes();

        $schedule->call(function (){
                SampleCollection::whereNull('lab_token')->get()->map(function ($item){
                $lab_token = LabTest::where('sample_token', $item->token)->first();
                if($lab_token){
                    try{
                        $received_date_np = explode("-", $lab_token->sample_recv_date);
                        $received_date_en = Calendar::nep_to_eng($received_date_np[0], $received_date_np[1], $received_date_np[2])->getYearMonthDayNepToEng();
                        if (!empty($lab_token->sample_test_date)){
                            $sample_test_date_np = explode("-", $lab_token->sample_test_date);
                            $sample_test_date_en = Calendar::nep_to_eng($sample_test_date_np[0], $sample_test_date_np[1], $sample_test_date_np[2])->getYearMonthDayNepToEng();
                        }
                        $item->received_by = $lab_token->checked_by;
                        $item->received_by_hp_code = $lab_token->hp_code;
                        $item->received_date_en = $received_date_en;
                        $item->received_date_np = $lab_token->sample_recv_date;
                        $item->sample_test_date_en = $sample_test_date_en ?? null;
                        $item->sample_test_date_np = $lab_token->sample_test_date;
                        $item->sample_test_time = $lab_token->sample_test_time;
                        $item->lab_token = $lab_token->token;
                        $item->save();
                    }catch (\Exception $e){
                    }
                }
            });
            })->everyThirtyMinutes();
        }
    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
