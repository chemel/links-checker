#!/usr/bin/php
<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Alc\LinksChecker\Command\UrlsCheckCommand;

$application = new Application();

$application->add(new UrlsCheckCommand());

$application->run();
