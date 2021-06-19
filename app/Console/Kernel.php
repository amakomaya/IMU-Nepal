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
use App\Models\SampleCollection;
use App\Models\SuspectedCase;
use App\Models\Symptoms;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Spatie\Activitylog\Models\Activity;

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
           SuspectedCase::whereDate('created_at', '<', Carbon::parse('2019-01-01'))->get()
                ->map(function ($item){
                    $item->created_at = $item->updated_at;
                    $item->register_date_en = $item->updated_at->toDateString();
                    $collection_date_en = explode("-", Carbon::parse($item->updated_at)->toDateString());
                    $item->register_date_np = Calendar::eng_to_nep($collection_date_en[0], $collection_date_en[1], $collection_date_en[2])->getYearMonthDay();
                    $item->update();
                });
            \App\Models\SuspectedCase::where('age_unit', '')->update(['age_unit' => '0']);
            SuspectedCase::whereNull('register_date_en')->get()
                ->map(function ($item){
                    $item->register_date_en = $item->created_at->toDateString();
                    $collection_date_en = explode("-", Carbon::parse($item->created_at)->toDateString());
                    $item->register_date_np = Calendar::eng_to_nep($collection_date_en[0], $collection_date_en[1], $collection_date_en[2])->getYearMonthDay();
                    $item->update();
            });
            SuspectedCase::whereDate('register_date_en', '>=', Carbon::now())->get()
                ->map(function ($item){
                    $item->register_date_en = $item->created_at->toDateString();
                    $collection_date_en = explode("-", Carbon::parse($item->created_at)->toDateString());
                    $item->register_date_np = Calendar::eng_to_nep($collection_date_en[0], $collection_date_en[1], $collection_date_en[2])->getYearMonthDay();
                    $item->update();
                });
            SuspectedCase::whereNull('register_date_np')->get()->map(function ($item){
                $collection_date_en = explode("-", Carbon::parse($item->register_date_en)->toDateString());
                $item->register_date_np = Calendar::eng_to_nep($collection_date_en[0], $collection_date_en[1], $collection_date_en[2])->getYearMonthDay();
                $item->update();
            });
        })->everyTenMinutes();
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
