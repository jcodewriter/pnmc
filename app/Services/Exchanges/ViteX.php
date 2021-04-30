<?php


namespace App\Services\Exchanges;

use App\Interfaces\ExchangeInterface;
use App\Traits\IsTradingPairIncluded;
use GuzzleHttp\Client;

/**
 * Class ViteX
 * @package App\Services\Exchanges
 */
class ViteX implements ExchangeInterface
{
    use IsTradingPairIncluded;

    /** @var array */
    protected $assets = [
        'PEG' => 'PEG-000',
        'pFCT' => 'PFCT-001',
        'pUSD' => 'PUSD-000',
        'pGOLD' => 'PGOLD-001',
    ];

    /** @var array */
    protected $quoteSymbols = [
        'BTC' => 'BTC-000',
        'ETH' => 'ETH-000',
        'USDT' => 'USDT-000',
        'VITE' => 'VITE',
    ];

    /** @var array */
    protected $tradingPairs = [
        // 4th param is the volume symbol
        ['PEG', 'BTC', true, 'BTC'],
        ['PEG', 'ETH', true, 'ETH'],

        ['pFCT', 'BTC', true, 'BTC'],
        ['pFCT', 'ETH', true, 'ETH'],

        ['pUSD', 'BTC', true, 'BTC'],
        ['pUSD', 'ETH', true, 'ETH'],
        ['pUSD', 'USDT', true, 'USDT'],
        ['pUSD', 'VITE', true, 'VITE'],

        ['pGOLD', 'ETH', true, 'ETH'],
        ['pGOLD', 'USDT', true, 'USDT'],
        ['pGOLD', 'VITE', true, 'VITE'],
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
            'key' => 'vitex',
            'data' => []
        ];

        $client = new Client();

        foreach ($this->tradingPairs as $tradingPair) {
            // Query for the latest trade to get price
            $response = $client->get('https://vitex.vite.net/api/v1/trades?symbol=' . $this->assets[$tradingPair[0]] . '_' . $this->quoteSymbols[$tradingPair[1]] . '&limit=1');
            $latestTrade = \GuzzleHttp\json_decode($response->getBody()
                ->getContents(), true);

            // Query for the 24hr volume
            $response = $client->get('https://vitex.vite.net/api/v1/ticker/24hr?symbols=' . $this->assets[$tradingPair[0]] . '_' . $this->quoteSymbols[$tradingPair[1]] . '');
            $volume24hr = \GuzzleHttp\json_decode($response->getBody()
                ->getContents(), true);

            $response = $client->get('https://vitex.vite.net/api/v1/ticker/bookTicker?symbol=' . $this->assets[$tradingPair[0]] . '_' . $this->quoteSymbols[$tradingPair[1]] . '&limit=1');
            $spreadData = \GuzzleHttp\json_decode($response->getBody()
                ->getContents(), true);

            $price = $latestTrade['data']['trade'][0]['price'] ?? 0;
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
            if ($spreadData['data']['bidPrice'] > 0.00 && $spreadData['data']['askPrice'] > 0.00) {
                $spread = (abs($spreadData['data']['askPrice'] - $spreadData['data']['bidPrice']) / $spreadData['data']['bidPrice']) * 100;
            }

            $retval['data'][$tradingPair[0]][$tradingPair[1]] = [
                'price' => $price,
                'volume' => (float)($volume24hr['data'][0]['quantity'] ?? 0),
                'volume_symbol' => $tradingPair[3],
                'bid' => $spreadData['data']['bidPrice'],
                'ask' => $spreadData['data']['askPrice'],
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

        return '<a href="https://x.vite.net/trade?symbol=' . $this->assets[$tickerSymbol] . '_' . $this->quoteSymbols[$quoteSymbol] . '&category=' . $quoteSymbol . '" target="_blank">
            ' . $tickerSymbol . ' / ' . $quoteSymbol . '
        </a>' . (!$this->isIncluded($tickerSymbol, $quoteSymbol) ? ('<br />' . trans('pegnet.price_not_reflected')) : '');
    }
}
