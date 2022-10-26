<?php

namespace App\Http\Classes;

use GuzzleHttp\Psr7\{Response};

/**
 * @property string body
 * @property array json
 * @property object object
 * @property array headers
 * @property string effectiveUrl
 * @property string requestUrl
 * @property array header
 * @property array requestHeaders
 * @property array requestHeader
 * @property array requestParams
 * @property int statusCode
 */
final class ResponseData
{
    protected Client $client;
    protected Response $response;

    private string $body;

    public function __construct(Client $request, Response $response)
    {
        $this->client = $request;
        $this->response = $response;
        $this->body = $response->getBody();
        $response->getBody()->rewind();
    }

    public function getJson()
    {
        return json_decode($this->getBody(), true);
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getObject()
    {
        return json_decode($this->getBody());
    }

    public function getHeaders(): array
    {
        return $this->response->getHeaders();
    }

    public function getHeader(string $header): array
    {
        return $this->response->getHeader($header);
    }

    public function __get($name)
    {
        return $this->{"get" . ucfirst($name)}();
    }

    public function __toString()
    {
        return $this->body;
    }

    public function getRequestHeaders(): array
    {
        return $this->client->request->getHeaders();
    }

    public function getRequestHeader(string $header): array
    {
        return $this->client->getHeader($header);
    }

    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    public function success(): bool
    {
        return $this->response->getStatusCode() >= 200 and $this->response->getStatusCode() < 300;
    }

    public function getRequestParams(): array
    {
        return $this->client->options;
    }

    public function getRequestUrl(): string
    {
        return $this->client->request->getUri();
    }

    public function getEffectiveUrl(): string
    {
        return $this->client->stats->getEffectiveUri();
    }
}
