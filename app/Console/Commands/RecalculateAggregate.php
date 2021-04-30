<?php

namespace App\Console\Commands;

use App\AssetHistory;
use App\Invokable\AggregateMarketCap;
use Illuminate\Console\Command;

class RecalculateAggregate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pegnet:rebuild-market-cap-aggregate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuilds the market cap aggregate based on asset history.';

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
            $obj = new AggregateMarketCap;
            $obj($startDate);
            unset($obj);
            
            $startDate = strtotime('+1 day', $startDate);
        }
    }
}
