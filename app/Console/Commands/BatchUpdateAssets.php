<?php

namespace App\Console\Commands;

use ApiHelper;
use App\AssetHistory;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Console\Command;

class BatchUpdateAssets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pegnet:batch-update {startHeight} {endHeight}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates assets from the PegNet API starting at [startHeight] and ending at [endHeight].';

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
     * @throws \Exception
     */
    public function handle()
    {
        for ($i = (int)$this->argument('startHeight'); $i <= (int)$this->argument('endHeight'); $i++)
        {
            try
            {
                $assetHistory = ApiHelper::getInfoForHeightId($i);

                foreach ($assetHistory as $tickerSymbol => $info)
                {
                    AssetHistory::updateOrCreate([
                        'ticker_symbol' => $tickerSymbol,
                        'height'        => $i
                    ], $info);
                }
            }
            catch (RequestException $e)
            {
                $this->error(sprintf('Error fetching height info: %s', $e->getMessage()));
                return 1;
            }
        }
    }
}
