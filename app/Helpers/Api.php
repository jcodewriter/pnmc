<?php

namespace App\Helpers;

use App\Asset;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

class Api
{
    /** @var array|null */
    protected static $removedAssets;

    /**
     * @return array
     */
    public static function getRemovedAssets()
    {
        if (self::$removedAssets === NULL)
        {
            /** @var array $removed */
            $removed = Asset::onlyTrashed()->pluck('ticker_symbol')->all();

            self::$removedAssets = $removed;
        }

        return self::$removedAssets;
    }

    /**
     * @param array $currentHeight
     */
    public static function updateAssetList(array $currentHeight = [])
    {
        $client = new Client();

        if (empty($currentHeight))
        {
            $response = $client->get(config('services.ticker.endpoint') . '/v1/all/current');
            $currentHeight = \GuzzleHttp\json_decode($response->getBody()
                ->getContents(), true);
        }

        if (empty($currentHeight['Height']))
        {
            return;
        }

        try {
            Cache::set('totalConversions', floatval($currentHeight['TotalConversions']));
        }
        catch (InvalidArgumentException $e) {
        }

        $response = $client->get(config('services.ticker.endpoint') . '/v1/asset/names');
        $assets = \GuzzleHttp\json_decode($response->getBody()
            ->getContents(), true);

        foreach ($assets as $tickerSymbol => $title)
        {
            if (in_array($tickerSymbol, self::getRemovedAssets()))
            {
                continue;
            }

            Asset::firstOrCreate([
                'ticker_symbol' => $tickerSymbol
            ], [
                'title' => $title,
                'icon_file' => mb_strtolower($tickerSymbol . '.png'),
                'height' => $currentHeight['Height'],
                'supply' => $currentHeight['Data'][$tickerSymbol][4] ?? 0,
                'price' => $currentHeight['Data'][$tickerSymbol][5] ?? 0,
                'updated_at' => $currentHeight['Blocktime'] ?? 0,
            ]);
        }
    }

    /**
     * @param int $i
     *
     * @return array
     * @throws \Exception
     */
    public static function getInfoForHeightId(int $i)
    {
        $client = new Client();

        $response = $client->get(config('services.ticker.endpoint') . '/v1/all/' . $i);
        $height = \GuzzleHttp\json_decode($response->getBody()
            ->getContents(), true);

        return self::parseInfoForHeight($height);
    }


    /**
     * @param array $height
     *
     * @return array
     * @throws \Exception
     */
    public static function parseInfoForHeight(array $height)
    {
        $assetHistory = [];
        foreach ($height['Data'] as $tickerSymbol => $info)
        {
            if (in_array($tickerSymbol, self::getRemovedAssets()))
            {
                continue;
            }

            if (!isset($assetHistory[$tickerSymbol]))
            {
                $assetHistory[$tickerSymbol] = [];
            }

            $assetHistory[$tickerSymbol] = [
                'volume' => $info[0] ?? 0,
                'volume_in' => $info[1] ?? 0,
                'volume_out' => $info[2] ?? 0,
                'volume_tx' => $info[3] ?? 0,
                'supply' => $info[4] ?? 0,
                'price' => $info[5] ?? 0,
            ];
        }

        foreach ($assetHistory as $key => &$arr)
        {
            $arr['dateline'] = $height['Blocktime'];
            $arr['updated_at'] = (new \DateTime())->setTimestamp($height['Blocktime'])->format('Y-m-d H:i:s');
        }

        return $assetHistory;
    }


}
