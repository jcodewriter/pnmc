<?php

use App\AssetHistory;
use Illuminate\Database\Seeder;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;

class AssetHistoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $client = new Client();
    
        $currentHeight = [];
        try
        {
            $response = $client->get(config('services.ticker.endpoint') . '/v1/all/current');
            $currentHeight = \GuzzleHttp\json_decode($response->getBody()
                ->getContents(), true);
        }
        catch (RequestException $e)
        {
            abort(500, sprintf('Error fetching current height: %s', $e->getMessage()));
        }
    
        for ($i = 210330; $i < $currentHeight['Height']; $i++)
        {
            if (in_array($i, [212122, 206522, 210000, 212000, 212001, 212002, 212003]))
            {
                continue;
            }
            
            try
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
            catch (RequestException $e)
            {
                abort(500, sprintf('Error fetching height information: %s', $e->getMessage()));
            }
        }
    
        try
        {
            $assetHistory = ApiHelper::parseInfoForHeight($currentHeight);
        
            foreach ($assetHistory as $tickerSymbol => $info)
            {
                AssetHistory::firstOrCreate([
                    'ticker_symbol' => $tickerSymbol,
                    'height' => $i
                ], $info);
            }
        }
        catch (RequestException $e)
        {
            abort(500, sprintf('Error fetching height information: %s', $e->getMessage()));
        }
    }
}
