#!/usr/bin/php
<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Alc\LinksChecker\Command;

$application = new Application();

$application->add(new Command\FileCheckCommand());
$application->add(new Command\SitemapCheckCommand());
$application->add(new Command\GeneratorCommand());

$application->run();
