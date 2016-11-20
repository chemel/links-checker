<?php

namespace Alc\LinksChecker\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Alc\LinksChecker\Component\UrlChecker;
use Alc\Csv\CsvWriter;

class SeoCheckCommand extends Command
{
    protected function configure()
    {
	    $this
	        ->setName('check:seo')
	        ->setDescription('Urls checker from file')
	        ->addArgument('input', InputArgument::REQUIRED, 'The input file.')
	        ->addArgument('output', InputArgument::REQUIRED, 'The output file.')
	    ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    	$filename = $input->getArgument('input');

    	$output->writeln('<info>[INFO]</info> Opening '.$filename);

        $file = file($filename);

        // Create output file
        $csv = new CsvWriter($input->getArgument('output'));

        $checker = new UrlChecker();

        foreach( $file as $url ) {

        	$url = trim($url);

            $output->write('<comment>[GET]</comment> '.$url);

            $data = $checker->checkSeo($url);

            if( $data['statusCode'] == 200 ) {
                $output->writeln("\t".'<info>'.$data['statusCode'].'</info>');
            }
            else {
                $output->writeln("\t".'<error>'.$data['statusCode'].' '.$data['error'].'</error>');
            }
            

            // Write result in file
			$csv->write($data);
        }

        $csv->close();

        $output->writeln('<info>[INFO]</info> Job done!');
    }
}
