<?php

namespace Alc\LinksChecker\Controller;

use Alc\Curl\Curl;
use Alc\LinksChecker\Component\OutputFile;

class UrlsChecker {

    public function run($filename, $ouputFilename, $verbose) {

        $file = file($filename);

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

        $curl = new Curl();

        foreach( $file as $url ) {

            $url = trim($url);

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
        }

        $verbose->writeln('Job done!');
    }
}