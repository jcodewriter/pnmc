<?php


namespace App\Services\Exchanges;

use App\Interfaces\ExchangeInterface;
use App\Traits\IsTradingPairIncluded;
use GuzzleHttp\Client;

/**
 * Class VineX
 * @package App\Services\Exchanges
 */
class VineX implements ExchangeInterface
{
    use IsTradingPairIncluded;

    /** @var array */
    protected $assets = [
        'PEG' => 'PEG',
        'pETH' => 'PETH',
        'pUSD' => 'PUSD',
        'pFCT' => 'PFCT',
        'pBTC' => 'PBTC',
    ];

    /** @var array */
    protected $quoteSymbols = [
        'BTC' => 'BTC',
        'USDT' => 'USDT',
        'ETH' => 'ETH'
    ];

    /** @var array */
    protected $tradingPairs = [
        // 4th param is the volume symbol
        ['PEG', 'BTC', true, 'BTC'],
        ['PEG', 'USDT', true, 'USDT'],
        ['PEG', 'ETH', true, 'ETH'],

        ['pETH', 'BTC', true, 'BTC'],
        ['pETH', 'ETH', true, 'ETH'],

        ['pUSD', 'BTC', true, 'BTC'],

        ['pFCT', 'BTC', true, 'BTC'],

        ['pBTC', 'BTC', true, 'BTC'],
    ];


    /**
     * @return bool
     */
    public function shouldUpdate(): bool
    {
        return true;
    }

    /**
     * @param array $pricingData
     * @return array
     */
    public function getData(array $pricingData): array
    {
        $retval = [
            'key' => 'vinex',
            'data' => []
        ];

        $client = new Client();

        foreach ($this->tradingPairs as $tradingPair) {
            // Query for the latest trade to get price
            $response = $client->get('https://api.vinex.network/api/v2/get-ticker?market=' . $this->quoteSymbols[$tradingPair[1]] . '_' . $this->assets[$tradingPair[0]]);
            $marketInfo = \GuzzleHttp\json_decode($response->getBody()
                ->getContents(), true);

            $price = $marketInfo['data']['lastPrice'] ?? 0;
            if ($price && isset($pricingData[$tradingPair[1]])) {
                $price *= $pricingData[$tradingPair[1]];
            }

            if (!isset($retval['data'][$tradingPair[0]])) {
                $retval['data'][$tradingPair[0]] = [];
            }

            if (!isset($retval['data'][$tradingPair[0]][$tradingPair[1]])) {
                $retval['data'][$tradingPair[0]][$tradingPair[1]] = [];
            }

            $spread = 0.00;
            if ($marketInfo['data']['bidPrice'] > 0.00 && $marketInfo['data']['askPrice'] > 0.00) {
                $spread = (abs($marketInfo['data']['askPrice'] - $marketInfo['data']['bidPrice']) / $marketInfo['data']['bidPrice']) * 100;
            }

            $retval['data'][$tradingPair[0]][$tradingPair[1]] = [
                'price' => $price,
                'volume' => (float)($marketInfo['data']['baseVolume'] ?? 0),
                'volume_symbol' => $tradingPair[3],
                'bid' => $marketInfo['data']['bidPrice'],
                'ask' => $marketInfo['data']['askPrice'],
                'spread' => $spread,
                'included' => $tradingPair[2]
            ];
        }

        return $retval;
    }

    /**
     * @param string $tickerSymbol
     * @param string $quoteSymbol
     * @return string
     */
    public function getLink(string $tickerSymbol, string $quoteSymbol = 'BTC'): string
    {
        if (!isset($this->assets[$tickerSymbol], $this->quoteSymbols[$quoteSymbol]))
        {
            return '';
        }

        return '<a href="https://vinex.network/market/' . $this->quoteSymbols[$quoteSymbol] . '_' . $this->assets[$tickerSymbol] . '" target="_blank">
            ' . $tickerSymbol . ' / ' . $quoteSymbol . '
        </a>' . (!$this->isIncluded($tickerSymbol, $quoteSymbol) ? ('<br />' . trans('pegnet.price_not_reflected')) : '');
    }
}
