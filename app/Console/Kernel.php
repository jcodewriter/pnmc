<?php

namespace App\Console;

use App\ExchangePriceHistory;
use App\Invokable\AggregateExchange;
use App\Invokable\AggregateMarketCap;
use App\Invokable\AggregateVolume;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('pegnet:update')
            ->cron('* * * * *')
            ->withoutOverlapping(1)
            ->environments(['local', 'staging', 'production']);

        $schedule->command('pegnet:update-exchange-data')
            ->everyMinute()
            ->withoutOverlapping(5)
            ->environments(['local', 'staging', 'production']);

        $schedule->command('pegnet:update-exchange-price-history')
            ->everyTenMinutes()
            ->environments(['local', 'staging', 'production']);


        $schedule->call(new AggregateExchange, [
            strtotime('midnight today') - 1
        ])
            ->daily();

        $schedule->call(new AggregateMarketCap, [
            strtotime('midnight today') - 1
        ])
            ->daily();

        $schedule->call(new AggregateVolume, [
            strtotime('midnight yesterday') - 1,
            strtotime('midnight today') - 1
        ])
            ->daily();

        $schedule->call(function () {
            \DB::delete('DELETE FROM exchange_volume_history WHERE dateline < ?', [time() - (86400 * 2)]);
        })
            ->daily();

        $schedule->call(function () {
            \DB::delete('DELETE FROM asset_exchange_price_history WHERE dateline < ?', [time() - (86400 * 2)]);
        })
            ->daily();
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
