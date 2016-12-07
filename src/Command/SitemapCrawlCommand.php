<?php

namespace Alc\LinksChecker\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Alc\SitemapCrawler;

class SitemapCrawlCommand extends Command
{
    protected function configure()
    {
	    $this
	        ->setName('sitemap:crawl')
	        ->setDescription('Crawl and dump sitemap urls')
	        ->addArgument('url', InputArgument::REQUIRED, 'Sitemap.xml url.')
	    ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    	$url = $input->getArgument('url');

        $crawler = new SitemapCrawler();

        $sitemapUrls = $crawler->crawl($url);

        foreach( $sitemapUrls as $url ) {

            $output->writeln($url->getUrl());
        }   	

    }
}
