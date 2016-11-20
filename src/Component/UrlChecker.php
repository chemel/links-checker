<?php

namespace Alc\LinksChecker\Component;

use Alc\Curl\Curl;

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
}