<?php

namespace App\Console\Commands;

use App\Asset;
use App\AssetHistory;
use ApiHelper;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class UpdateAssets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pegnet:update {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates all assets from the PegNet API.';


    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $client = new Client();

        try
        {
            $response = $client->get(config('services.ticker.endpoint') . '/v1/all/current');
            $currentHeight = \GuzzleHttp\json_decode($response->getBody()
                ->getContents(), true);
        }
        catch (RequestException $e)
        {
            $this->error(sprintf('Error fetching current height: %s', $e->getMessage()));
            return 1;
        }

        try
        {
            ApiHelper::updateAssetList($currentHeight);
        }
        catch (RequestException $e)
        {
            abort(500, sprintf('Error updating assets: %s', $e->getMessage()));
        }

        $asset = Asset::first();
        if ($asset->height == $currentHeight['Height'] && !$this->option('force'))
        {
            $this->info('Already up to date.');
            return 0;
        }

        try
        {
            for ($i = $asset->height; $i < $currentHeight['Height']; $i++)
            {
                $assetHistory = ApiHelper::getInfoForHeightId($i);

                foreach ($assetHistory as $tickerSymbol => $info)
                {
                    AssetHistory::firstOrCreate([
                        'ticker_symbol' => $tickerSymbol,
                        'height' => $i
                    ], $info);
                }
            }
        }
        catch (RequestException $e)
        {
            $this->error(sprintf('Error fetching height info: %s', $e->getMessage()));
            return 1;
        }

        $assetHistory = ApiHelper::parseInfoForHeight($currentHeight);

        foreach ($assetHistory as $tickerSymbol => $info)
        {
            AssetHistory::updateOrCreate([
                'ticker_symbol' => $tickerSymbol,
                'height' => $currentHeight['Height']
            ], $info);
        }
    }
}
