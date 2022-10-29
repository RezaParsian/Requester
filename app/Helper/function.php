<?php
header("Content-Type: application/json");

function infoLog(string $data)
{
    $logFile = date("Y-m-d");
    $logTime = date("Y-m-d H:i:s");

    $file = fopen("./logs/" . $logFile . ".log", "a");
    fwrite($file, "[$logTime]: $data" . PHP_EOL);
    fclose($file);
}
