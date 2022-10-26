<?php

namespace App\Http\Classes;

use Closure;
use Exception;
use GuzzleHttp\Exception\{ClientException, GuzzleException, ServerException};
use GuzzleHttp\TransferStats;
use Psr\Http\Message\{RequestInterface, ResponseInterface};

/**
 * @method getHeader(string $header)
 * @method getHeaders()
 */
class Client extends \GuzzleHttp\Client
{
    public array $options;
    public RequestInterface $request;
    public TransferStats $stats;

    protected ?Closure $beforeRequest = null;
    protected ?Closure $afterRequest = null;

    /**
     * @param Closure|null $beforeRequest
     */
    public function setBeforeRequest(?Closure $beforeRequest): void
    {
        $this->beforeRequest = $beforeRequest;
    }

    /**
     * @param Closure|null $afterRequest
     */
    public function setAfterRequest(?Closure $afterRequest): void
    {
        $this->afterRequest = $afterRequest;
    }

    /**
     * @param RequestInterface $request
     * @param array $options
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function send(RequestInterface $request, array $options = []): ResponseInterface
    {
        $response = new \GuzzleHttp\Psr7\Response(408);

        $options["on_stats"] = function (TransferStats $stats) {
            $this->stats = $stats;
        };

        $this->options = $options;
        $this->request = $request;

        if ($this->beforeRequest)
            ($this->beforeRequest)($request, $options);

        try {
            $response = parent::send($request, $options);
        } catch (ClientException | ServerException $exception) {
            $response = $exception->getResponse();
        } catch (Exception $exception) {
//            Log::error("Client Exception: ", [
//                "Error" => $exception->getMessage(),
//                "Url" => $request->getUri(),
//                "Options" => $options,
//            ]);
        }

        if ($this->afterRequest)
            ($this->afterRequest)($request, $response, $options);

        return $response;
    }
}
