<?php


namespace App\Interfaces;


interface ExchangeInterface
{
    public function getData(array $pricingData): array;
    public function getLink(string $tickerSymbol, string $quoteSymbol): string;
    public function shouldUpdate(): bool;
}
