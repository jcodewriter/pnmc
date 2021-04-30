<?php


namespace App\Services\Exchanges;

use App\Interfaces\ExchangeInterface;
use App\Traits\IsTradingPairIncluded;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\FileCookieJar;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;

/**
 * Class Bilaxy
 * @package App\Services\Exchanges
 */
class Bilaxy implements ExchangeInterface
{
    use IsTradingPairIncluded;

    /** @var array */
    protected $assets = [
        'PEG' => 'PEG',
    ];

    /** @var array */
    protected $quoteSymbols = [
        'USDT' => 'USDT',
    ];

    /** @var array */
    protected $tradingPairs = [
        // 4th param is the symbol used for the API call
        // 5th param is the volume symbol
        ['PEG', 'USDT', true, 359, 'PEG'],
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
            'key' => 'bilaxy',
            'data' => []
        ];

        foreach ($this->tradingPairs as $tradingPair) {
            $url = 'https://api.bilaxy.com/v1/ticker?symbol=' . $tradingPair[3];

            $client = new Client([
                'cookies' => new FileCookieJar(tempnam('/tmp', __CLASS__)),
                'headers' => ['Referer' => $url],
            ]);

            /** @var HandlerStack $handler */
//            $handler = $client->getConfig('handler');
//            $handler->push(\GuzzleCloudflare\Middleware::create());

            try {
                // Query for the latest trade to get price
                $response = $client->get($url);
            } catch (RequestException $e) {
                $response = $e->getResponse();

                if (
                    ($response->getStatusCode() === 503 || $response->getStatusCode() === 521)
                    && strpos($response->getHeaderLine('Server'), 'cloudflare') !== false
                ) {
                    $retval['data'][$tradingPair[0]][$tradingPair[1]] = [
                        'price' => 0,
                        'volume' => 0,
                        'volume_symbol' => '',
                        'bid' => 0,
                        'ask' => 0,
                        'spread' => 0,
                        'included' => false
                    ];

                    continue;
                }

                throw $e;
            }

            $marketInfo = json_decode($response->getBody()
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
            if ($marketInfo['data']['buy'] > 0.00 && $marketInfo['data']['sell'] > 0.00) {
                $spread = (abs($marketInfo['data']['sell'] - $marketInfo['data']['buy']) / $marketInfo['data']['buy']) * 100;
            }

            $retval['data'][$tradingPair[0]][$tradingPair[1]] = [
                'price' => $price,
                'volume' => (float)($marketInfo['data']['vol'] ?? 0),
                'volume_symbol' => $tradingPair[4],
                'bid' => $marketInfo['data']['buy'],
                'ask' => $marketInfo['data']['sell'],
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
        if (!isset($this->assets[$tickerSymbol], $this->quoteSymbols[$quoteSymbol])) {
            return '';
        }

        return '<a href="https://bilaxy.com/trade/' . $this->assets[$tickerSymbol] . '_' . $this->quoteSymbols[$quoteSymbol] . '" target="_blank">
            ' . $tickerSymbol . ' / ' . $quoteSymbol . '
        </a>' . (!$this->isIncluded($tickerSymbol, $quoteSymbol) ? ('<br />' . trans('pegnet.price_not_reflected')) : '');
    }
}
