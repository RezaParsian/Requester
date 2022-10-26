<?php

function infoLog(string $data)
{
    $logFile = date("Y-m-d");
    $logTime = date("Y-m-d H:i:s");

    $file = fopen("./logs/" . $logFile . ".log", "a");
    fwrite($file, "[$logTime]: $data" . PHP_EOL);
    fclose($file);
}

function afterResponse(Closure $closure)
{
    set_time_limit(0);

    ob_start();

    echo json_encode([
        "status" => "success"
    ]);

    header("Content-Type: application/json");
    header('Connection: close');
    header('Content-Length: ' . ob_get_length());

    ob_end_flush();
    @ob_flush();
    flush();

    $closure();
}