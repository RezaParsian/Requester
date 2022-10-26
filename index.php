<?php

use App\Http\Classes\{Client, ResponseData};
use GuzzleHttp\Psr7\Request;

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    echo json_encode([
        "status" => "alive"
    ]);

    die();
};


afterResponse(function () {
    $request = json_decode(file_get_contents("php://input"));

    $client = new Client(json_decode(json_encode($request->client), true));

    $req = new Request($request->method, $request->url);

    $res = $client->send($req, json_decode(json_encode($request->options), true));

    $response = new ResponseData($res);

//    send response to callback

    $callBackClient = new Client([
        "timeout" => 60,
        "verify" => false
    ]);

    $req1 = new Request("POST", $request->callback);

    $callBackClient->send($req1, [
        "json" => [
            "data" => serialize($response)
        ],
    ]);
});