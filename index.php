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

    $client = new Client();

    $req = new Request($request->method, $request->url);

    $res = $client->send($req, json_decode(json_encode($request->options), true));

    $req = new Request("POST", $request->callback);

    $client->send($req, [
        "json" => [
            "data" => serialize(new ResponseData($res))
        ],
    ]);
});