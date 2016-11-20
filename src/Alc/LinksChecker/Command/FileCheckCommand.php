<?php

namespace Alc\LinksChecker\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Alc\LinksChecker\Component\UrlChecker;
use Alc\Csv\CsvWriter;

class FileCheckCommand extends Command
{
    protected function configure()
    {
	    $this
	        ->setName('check:file')
	        ->setDescription('Urls checker from file')
	        ->addArgument('input', InputArgument::REQUIRED, 'The input file.')
	        ->addOption('output', 'o', InputOption::VALUE_OPTIONAL, 'The output file.')
	    ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    	$filename = $input->getArgument('input');

    	$output->writeln('<info>[INFO]</info> Opening '.$filename);

        $file = file($filename);

        $csv = $input->getOption('output');

        if( $csv ) {

            // Create output file
            $csv = new CsvWriter($input->getOption('output'));
            $csv->setDelimiter("\t");
        }

        $checker = new UrlChecker();

        foreach( $file as $url ) {

        	$url = trim($url);

            $output->write('<comment>[GET]</comment> '.$url);

            $data = $checker->check($url);

            if( $data['statusCode'] == 200 ) {
                $output->writeln("\t".'<info>'.$data['statusCode'].'</info>');
            }
            else {
                $output->writeln("\t".'<error>'.$data['statusCode'].' '.$data['error'].'</error>');
            }
            

            // Write result in file
			if( $csv ) $csv->write($data);
        }

        if( $csv ) $csv->close();

        $output->writeln('<info>[INFO]</info> Job done!');
    }
}
