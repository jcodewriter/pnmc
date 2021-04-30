<?php


namespace App\Services\Exchanges;

use App\Interfaces\ExchangeInterface;
use App\Traits\IsTradingPairIncluded;
use Exception;
use GuzzleHttp\Client;
use UnexpectedValueException;

/**
 * Class Hotbit
 * @package App\Services\Exchanges
 */
class Hotbit implements ExchangeInterface
{
    use IsTradingPairIncluded;

    /** @var array */
    protected $assets = [
        'PEG' => 'PEG',
    ];

    /** @var array */
    protected $quoteSymbols = [
        'BTC' => 'BTC',
        'ETH' => 'ETH',
        'FCT' => 'FCT',
        'USDT' => 'USDT',
    ];

    /** @var array */
    protected $tradingPairs = [
        // 4th param is the volume symbol
        ['PEG', 'BTC', true, 'PEG'],
        ['PEG', 'ETH', true, 'PEG'],
        ['PEG', 'FCT', true, 'PEG'],
        ['PEG', 'USDT', true, 'PEG'],
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
     * @throws Exception
     */
    public function getData(array $pricingData): array
    {
        $retval = [
            'key' => 'hotbit',
            'data' => []
        ];

        $client = new Client();

        // Query for the latest trade to get price
        $response = $client->get('https://www.hotbit.io/public/market/status24h', [
            'query' => ['platform' => 'web']
        ]);
        $marketInfo = \GuzzleHttp\json_decode($response->getBody()
            ->getContents(), true);

        if (isset($marketInfo['Msg'])
            && $marketInfo['Msg'] !== 'success'
        )
        {
            throw new UnexpectedValueException('[Hotbit] ' . $marketInfo['msg']);
        }

        if (!isset($marketInfo['Content']))
        {
            throw new UnexpectedValueException('Hotbit returned incorrect data.');
        }

        foreach ($this->tradingPairs as $tradingPair) {

            foreach ($marketInfo['Content'] as $symbol => $info)
            {
                if ($symbol !== mb_strtoupper($this->assets[$tradingPair[0]] . $this->quoteSymbols[$tradingPair[1]])) {
                    continue;
                }

                $price = $info['last'] ?? 0;
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
//                if ($info['buy'] > 0.00 && $info['sell'] > 0.00) {
//                    $spread = (abs($info['sell'] - $info['buy']) / $info['buy']) * 100;
//                }

                $retval['data'][$tradingPair[0]][$tradingPair[1]] = [
                    'price' => $price,
                    'volume' => (float)($info['volume'] ?? 0),
                    'volume_symbol' => $tradingPair[3],
                    'bid' => $info['buy'] ?? 0,
                    'ask' => $info['sell'] ?? 0,
                    'spread' => $spread,
                    'included' => $tradingPair[2]
                ];
            }
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

        return '<a href="https://www.hotbit.io/exchange?symbol=' . $this->assets[$tickerSymbol] . '_' . $this->quoteSymbols[$quoteSymbol] . '" target="_blank">
            ' . $tickerSymbol . ' / ' . $quoteSymbol . '
        </a>' . (!$this->isIncluded($tickerSymbol, $quoteSymbol) ? ('<br />' . trans('pegnet.price_not_reflected')) : '');
    }
}
