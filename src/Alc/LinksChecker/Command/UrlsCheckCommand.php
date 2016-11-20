<?php

namespace Alc\LinksChecker\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Alc\LinksChecker\Component\UrlChecker;
use Alc\Csv\CsvWriter;

class UrlsCheckCommand extends Command
{
    protected function configure()
    {
	    $this
	        ->setName('check')
	        ->setDescription('Urls checker')
	        ->addArgument('input', InputArgument::REQUIRED, 'The input file.')
	        ->addArgument('output', InputArgument::OPTIONAL, 'The output file.', 'php://output')
	    ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    	$filename = $input->getArgument('input');

    	$output->writeln('[INFO] Opening '.$filename);

        $file = file($filename);

        // Create output file
        $csv = new CsvWriter($input->getArgument('output'));
        $csv->setDelimiter("\t");

        $checker = new UrlChecker();

        foreach( $file as $url ) {

        	$url = trim($url);

            $output->writeln('[GET] '.$url);

            $data = $checker->check($url);

            // Write result in file
			$csv->write($data);
        }

        $csv->close();

        $output->writeln('[INFO] Job done!');
    }
}
