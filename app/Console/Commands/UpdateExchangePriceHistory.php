<?php

namespace App\Console\Commands;

use App\ExchangePriceHistory;
use Exception;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Console\Command;
use Illuminate\Contracts\Container\BindingResolutionException;
use Log;
use UnexpectedValueException;

class UpdateExchangePriceHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pegnet:update-exchange-price-history';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the exchange price history for supported assets.';


    /**
     * Execute the console command.
     * @throws Exception
     */
    public function handle(): void
    {
        try
        {
            $data = app()->make('ExchangeData')->getExchangePriceHistory();

            foreach ($data as $tickerSymbol => $assetData) {
                foreach ($assetData['exchangeData'] as $exchange => $exchangeData) {
                    foreach ($exchangeData as $quoteSymbol => $data) {
                        ExchangePriceHistory::firstOrCreate([
                            'ticker_symbol' => $tickerSymbol,
                            'quote_symbol' => $quoteSymbol,
                            'dateline' => time()
                        ], [
                            'price' => $assetData['displayPrice'],
                            'updated_at' => (new \DateTime())->setTimestamp(time())->format('Y-m-d H:i:s')
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
    }
}
