<?php


namespace App\Services\Exchanges;

use App\Interfaces\ExchangeInterface;
use App\Traits\IsTradingPairIncluded;
use GuzzleHttp\Client;

/**
 * Class qTrade
 * @package App\Services\Exchanges
 */
class qTrade implements ExchangeInterface
{
    use IsTradingPairIncluded;

    /** @var array */
    protected $assets = [
        'PEG' => 'PEG',
    ];

    /** @var array */
    protected $quoteSymbols = [
        'BTC' => 'BTC',
    ];

    /** @var array */
    protected $tradingPairs = [
        // 4th param is the volume symbol
        ['PEG', 'BTC', true, 'PEG'],
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
            'key' => 'qtrade',
            'data' => []
        ];

        $client = new Client();

        foreach ($this->tradingPairs as $tradingPair) {
            // Query for the latest trade to get price
            $response = $client->get('https://api.qtrade.io/v1/ticker/' . $this->assets[$tradingPair[0]] . '_' . $this->quoteSymbols[$tradingPair[1]]);
            $marketInfo = \GuzzleHttp\json_decode($response->getBody()
                ->getContents(), true);

            $price = $marketInfo['data']['last'] ?? 0;
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
            if ($marketInfo['data']['bid'] > 0.00 && $marketInfo['data']['ask'] > 0.00) {
                $spread = (abs($marketInfo['data']['ask'] - $marketInfo['data']['bid']) / $marketInfo['data']['bid']) * 100;
            }

            $retval['data'][$tradingPair[0]][$tradingPair[1]] = [
                'price' => $price,
                'volume' => (float)($marketInfo['data']['day_volume_market'] ?? 0),
                'volume_symbol' => $tradingPair[3],
                'bid' => $marketInfo['data']['bid'],
                'ask' => $marketInfo['data']['ask'],
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

        return '<a href="https://qtrade.io/market/' . $this->assets[$tickerSymbol] . '_' . $this->quoteSymbols[$quoteSymbol] . '" target="_blank">
            ' . $tickerSymbol . ' / ' . $quoteSymbol . '
        </a>' . (!$this->isIncluded($tickerSymbol, $quoteSymbol) ? ('<br />' . trans('pegnet.price_not_reflected')) : '');
    }
}
