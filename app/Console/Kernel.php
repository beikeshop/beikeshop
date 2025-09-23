<?php

namespace App\Console;

use Beike\Console\Commands\Sequence;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Sequence::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('process:order')->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $paths = [
            __DIR__ . '/Commands',
            base_path('beike/Console/Commands'),
        ];
        $this->load($paths);

        require base_path('routes/console.php');
    }
}
