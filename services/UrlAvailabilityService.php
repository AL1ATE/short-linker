<?php

namespace app\services;

class UrlAvailabilityService
{
    private const CONNECT_TIMEOUT = 5;
    private const REQUEST_TIMEOUT = 10;
    private const MAX_REDIRECTS = 5;
    private const USER_AGENT = 'ShortLinkerBot/1.0';

    public function isAvailable(string $url): bool
    {
        $headStatusCode = $this->sendRequest($url, true);

        if ($this->isSuccessfulStatusCode($headStatusCode)) {
            return true;
        }

        $getStatusCode = $this->sendRequest($url, false);

        return $this->isSuccessfulStatusCode($getStatusCode);
    }

    private function sendRequest(string $url, bool $headRequest): int
    {
        $curl = curl_init($url);

        if ($curl === false) {
            return 0;
        }

        curl_setopt_array($curl, $this->buildCurlOptions($headRequest));
        curl_exec($curl);

        if (curl_errno($curl) !== 0) {
            curl_close($curl);
            return 0;
        }

        $statusCode = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return $statusCode;
    }

    private function buildCurlOptions(bool $headRequest): array
    {
        return [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_NOBODY => $headRequest,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => self::MAX_REDIRECTS,
            CURLOPT_CONNECTTIMEOUT => self::CONNECT_TIMEOUT,
            CURLOPT_TIMEOUT => self::REQUEST_TIMEOUT,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_USERAGENT => self::USER_AGENT,
        ];
    }

    private function isSuccessfulStatusCode(int $statusCode): bool
    {
        return $statusCode >= 200 && $statusCode < 400;
    }
}