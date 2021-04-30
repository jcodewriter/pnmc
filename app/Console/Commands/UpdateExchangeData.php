<?php

namespace App\Console\Commands;

use App\Asset;
use App\Exchange;
use App\Services\Exchanges;
use Exception;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Console\Command;
use Illuminate\Contracts\Container\BindingResolutionException;
use Log;
use UnexpectedValueException;

class UpdateExchangeData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pegnet:update-exchange-data {--exchange=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the exchange prices of supported assets.';


    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws Exception
     */
    public function handle()
    {
        try
        {
            $data = app()->make(Exchanges::class, ['command' => $this])
                ->getDataFromExchanges($this->option('exchange'));

            foreach ($data as $tickerSymbol => $assetData) {
                /** @var Asset $asset */
                $asset = Asset::where('ticker_symbol', $tickerSymbol)->first();

                $asset->exchange_price = $assetData['displayPrice'];
                $asset->exchange_volume = $assetData['totalVolume'];
                $asset->exchange_price_updated_at = $asset->freshTimestampString();
                $asset->exchange_price_dateline = time();
                $asset->calculateExchangePriceChange();
                $asset->save();

                foreach ($assetData['exchangeData'] as $exchange => $exchangeData) {
                    foreach ($exchangeData as $quoteSymbol => $data) {
                        Exchange::updateOrCreate([
                            'ticker_symbol' => $tickerSymbol,
                            'quote_symbol' => $quoteSymbol,
                            'exchange' => $exchange,
                        ], [
                            'price' => $data['price'],
                            'volume' => $data['volume'],
                            'spread' => $data['spread'] ?? 0.00
                        ]);
                    }
                }
            }
        }
        catch (BindingResolutionException $e)
        {
            $message = sprintf('Error resolving exchange data classes: %s', $e->getMessage());
            Log::error($message);
            $this->error($message);
        }
        catch (RequestException $e)
        {
            $message = sprintf('Error updating pricing data from remote API: %s', $e->getMessage());
            Log::error($message);
            $this->error($message);
        }
        catch (UnexpectedValueException $e)
        {
            $message = sprintf('Unexpected value when updating pricing data from remote API: %s', $e->getMessage());
            Log::error($message);
            $this->error($message);
        }
        return true;
    }
}
