<?php

require __DIR__.'/vendor/autoload.php';

use Alc\Curl\Curl;

$file = file('urls.txt');

  echo implode("\t", array(
    'requestedUrl',
    'url',
    'success',
    'statusCode',
    'errorNo',
    'error',
  )), "\n";

foreach( $file as $url ) {

  $url = trim($url);

  $curl = new Curl();
  $response = $curl->get($url);

  echo implode("\t", array(
    $url,
    $response->getUrl(),
    $response->success() ? 1 : 0,
    $response->getStatusCode(),
    $response->getErrorNo(),
    $response->getError(),
  )), "\n";
}
