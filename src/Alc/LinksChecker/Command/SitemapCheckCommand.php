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
            ->addOption('output', 'o', InputOption::VALUE_OPTIONAL, 'Output results in file.')
	        ->addOption('level', 'l', InputOption::VALUE_OPTIONAL, 'Depth level.', 1)
	    ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    	$url = $input->getArgument('url');

        $output->writeln('<info>[INFO]</info> Crawling sitemap '.$url);

        $crawler = new SitemapCrawler();

        $sitemapUrls = $crawler->crawl($url);

    	$output->writeln('<info>[INFO]</info> '.count($sitemapUrls).' urls found');

        $csv = $input->getOption('output');

        if( $csv ) {

            // Create output file
            $csv = new CsvWriter($input->getOption('output'));
            $csv->setDelimiter("\t");
        }

        $checker = new UrlChecker();

        if( $input->getOption('level') == 2 ) {

            $visitedUrls = array();

            foreach( $sitemapUrls as $sitemapUrl ) {

                $sitemapUrl = trim($sitemapUrl);

                if(empty($sitemapUrl)) continue;

                $output->write('<comment>[GET][1]</comment> '.$sitemapUrl);

                $data = $checker->check($sitemapUrl);

                if( $data['statusCode'] == 200 ) {
                    $output->writeln("\t".'<info>'.$data['statusCode'].'</info>');
                }
                else {
                    $output->writeln("\t".'<error>'.$data['statusCode'].' '.$data['error'].'</error>');
                }

                // Write result in file
                if( $csv ) $csv->write($data);

                $visitedUrls[] = $sitemapUrl;

                $content = $checker->getCurlResponse()->getContent();

                $urls = $this->getUrlsFromPage( $content, $sitemapUrl );

                foreach( $urls as $url ) {

                    if(in_array($url, $visitedUrls) or in_array($url, $sitemapUrls)) continue;

                    $output->write('<comment>[GET][2]</comment> '.$url);

                    $data = $checker->check($url);

                    if( $data['statusCode'] == 200 ) {
                        $output->writeln("\t".'<info>'.$data['statusCode'].'</info>');
                    }
                    else {
                        $output->writeln("\t".'<error>'.$data['statusCode'].' '.$data['error'].'</error>');
                    }

                    // Write result in file
                    if( $csv ) $csv->write($data);

                    $visitedUrls[] = $url;
                }
            }
        }
        else {

            foreach( $sitemapUrls as $sitemapUrl ) {

                $url = trim($sitemapUrl);

                $output->write('<comment>[GET]</comment> '.$sitemapUrl);

                $data = $checker->check($sitemapUrl);

                if( $data['statusCode'] == 200 ) {
                    $output->writeln("\t".'<info>'.$data['statusCode'].'</info>');
                }
                else {
                    $output->writeln("\t".'<error>'.$data['statusCode'].' '.$data['error'].'</error>');
                }

                // Write result in file
                if( $csv ) $csv->write($data);
            }
        }

        if( $csv ) $csv->close();

        $output->writeln('<info>[INFO]</info> Job done!');
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
