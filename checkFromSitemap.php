<?php

require __DIR__.'/vendor/autoload.php';

use Alc\LinksChecker\Controller\SitemapCheckerController;
use Alc\LinksChecker\Component\Verbose;

$controller = new SitemapCheckerController();

$verbose = new Verbose();

$controller->run($argv[1], $argv[2], $verbose);
