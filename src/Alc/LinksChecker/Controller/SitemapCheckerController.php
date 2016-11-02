<?php

namespace Alc\LinksChecker\Controller;

use Alc\HtmlDomParserHelper;
use Alc\CUrl\Curl;
use Alc\LinksChecker\Component\OutputFile;

class SitemapCheckerController {

	public function run($sitemapUrl, $ouputFilename, $verbose) {

		$verbose->write('Collecting urls from sitemap...');

		$sitemapLocs = $this->getUrlsFromSitemap($sitemapUrl);

		// Collecting urls from sitemap
		$sitemapUrls = array();

		foreach($sitemapLocs as $sitemapLoc) {

			$sitemapUrls = $this->getUrlsFromSitemap($sitemapLoc, $sitemapUrls);
		}

		$verbose->writeln(count($sitemapUrls).' founds!');

		// Create output file
		$outputFile = new OutputFile($ouputFilename);

		// Write header
		$outputFile->append(array(
		    'requestedUrl',
		    'url',
		    'success',
		    'statusCode',
		    'errorNo',
		    'error',
		  ));

		$visitedUrls = array();

		// Check links for each pages
		foreach( $sitemapUrls as $sitemapUrl ) {

			$verbose->writeln('Checking: '.$sitemapUrl);

			$urls = $this->getUrlsFromPage($sitemapUrl);

			$curl = new Curl();

			foreach($urls as $url) {

			  $url = trim($url);

			  // Skip already visited urls
			  if(in_array($url, $visitedUrls))
			  	continue;

			  $verbose->writeln('Get: '.$url);

			  $response = $curl->get($url);

			  // Write result in file
			  $outputFile->append(array(
			    $url,
			    $response->getUrl(),
			    $response->success() ? 1 : 0,
			    $response->getStatusCode(),
			    $response->getErrorNo(),
			    $response->getError(),
			  ));

			  $visitedUrls[] = $url;
			}
		}

		$verbose->writeln('Job done!');
	}

	private function getUrlsFromSitemap($sitemapUrl, $results=array()) {

		$helper = new HtmlDomParserHelper();
		$parser = $helper->parse($sitemapUrl);

		$nodes = $parser->find('loc');

		if( !empty($nodes) ) {

			foreach( $nodes as $node ) {

				$results[] = $node->innertext;
			}
		}

		$helper->clear();

		return $results;
	}

	private function getUrlsFromPage($url) {

		$helper = new HtmlDomParserHelper();
		$parser = $helper->parse($url);

		$results = array();

		$nodes = $parser->find('a');

		if( !empty($nodes) ) {

			foreach( $nodes as $node ) {

				$results[] = $node->getAttribute('href');
			}
		}

		$helper->clear();

		return $results;
	}
}