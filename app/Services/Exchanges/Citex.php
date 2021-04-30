<?php


namespace App\Services\Exchanges;

use App\ExchangeVolumeHistory;
use App\Interfaces\ExchangeInterface;
use App\Traits\IsTradingPairIncluded;
use DateTime;
use Exception;
use GuzzleHttp\Client;
use UnexpectedValueException;

/**
 * Class Citex
 * @package App\Services\Exchanges
 */
class Citex implements ExchangeInterface
{
    use IsTradingPairIncluded;

    /** @var array */
    protected $assets = [
        'PEG' => 'PEG',
    ];

    /** @var array */
    protected $quoteSymbols = [
        'BTC' => 'BTC',
        'USDT' => 'USDT',
    ];

    /** @var array */
    protected $tradingPairs = [
        // 4th param is the volume symbol
        ['PEG', 'BTC', false, 'PEG'],
        ['PEG', 'USDT', false, 'PEG'],
    ];


    /**
     * @return bool
     */
    public function shouldUpdate(): bool
    {
        if (!app()->environment('production', 'local'))
        {
            sleep(15);
//            return false;
        }

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
            'key' => 'citex',
            'data' => []
        ];

        $client = new Client();

        // Query for the latest trade to get price
        $response = $client->get('https://api.citex.co.kr/v1/alltickerss');
        $marketInfo = \GuzzleHttp\json_decode($response->getBody()
            ->getContents(), true);

        if (isset($marketInfo['success'], $marketInfo['msg']) && !$marketInfo['success'])
        {
            throw new UnexpectedValueException('[Citex] ' . $marketInfo['msg']);
        }

        if (!isset($marketInfo['ticker']))
        {
            throw new UnexpectedValueException('Citex returned incorrect data.');
        }

        foreach ($this->tradingPairs as $tradingPair) {

            foreach ($marketInfo['ticker'] as $info)
            {
                if ($info['symbol'] !== mb_strtolower($this->assets[$tradingPair[0]] . '_' . $this->quoteSymbols[$tradingPair[1]])) {
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

                $currentVolume = (float)($info['vol'] ?? 0);

                /** @var ExchangeVolumeHistory|null $result */
                $volume24hAgo = ExchangeVolumeHistory::where('dateline', '<=', time() - 86400)
                    ->where('ticker_symbol', $tradingPair[0])
                    ->where('quote_symbol', $tradingPair[1])
                    ->where('exchange', 'citex')
                    ->orderBy('dateline', 'DESC')
                    ->limit(1)
                    ->value('volume')
                ;
                if ($volume24hAgo)
                {
                    if (date('G') >= 16)
                    {
                        $closingTime = strtotime('today 16:00:00 UTC');
                    }
                    else
                    {
                        $closingTime = strtotime('yesterday 16:00:00 UTC');
                    }

                    $yesterdaysVolume = ExchangeVolumeHistory::where('dateline', '<', $closingTime)
                        ->where('ticker_symbol', $tradingPair[0])
                        ->where('quote_symbol', $tradingPair[1])
                        ->where('exchange', 'citex')
                        ->orderBy('dateline', 'DESC')
                        ->limit(1)
                        ->value('volume')
                    ;
                    if ($yesterdaysVolume)
                    {
                        // We have both volume 24h ago and yesterday's volume
                        $volume = $currentVolume + ($yesterdaysVolume - $volume24hAgo);
                    }
                    else
                    {
                        // We don't have yesterday's volume
                        $volume = $currentVolume;
                    }
                }
                else
                {
                    // We don't have volume 24h ago
                    $volume = $currentVolume;
                }

                $spread = 0.00;
                if ($info['buy'] > 0.00 && $info['sell'] > 0.00) {
                    $spread = (abs($info['sell'] - $info['buy']) / $info['buy']) * 100;
                }

                $retval['data'][$tradingPair[0]][$tradingPair[1]] = [
                    'price' => $price,
                    'volume' => $volume,
                    'volume_symbol' => $tradingPair[3],
                    'bid' => $info['buy'],
                    'ask' => $info['sell'],
                    'spread' => $spread,
                    'included' => $tradingPair[2]
                ];

                ExchangeVolumeHistory::updateOrCreate([
                    'ticker_symbol' => $tradingPair[0],
                    'quote_symbol' => $tradingPair[1],
                    'exchange' => 'citex',
                    'dateline' => time()
                ], [
                    'volume' => $currentVolume,
                    'updated_at' => (new DateTime())->setTimestamp(time())->format('Y-m-d H:i:s')
                ]);
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

        return '<a href="https://trade.citex.co.kr/trade/' . $this->assets[$tickerSymbol] . '_' . $this->quoteSymbols[$quoteSymbol] . '" target="_blank">
            ' . $tickerSymbol . ' / ' . $quoteSymbol . '
        </a>' . (!$this->isIncluded($tickerSymbol, $quoteSymbol) ? ('<br />' . trans('pegnet.price_not_reflected')) : '');
    }
}
