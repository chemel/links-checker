<?php

require __DIR__.'/vendor/autoload.php';

use Alc\LinksChecker\Controller\UrlsChecker;
use Alc\LinksChecker\Component\Verbose;

$controller = new UrlsChecker();

$verbose = new Verbose();

$controller->run($argv[1], $argv[2], $verbose);
