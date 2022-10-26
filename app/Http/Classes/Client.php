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

        try {
            $response = parent::send($request, $options);
        } catch (ClientException | ServerException $exception) {
            $response = $exception->getResponse();
        } catch (Exception $exception) {
            infoLog(json_encode([
                "Client Exception: " => [
                    "Error" => $exception->getMessage(),
                    "Url" => $request->getUri(),
                    "Options" => $options,
                ]
            ]));
        }

        return $response;
    }
}
