<?php

namespace Alc\LinksChecker\Component;

use Alc\Curl\Curl;

class UrlChecker {

    /**
     * Check
     *
     * @param string url
     * @return array info
     */
    public function check($url) {

        $url = trim($url);

        $curl = new Curl();

        $response = $curl->get($url);

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