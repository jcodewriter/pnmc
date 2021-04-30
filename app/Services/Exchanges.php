<?php

namespace App\Services;

use App\AssetExchangePriceHistory;
use App\Exchange;
use App\Helpers\Formatter;
use App\Services\Exchanges\Bilaxy;
use App\Services\Exchanges\Citex;
use App\Services\Exchanges\Hotbit;
use App\Services\Exchanges\Idex;
use App\Services\Exchanges\qTrade;
use App\Services\Exchanges\VineX;
use App\Services\Exchanges\ViteX;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Console\Command;
use Illuminate\Contracts\Container\BindingResolutionException;
use Log;

/**
 * Class Exchanges
 * @package App\Services
 */
class Exchanges
{
    /**
     * @var array
     */
    protected $exchangeData = [
        'BTC' => 1,
        'ETH' => 1027,
        'USDT' => 825,
        'VITE' => 2937,
        'FCT' => 1087
    ];

    /**
     * @var array
     */
    protected $_pricingData = [];

    /**
     * @var array
     */
    protected $exchangeClasses = [
        'vitex' => ViteX::class,
        'vinex' => VineX::class,
        'citex' => Citex::class,
        'qtrade' => qTrade::class,
        'bilaxy' => Bilaxy::class,
        'hotbit' => Hotbit::class,
        'idex' => Idex::class
    ];

    protected $command;

    public function __construct(\Illuminate\Console\Command $command = null)
    {
        if ($command) {
            $this->command = $command;
        }
    }

    /**
     * @param $exchangeData
     */
    public function logData($exchangeData): void
    {
        $path = storage_path('app/exchange_data.csv');
        $file = fopen($path, 'ab');
        if (!file_exists($path)) {
            fputcsv($file, [
                'Ticker Symbol',
                'Quote Symbol',
                'Exchange',
                'Price',
                'Volume',
                'Bid',
                'Ask',
                'Spread',
                'Included',
                'Date'
            ]);
        }
        foreach ($exchangeData['data'] as $tickerSymbol => $tradingPairs) {
            foreach ($tradingPairs as $quoteSymbol => $assetData) {
                if ($assetData['volume'] <= 0.0) {
                    continue;
                }
                fputcsv($file, [
                    $tickerSymbol,
                    $quoteSymbol,
                    $exchangeData['key'],
                    $assetData['price'],
                    $assetData['volume'],
                    $assetData['bid'],
                    $assetData['ask'],
                    $assetData['spread'],
                    $assetData['included'],
                    now()
                ]);
            }
        }
        fclose($file);
    }

    /**
     * @param $class
     * @return bool|mixed
     */
    protected function _makeExchange($class)
    {
        try {
            $exchange = app()->make($class);
        } catch (BindingResolutionException $e) {
            return false;
        }
        return $exchange->shouldUpdate() ? $exchange : false;
    }

    /**
     * @param $data
     * @param $exchange
     * @return mixed
     */
    protected function _getData($data, $exchange)
    {
        try {
            $exchangeData = $exchange->getData($this->_pricingData);
        } catch (RequestException $e) {
            $message = sprintf('Error updating pricing data from remote API: %s', $e->getMessage());
            Log::error($message);
            $this->command->error($message);
            return $data;
        }
        if (app()->environment('local')) {
            $this->logData($exchangeData);
        }

        foreach ($exchangeData['data'] as $tickerSymbol => $tradingPairs) {
            if (!isset($data[$tickerSymbol])) {
                $data[$tickerSymbol] = [
                    'totalVolume' => 0,
                    'weightedVolume' => 0,
                    'displayPrice' => 0.00,
                    'exchangeData' => []
                ];
            }

            foreach ($tradingPairs as $quoteSymbol => $assetData) {
                $data[$tickerSymbol]['exchangeData'][$exchangeData['key']][$quoteSymbol] = $assetData;
                if ($assetData['volume'] <= 0) {
                    continue;
                }
                $data[$tickerSymbol]['totalVolume'] += $assetData['volume'];
                if (empty($assetData['included'])) {
                    continue;
                }
                $data[$tickerSymbol]['weightedVolume'] += $assetData['volume'];
            }
        }
        return $data;
    }

    /**
     * @param $individualData
     * @param $exchange
     * @param $tickerSymbol
     * @param $quoteSymbol
     * @return bool
     */
    protected function _isDataIncluded($individualData, $exchange, $tickerSymbol, $quoteSymbol): bool
    {
        if ($individualData['volume'] <= 0 || empty($individualData['included'])) {
            return false;
        }

        /* @var AssetExchangePriceHistory|null $result */
        $result = AssetExchangePriceHistory::query()
            ->where('exchange', $exchange)
            ->where('ticker_symbol', $tickerSymbol)
            ->where('quote_symbol', $quoteSymbol)
            ->orderBy('dateline', 'DESC')
            ->limit(1)
            ->get()
            ->first();
        if (!$result) {
            return true;
        }

        $priceChange = PHP_INT_MAX;
        if ($result->price > 0.00) {
            $priceChange = (($individualData['price'] - $result->price) / $result->price) * 100;
        }
        if ($priceChange <= -50 || $priceChange >= 100) {
            Log::alert(trans('logging.exchange_price_change', [
                'exchange' => $exchange,
                'ticker' => $tickerSymbol,
                'quote' => $quoteSymbol,
                'percent' => ($priceChange === PHP_INT_MAX ? '∞' : Formatter::numberShort($priceChange)) . ' % ',
                'newPrice' => Formatter::number($individualData['price']),
                'oldPrice' => Formatter::number($result->price)
            ]));
            return false;
        }

        $volumeChange = PHP_INT_MAX;
        if ($result->volume > 0.00) {
            $volumeChange = (($individualData['volume'] - $result->volume) / $result->volume) * 100;
        }
        if ($volumeChange <= -500 || $volumeChange >= 1000) {
            Log::alert(trans('logging.exchange_volume_change', [
                'exchange' => $exchange,
                'ticker' => $tickerSymbol,
                'quote' => $quoteSymbol,
                'percent' => ($volumeChange === PHP_INT_MAX ? '∞' : Formatter::numberShort($volumeChange)) . ' % ',
                'newVolume' => Formatter::number($individualData['volume']) . ' ' . $individualData['volume_symbol'],
                'oldVolume' => Formatter::number($result->volume) . ' ' . $individualData['volume_symbol']
            ]));
            return false;
        }
        return true;
    }

    /**
     * @param Command $command
     * @param array $onlyExchanges
     * @return array
     */
    public function getDataFromExchanges(array $onlyExchanges = []): array
    {
        $this->_pricingData = $this->getPricingData();                      // get pricing data
        $exchanges = $this->exchangeClasses;                                // array of exchanges to update
        if (!empty($onlyExchanges)) {                                       // check if updating only certain exchanges
            $onlyExchanges = array_flip($onlyExchanges);                    // flip key/values to use in an intersect
            $exchanges = array_intersect_key($exchanges, $onlyExchanges);   // remove exchanges we don't want to update
        }
        $exchanges = array_map([$this, '_makeExchange'], $exchanges);       // make/load each exchange model
        $exchanges = array_filter($exchanges);                              // filter exchanges that should update
        $data = array_reduce($exchanges, [$this, '_getData'], []);           // get exchange data

        foreach ($data as $tickerSymbol => &$assetData) {
            if ($assetData['totalVolume'] <= 0.0) {
                continue;
            }

            foreach ($assetData['exchangeData'] as $exchange => $exchangeData) {
                foreach ($exchangeData as $quoteSymbol => $individualData) {
                    $isIncluded = $this->_isDataIncluded($individualData, $exchange, $tickerSymbol, $quoteSymbol);

                    AssetExchangePriceHistory::create([
                        'ticker_symbol' => $tickerSymbol,
                        'quote_symbol' => $quoteSymbol,
                        'exchange' => $exchange,
                        'dateline' => time(),
                        'price' => $individualData['price'],
                        'volume' => $individualData['volume'],
                        'included' => (int)$isIncluded,
                        'updated_at' => now()
                    ]);

                    if (!$isIncluded) {
                        continue;
                    }

                    // Exchange price multiplied with exchange's weight
                    $assetData['displayPrice'] += ($individualData['price'] * ($individualData['volume'] / $assetData['weightedVolume']));
                }
            }
        }
        return $data;
    }

    /**
     * @return array
     */
    public function getExchangePriceHistory(): array
    {
        $exchangeData = Exchange::get();

        $data = [];
        foreach ($exchangeData as $exchange) {
            if (!isset($data[$exchange->ticker_symbol])) {
                $data[$exchange->ticker_symbol] = [
                    'totalVolume' => 0,
                    'displayPrice' => 0.00,
                    'exchangeData' => []
                ];
            }

            $data[$exchange->ticker_symbol]['totalVolume'] += $exchange->volume;
            $data[$exchange->ticker_symbol]['exchangeData'][$exchange->exchange][$exchange->quote_symbol] = [
                'volume' => $exchange->volume,
                'price' => $exchange->price,
            ];
        }

        foreach ($data as $tickerSymbol => &$assetData) {
            if ($assetData['totalVolume'] <= 0.0) {
                continue;
            }

            foreach ($assetData['exchangeData'] as $exchangeData) {
                foreach ($exchangeData as $individualData) {
                    if ($individualData['volume'] <= 0.0) {
                        continue;
                    }

                    // Exchange price multiplied with exchange's weight
                    $assetData['displayPrice'] += ($individualData['price'] * ($individualData['volume'] / $assetData['totalVolume']));
                }
            }
        }

        return $data;
    }

    /**
     * @param string $exchange
     * @param string $tickerSymbol
     * @param string $quoteSymbol
     * @return string
     */
    public function getLink(string $exchange, string $tickerSymbol, string $quoteSymbol = 'BTC'): ?string
    {
        if (!isset($this->exchangeClasses[$exchange])) {
            return '';
        }
        try {
            $exchangeClass = app()->make($this->exchangeClasses[$exchange]);
            $link = $exchangeClass->getLink($tickerSymbol, $quoteSymbol);
        } catch (BindingResolutionException $e) {
            return '';
        }
        return $link;
    }

    /**
     * @return array
     */
    protected function getPricingData(): array
    {
        $priceData = array_fill_keys(array_keys($this->exchangeData), 0.00);
        try {
            $client = new Client();
            $response = $client->get('https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest', [
                'headers' => [
                    'Accept' => 'application/json',
                    'X-CMC_PRO_API_KEY' => config('services.cmc.key')
                ],
                'query' => ['id' => implode(',', $this->exchangeData)]
            ]);
            $prices = json_decode($response->getBody()->getContents(), false);
            foreach ($prices->data as $data) {
                $priceData[$data->symbol] = $data->quote->USD->price;
            }
        } catch (RequestException $e) {
            Log::emergency($e->getMessage());
        }
        return $priceData;
    }
}
