<?php


namespace App\Services\Exchanges;

use App\Interfaces\ExchangeInterface;

/**
 * Class ExampleSecretExchange
 * @package App\Services\Exchanges
 */
class ExampleSecretExchange implements ExchangeInterface
{
    protected $clientId;
    protected $clientSecret;

    /**
     * ExampleSecretExchange constructor.
     * @param string $clientId
     * @param string $clientSecret
     */
    public function __construct(string $clientId, $clientSecret)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }


    /**
     * @param array $pricingData
     * @return array
     */
    public function getData(array $pricingData): array
    {
        return ['test' => 'Secret Test'];
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
