<?php


namespace App\Services\Exchanges;

use App\Interfaces\ExchangeInterface;

/**
 * Class ExampleKeyExchange
 * @package App\Services\Exchanges
 */
class ExampleKeyExchange implements ExchangeInterface
{
    protected $apiKey;

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
        return ['test' => 'Key Test'];
    }

    public function getLink(string $tickerSymbol, string $quoteSymbol): string
    {
        // TODO: Implement getLink() method.
    }

    public function shouldUpdate(): bool
    {
        // TODO: Implement shouldUpdate() method.
    }
}
