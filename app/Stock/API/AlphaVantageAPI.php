<?php

namespace App\Stock\API;

use Mockery\Exception;

class AlphaVantageAPI
{
    public const API_ENDPOINT = 'https://alphavantage.co/query';

    /**
     * Get the latest price for symbol
     *
     * @param string $symbol
     * @return float
     */
    public function getCurrentPriceForQuote(string $symbol): float
    {
        $endpoint = $this->getQuoteEndpoint($symbol);
        $payload = $this->getContents($endpoint);

        if (!isset($payload['Global Quote']) || !isset($payload['Global Quote']['05. price'])) {
            throw new Exception('Encountered unexpected payload');
        }

        $currentPrice =  $payload['Global Quote']['05. price'];

        if (!\is_numeric($currentPrice)) {
            throw new Exception("Error getting current price for quote {$symbol}");
        }

        return (float) $currentPrice;
    }

    /**
     * Get the quote endpoint URL
     *
     * @param string $symbol
     * @return string
     */
    public function getQuoteEndpoint(string $symbol): string
    {
        $queryStrings = [
            'symbol' => $symbol,
            'function' => 'GLOBAL_QUOTE',
            'apikey' => env('ALPHA_VANTAGE_KEY'),
        ];

        return $this->buildApiUrl($queryStrings);
    }

    /**
     * Build the URL to respect the query strings
     *
     * @param array $queryStrings
     * @return string
     */
    public function buildApiUrl(array $queryStrings): string
    {
        $queryString = http_build_query($queryStrings);

        return static::API_ENDPOINT . '?' . $queryString;
    }

    /**
     * Get the content of the endpoint
     *
     * @param string $endpoint
     * @return mixed
     */
    public function getContents(string $endpoint): mixed
    {
        $content = file_get_contents($endpoint);

        if (!$content) {
            throw new Exception('Error getting the content');
        }

        return json_decode($content,true);
    }
}