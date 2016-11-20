<?php

namespace Alc\LinksChecker\Component;

use Alc\Curl\Curl;
use Alc\HtmlDomParserHelper;

class UrlChecker {

    private $curl;
    private $response;

    public function getCurl() {

        if( $this->curl == null )
            $this->curl = new Curl();

        return $this->curl;
    }

    public function getCurlResponse() {

        return $this->response;
    }

    /**
     * Check
     *
     * @param string url
     *
     * @return array info
     */
    public function check($url) {

        $url = trim($url);

        $curl = $this->getCurl();

        $this->response = $response = $curl->get( $url );

        $info = array(
            'requestedUrl' => $url,
            'url' => $response->getUrl(),
            'success' => $response->success() ? 1 : 0,
            'statusCode' => $response->getStatusCode(),
            'errorNo' => $response->getErrorNo(),
            'error' => $response->getError(),
        );

        return $info;
    }

    /**
     * Check
     *
     * @param string url
     *
     * @return array info
     */
    public function checkSeo($url) {

        $url = trim($url);

        $helper = new HtmlDomParserHelper();
        $helper->parse($url);

        $response = $helper->getResponse();

        $info = array(
            'requestedUrl' => $url,
            'url' => $response->getUrl(),
            'success' => $response->success() ? 1 : 0,
            'statusCode' => $response->getStatusCode(),
            'errorNo' => $response->getErrorNo(),
            'error' => $response->getError(),
            'title' => $helper->getPageTitle(),
            'description' => $helper->getPageDescription(),
            'keywords' => $helper->getPageKeywords(),
            'canonical' => $helper->getPageCanonical(),
        );

        return $info;
    }
}