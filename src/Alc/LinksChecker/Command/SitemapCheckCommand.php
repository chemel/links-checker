<?php

namespace Alc\LinksChecker\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Alc\SitemapCrawler;
use Alc\LinksChecker\Component\UrlChecker;
use Alc\Csv\CsvWriter;

class SitemapCheckCommand extends Command
{
    protected function configure()
    {
	    $this
	        ->setName('check:sitemap')
	        ->setDescription('Check sitemap.xml urls')
	        ->addArgument('url', InputArgument::REQUIRED, 'Sitemap.xml url.')
	        ->addArgument('output', InputArgument::OPTIONAL, 'The output file.', 'php://output')
	    ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    	$url = $input->getArgument('url');

        $output->writeln('[INFO] Crawling sitemap '.$url);

        $crawler = new SitemapCrawler();

        $urls = $crawler->crawl($url);

    	$output->writeln('[INFO] '.count($urls).' urls found');

        // Create output file
        $csv = new CsvWriter($input->getArgument('output'));
        $csv->setDelimiter("\t");

        $checker = new UrlChecker();

        foreach( $urls as $url ) {

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
