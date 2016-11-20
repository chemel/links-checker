<?php

namespace Alc\LinksChecker\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Alc\LinksChecker\Component\UrlChecker;
use Alc\SitemapCrawler;
use Alc\HtmlDomParserHelper;
use Alc\Csv\CsvWriter;

class SitemapCheckCommand extends Command
{
    protected function configure()
    {
	    $this
	        ->setName('check:sitemap')
	        ->setDescription('Check sitemap.xml urls')
	        ->addArgument('url', InputArgument::REQUIRED, 'Sitemap.xml url.')
            ->addOption('output', 'o', InputOption::VALUE_OPTIONAL, 'Output results in file.', 'php://output')
	        ->addOption('level', 'l', InputOption::VALUE_OPTIONAL, 'Depth level.', 1)
	    ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    	$url = $input->getArgument('url');

        $output->writeln('[INFO] Crawling sitemap '.$url);

        $crawler = new SitemapCrawler();

        $sitemapUrls = $crawler->crawl($url);

    	$output->writeln('[INFO] '.count($sitemapUrls).' urls found');

        // Create output file
        $csv = new CsvWriter($input->getOption('output'));
        $csv->setDelimiter("\t");

        $checker = new UrlChecker();

        if( $input->getOption('level') == 2 ) {

            $visitedUrls = array();

            foreach( $sitemapUrls as $sitemapUrl ) {

                $sitemapUrl = trim($sitemapUrl);

                if(empty($sitemapUrl)) continue;

                $output->writeln('[GET][1] '.$sitemapUrl);

                $data = $checker->check($sitemapUrl);

                // Write result in file
                $csv->write($data);

                $visitedUrls[] = $sitemapUrl;

                $content = $checker->getCurlResponse()->getContent();

                $urls = $this->getUrlsFromPage( $content, $sitemapUrl );

                foreach( $urls as $url ) {

                    if(in_array($url, $visitedUrls) or in_array($url, $sitemapUrls)) continue;

                    $output->writeln('[GET][2] '.$url);

                    $data = $checker->check($url);

                    // Write result in file
                    $csv->write($data);

                    $visitedUrls[] = $url;
                }
            }
        }
        else {

            foreach( $sitemapUrls as $sitemapUrl ) {

                $url = trim($sitemapUrl);

                $output->writeln('[GET] '.$sitemapUrl);

                $data = $checker->check($sitemapUrl);

                // Write result in file
                $csv->write($data);
            }
        }

        $csv->close();

        $output->writeln('[INFO] Job done!');
    }

    private function getUrlsFromPage($content, $url) {

        // Extract domain
        $domain = parse_url($url);
        $domain = $domain['scheme'].'://'.$domain['host'];

        $helper = new HtmlDomParserHelper();
        $parser = $helper->getHtmlDomParser($content);

        if(!$parser) return;

        $results = array();

        $nodes = $parser->find('a');

        if( !empty($nodes) ) {

            foreach( $nodes as $node ) {

                $results[] = $node->getAttribute('href');
            }
        }

        $nodes = $parser->find('img');

        if( !empty($nodes) ) {

            foreach( $nodes as $node ) {

                $results[] = $node->getAttribute('src');
            }
        }

        $helper->clear();

        foreach($results as $i => &$url) {

            $url = trim($url);

            if( ($pos = stripos($url, '#')) !== false )
                $url = substr($url, 0, $pos);

            // Absolutize url
            if(substr($url, 0, 1) == '/')
                $url = $domain.$url;

            if(empty($url)) unset($results[$i]);
        }

        return $results;
    }
}
