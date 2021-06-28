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
use DB;

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

        // $schedule->call(function () {
        //     DB::connection('mysqldump')->table('women')->whereNotNull('deleted_at')->delete();
        //     DB::connection('mysqldump')->table('ancs')->whereNotNull('deleted_at')->delete();
        //     DB::connection('mysqldump')->table('lab_tests')->whereNotNull('deleted_at')->delete();
        //     SuspectedCase::query()
        //         ->whereNotNull('deleted_at')
        //         ->each(function ($oldRecord) {
        //             $newRecord = $oldRecord->replicate();
        //             try{
        //                 $newRecord->setConnection('mysqldump')->save();
        //                 $oldRecord->forceDelete();
        //             }catch (\Exception $e){}
        //         });
        //     SampleCollection::query()
        //         ->whereNotNull('deleted_at')
        //         ->each(function ($oldRecord) {
        //             $newRecord = $oldRecord->replicate();
        //             try{
        //                 $newRecord->setConnection('mysqldump')->save();
        //                 $oldRecord->forceDelete();
        //             }catch (\Exception $e){}
        //         });

        //     LabTest::query()
        //         ->whereNotNull('deleted_at')
        //         ->each(function ($oldRecord) {
        //             $newRecord = $oldRecord->replicate();
        //             try{
        //                 $newRecord->setConnection('mysqldump')->save();
        //                 $oldRecord->forceDelete();
        //             }catch (\Exception $e){}
        //         });
        // })->daily();

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
