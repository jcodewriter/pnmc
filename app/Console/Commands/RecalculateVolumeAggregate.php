<?php

namespace App\Console\Commands;

use App\AssetHistory;
use App\Invokable\AggregateVolume;
use Illuminate\Console\Command;

class RecalculateVolumeAggregate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pegnet:rebuild-volume-aggregate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuilds the volume aggregate based on asset history.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $startDate = AssetHistory::selectRaw('MIN(dateline) AS min_dateline')
            ->value('min_dateline')
        ;
        $startDate = strtotime('midnight tomorrow', $startDate) - 1;

        while ($startDate < time())
        {
            $obj = new AggregateVolume;
            $obj(strtotime('midnight yesterday', $startDate) - 1, $startDate);
            unset($obj);

            $startDate = strtotime('+1 day', $startDate);
        }
    }
}
