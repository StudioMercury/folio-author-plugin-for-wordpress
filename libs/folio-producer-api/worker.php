<?php
require 'vendor/autoload.php';

$command = null;
$config = null;
$options = null;

if (isset($argv) && count($argv) >= 3) {
    $command = $argv[1];
    $config = $argv[2];
    if (count($argv) >= 4) {
        $options = $argv[3];
    }
} else {
    throw new Exception('Command takes three arguments: command_name, options_json, config_json');
    exit;
}

$config = json_decode($config, true);
if ($config === null && json_last_error() !== JSON_ERROR_NONE) {
    throw new Exception('Config parse error: '.json_last_error());
    exit;
}

if (!is_null($options)) {
    $options = json_decode($options);
    if ($options === null && json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Options parse error: '.json_last_error());
        exit;
    }
}

$client = new DPSFolioProducer\Client($config);
$request = $client->execute($command, $options);
echo serialize($request);
