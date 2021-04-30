<?php


namespace App\Services\Exchanges;

use App\Interfaces\ExchangeInterface;
use App\Traits\IsTradingPairIncluded;

/**
 * Class Idex
 * @package App\Services\Exchanges
 */
class Idex implements ExchangeInterface
{
    use IsTradingPairIncluded;

    /** @var string */
    protected $apiKey;

    /** @var array */
    protected $assets = [
        'PEG' => 'PEG',
        'pUSD' => 'PUSD',
        'pFCT' => 'PFCT',
        'pGOLD' => 'PXAU',
        'pKRW' => 'PKRW',

        'pBTC' => 'PXBT',
        'pETH' => 'PETH',
        'pSILVER' => 'PXAG',
        'pEUR' => 'PEUR',
        'pADA' => 'PADA',

        'pCNY' => 'PCNY',
        'pBCH' => 'PXBC',
        'pMXN' => 'PMXN',
        'pBRL' => 'PBRL',
        'pCHF' => 'PCHF',

        'pCAD' => 'PCAD',
        'pSGD' => 'PSGD',
        'pHKD' => 'PHKD',
        'pPHP' => 'PPHP',
        'pGBP' => 'PGBP',

        'pJPY' => 'PJPY',
        'pINR' => 'PINR',
        'pRVN' => 'PRVN',
        'pDCR' => 'PDCR',
        'pZEC' => 'PZEC',

//        'pBNB' => 'PBNB',
//        'pLTC' => 'PLTC',
//        'pXMR' => 'PXMR',
//        'pXLM' => 'PXLM',
//        'pDASH' => 'PDASH',
    ];

    /** @var array */
    protected $quoteSymbols = [
        'ETH' => 'ETH',
    ];

    /** @var array */
    protected $tradingPairs = [
        // 4th param is the volume symbol
        ['PEG', 'ETH', true, 'PEG'],
        ['pFCT', 'ETH', false, 'pFCT'],
        ['pUSD', 'ETH', false, 'pUSD'],
        ['pGOLD', 'ETH', false, 'pGOLD'],

        ['pBTC', 'ETH', false, 'pBTC'],
        ['pETH', 'ETH', false, 'pETH'],
        ['pSILVER', 'ETH', false, 'pSILVER'],
        ['pEUR', 'ETH', false, 'pEUR'],
        ['pADA', 'ETH', false, 'pADA'],

        ['pCNY', 'ETH', false, 'pCNY'],
        ['pBCH', 'ETH', false, 'pBCH'],
        ['pMXN', 'ETH', false, 'pMXN'],
        ['pBRL', 'ETH', false, 'pBRL'],
        ['pCHF', 'ETH', false, 'pCHF'],

        ['pCAD', 'ETH', false, 'pCAD'],
        ['pSGD', 'ETH', false, 'pSGD'],
        ['pHKD', 'ETH', false, 'pHKD'],
        ['pPHP', 'ETH', false, 'pPHP'],
        ['pGBP', 'ETH', false, 'pGBP'],

        ['pJPY', 'ETH', false, 'pJPY'],
        ['pINR', 'ETH', false, 'pINR'],
        ['pRVN', 'ETH', false, 'pRVN'],
        ['pDCR', 'ETH', false, 'pDCR'],
        ['pZEC', 'ETH', false, 'pZEC'],

//        ['pBNB', 'ETH', false, 'pBNB'],
//        ['pLTC', 'ETH', false, 'pLTC'],
//        ['pXMR', 'ETH', false, 'pXMR'],
//        ['pXLM', 'ETH', false, 'pXLM'],
//        ['pDASH', 'ETH', false, 'pDASH'],
    ];


    /**
     * @return bool
     */
    public function shouldUpdate(): bool
    {
        return true;
    }

    /**
     * ExampleKeyExchange constructor.
     * @param string $apiKey
     */
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }


    /**
     * @param array $pricingData
     * @return array
     */
    public function getData(array $pricingData): array
    {
        $retval = [
            'key' => 'idex',
            'data' => []
        ];

        $client = new \ccxt\idex([
            'apiKey' => $this->apiKey
        ]);

        $symbols = [];
        foreach ($this->tradingPairs as $tradingPair) {
            $symbols[] = $tradingPair[0] . '/' . $tradingPair[1];
        }

        // Get all ticker info
        $tickers = $client->fetch_tickers($symbols);

        foreach ($this->tradingPairs as $tradingPair) {
            $assetSymbol = $this->assets[$tradingPair[0]];
            $quoteSymbol = $this->quoteSymbols[$tradingPair[1]];

            if (!isset($tickers[implode('/', [$assetSymbol, $quoteSymbol])])) {
                continue;
            }

            // Now we know this must exist
            $marketInfo = $tickers[$assetSymbol . '/' . $quoteSymbol];

            $price = $marketInfo['info']['last'] ?? 0;
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
            if ($marketInfo['bid'] > 0.00 && $marketInfo['ask'] > 0.00) {
                $spread = (abs($marketInfo['ask'] - $marketInfo['bid']) / $marketInfo['bid']) * 100;
            }

            $retval['data'][$tradingPair[0]][$tradingPair[1]] = [
                'price' => $price,
                'volume' => (float)$marketInfo['quoteVolume'] ?? 0,
                'volume_symbol' => $tradingPair[3],
                'bid' => $marketInfo['bid'],
                'ask' => $marketInfo['ask'],
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

        return '<a href="https://idex.market/' . strtolower($this->quoteSymbols[$quoteSymbol]) . '/' . strtolower($this->assets[$tickerSymbol]) . '" target="_blank">
            ' . $tickerSymbol . ' / ' . $quoteSymbol . '
        </a> (ERC20)' . (!$this->isIncluded($tickerSymbol, $quoteSymbol) ? ('<br />' . trans('pegnet.price_not_reflected')) : '');
    }
}
