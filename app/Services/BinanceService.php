<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BinanceService
{
    protected $client;
    protected $apiUrl = 'https://api.binance.com/api/v3';
    protected $cacheTime = 60; // 1 minute cache

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->apiUrl,
            'timeout' => 10,
        ]);
    }

    /**
     * Get current price for a cryptocurrency pair
     */
    public function getPrice($symbol)
    {
        try {
            $cacheKey = "crypto_price_{$symbol}";
            
            return Cache::remember($cacheKey, $this->cacheTime, function () use ($symbol) {
                $response = $this->client->get('/ticker/price', [
                    'query' => ['symbol' => $symbol]
                ]);

                $data = json_decode($response->getBody(), true);
                return $data['price'] ?? null;
            });
        } catch (\Exception $e) {
            Log::error('Binance API Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get multiple prices at once
     */
    public function getPrices($symbols = [])
    {
        try {
            $cacheKey = 'crypto_prices_all';
            
            return Cache::remember($cacheKey, $this->cacheTime, function () use ($symbols) {
                $response = $this->client->get('/ticker/price');
                $data = json_decode($response->getBody(), true);
                
                $prices = [];
                foreach ($data as $item) {
                    if (empty($symbols) || in_array($item['symbol'], $symbols)) {
                        $prices[$item['symbol']] = $item['price'];
                    }
                }
                
                return $prices;
            });
        } catch (\Exception $e) {
            Log::error('Binance API Error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get 24h price statistics
     */
    public function get24hStats($symbol)
    {
        try {
            $response = $this->client->get('/ticker/24hr', [
                'query' => ['symbol' => $symbol]
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('Binance API Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get klines (OHLC) data
     */
    public function getKlines($symbol, $interval = '1h', $limit = 100)
    {
        try {
            $response = $this->client->get('/klines', [
                'query' => [
                    'symbol' => $symbol,
                    'interval' => $interval,
                    'limit' => $limit
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('Binance API Error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Validate cryptocurrency symbol
     */
    public function validateSymbol($symbol)
    {
        try {
            $price = $this->getPrice($symbol);
            return !is_null($price);
        } catch (\Exception $e) {
            return false;
        }
    }
}
