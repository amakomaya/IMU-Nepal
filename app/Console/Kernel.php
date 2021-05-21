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
                    $newRecord->setConnection('mysqldump')->save();
                    $oldRecord->forceDelete();
                });
            SampleCollection::query()
                ->where('created_at', '<=', now()->subDays(15))
                ->each(function ($oldRecord) {
                    $newRecord = $oldRecord->replicate();
                    $newRecord->setConnection('mysqldump')->save();
                    $oldRecord->forceDelete();
                });
            LabTest::query()
                ->where('created_at', '<=', now()->subDays(15))
                ->each(function ($oldRecord) {
                    $newRecord = $oldRecord->replicate();
                    $newRecord->setConnection('mysqldump')->save();
                    $oldRecord->forceDelete();
                });
            Activity::query()
                ->where('created_at', '<=', now()->subDays(1))
                ->each(function ($oldRecord) {
                    $newRecord = $oldRecord->replicate();
                    $newRecord->setConnection('mysqldump')->save();
                    $oldRecord->delete();
                });
            CaseManagement::query()
                ->where('created_at', '<=', now()->subDays(15))
                ->each(function ($oldRecord) {
                    $newRecord = $oldRecord->replicate();
                    $newRecord->setConnection('mysqldump')->save();
                    $oldRecord->delete();
                });
            ClinicalParameter::query()
                ->where('created_at', '<=', now()->subDays(15))
                ->each(function ($oldRecord) {
                    $newRecord = $oldRecord->replicate();
                    $newRecord->setConnection('mysqldump')->save();
                    $oldRecord->delete();
                });
            ContactDetail::query()
                ->where('created_at', '<=', now()->subDays(15))
                ->each(function ($oldRecord) {
                    $newRecord = $oldRecord->replicate();
                    $newRecord->setConnection('mysqldump')->save();
                    $oldRecord->delete();
                });
            ContactFollowUp::query()
                ->where('created_at', '<=', now()->subDays(15))
                ->each(function ($oldRecord) {
                    $newRecord = $oldRecord->replicate();
                    $newRecord->setConnection('mysqldump')->save();
                    $oldRecord->delete();
                });
            ContactTracing::query()
                ->where('created_at', '<=', now()->subDays(15))
                ->each(function ($oldRecord) {
                    $newRecord = $oldRecord->replicate();
                    $newRecord->setConnection('mysqldump')->save();
                    $oldRecord->delete();
                });
            LaboratoryParameter::query()
                ->where('created_at', '<=', now()->subDays(15))
                ->each(function ($oldRecord) {
                    $newRecord = $oldRecord->replicate();
                    $newRecord->setConnection('mysqldump')->save();
                    $oldRecord->delete();
                });
            Message::query()
                ->where('created_at', '<=', now()->subDays(2))
                ->each(function ($oldRecord) {
                    $newRecord = $oldRecord->replicate();
                    $newRecord->setConnection('mysqldump')->save();
                    $oldRecord->forceDelete();
                });
            Symptoms::query()
                ->where('created_at', '<=', now()->subDays(15))
                ->each(function ($oldRecord) {
                    $newRecord = $oldRecord->replicate();
                    $newRecord->setConnection('mysqldump')->save();
                    $oldRecord->delete();
                });
        })->dailyAt('02:00');
//        })->everyMinute();
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
