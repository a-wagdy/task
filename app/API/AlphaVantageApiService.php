<?php

namespace App\API;

use Exception;
use Illuminate\Support\Facades\Log;

class AlphaVantageApiService
{
    public const API_ENDPOINT = 'https://alphavantage.co/query';

    /**
     * Get the latest price for symbol
     *
     * @param string $symbol
     * @return ?float
     */
    public function fetchPriceForQuote(string $symbol): ?float
    {
        try {
            $endpoint = $this->getQuoteEndpoint($symbol);
            $payload = $this->getContents($endpoint);

            if (!isset($payload['Global Quote']) || !isset($payload['Global Quote']['05. price'])) {
                throw new Exception("Encountered unexpected payload for symbol {$symbol} " . json_encode($payload));
            }

            $currentPrice =  $payload['Global Quote']['05. price'];

            if (!\is_numeric($currentPrice)) {
                throw new Exception("Error getting current price for quote: {$symbol}, got: {$currentPrice}");
            }

            return (float) $currentPrice;
        } catch (Exception $exception) {
            Log::error("Failed to fetch price for symbol {$symbol}: {$exception->getMessage()}");
            return null;
        }
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
     * @return array
     * @throws Exception
     */
    public function getContents(string $endpoint): array
    {
        $content = file_get_contents($endpoint);

        if (!$content) {
            throw new Exception("Error getting the content for endpoint: {$endpoint}");
        }

        $decodedContent = json_decode($content, true);

        if (!\is_array($decodedContent)) {
            throw new Exception("Error decoding JSON content for endpoint: {$endpoint}");
        }

        return $decodedContent;
    }
}