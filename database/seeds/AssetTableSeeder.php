<?php

use Illuminate\Database\Seeder;
use GuzzleHttp\Exception\RequestException;

class AssetTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try
        {
            ApiHelper::updateAssetList();
        }
        catch (RequestException $e)
        {
            abort(500, sprintf('Error loading assets: %s', $e->getMessage()));
        }
    }
}
