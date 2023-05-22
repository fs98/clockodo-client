<?php

namespace Fs98\ClockodoClient\Services;

use Fs98\ClockodoClient\Interfaces\ClockodoApiInterface;
use Illuminate\Support\Facades\Http;

class ClockodoApiService implements ClockodoApiInterface
{
    protected $clockodoHeaders;

    protected $clockodoApiUrl;

    public function __construct(
        array $clockodoHeaders,
        string $clockodoApiUrl
    ) {
        $this->clockodoHeaders = $clockodoHeaders;
        $this->clockodoApiUrl = $clockodoApiUrl;
    }

    public function getRequestHeaders(): array
    {
        return $this->clockodoHeaders;
    }

    public function getApiUrl(string $endpoint): string
    {
        return rtrim($this->clockodoApiUrl, '/').'/'.ltrim($endpoint, '/');
    }

    public function performRequest(string $method, string $endpoint, array $data = []): array
    {
        return Http::withHeaders($this->getRequestHeaders())
            ->{$method}($this->getApiUrl($endpoint), $data)
            ->json();
    }

    public function performGetRequest(string $endpoint, array $data = []): array
    {
        return $this->performRequest('get', $endpoint, $data);
    }

    public function performPostRequest(string $endpoint, array $data = []): array
    {
        return $this->performRequest('post', $endpoint, $data);
    }

    public function performPutRequest(string $endpoint, array $data = []): array
    {
        return $this->performRequest('put', $endpoint, $data);
    }

    public function performDeleteRequest(string $endpoint, array $data = []): array
    {
        return $this->performRequest('delete', $endpoint, $data);
    }
}
