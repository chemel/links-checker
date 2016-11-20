<?php

namespace Alc\LinksChecker\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class GeneratorCommand extends Command
{
    protected function configure()
    {
	    $this
	        ->setName('url:generator')
	        ->addArgument('url', InputArgument::REQUIRED)
	    ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    	$url = $input->getArgument('url');

        preg_match("/{(\d+)\-(\d+)}/", $url, $match);

        if(!empty($match)) {

            for ($i=$match[1]; $i <= $match[2]; $i++) {

                $output->writeln( preg_replace('/{.*}/', $i, $url) );
            }
        }
    }
}
