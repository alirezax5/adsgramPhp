<?php

namespace alirezax5\adsgramPhp;

use GuzzleHttp\Client as GuzzleClient;

class adsgram
{
    public $url = 'https://api.adsgram.ai/';
    private $httpClient;
    private $token;
    public function __construct($httpClient = null ,$token )
    {
        $this->token = $token;
        $this->httpClient = $httpClient ?? new GuzzleClient(['verify' => false]);
    }

    public function request($type, $query = [], $body = [], $method = 'GET')
    {
        $options = [
            'headers' => ['User-Agent' => 'adsgramPhp script'],
        ];

        $requestUrl = $this->url . $type;
        $query['token'] = $this->token;

        if (!empty($query)) {
            $requestUrl .= '?' . http_build_query($query);
        }
        ;
        if ($method === 'POST') {
            $options['form_params'] = $body;
        }

        try {
            $response = $this->httpClient->request($method, $requestUrl, $options);

            if ($response->getStatusCode() !== 200) {

                return 'HTTP error: ' . $response->getStatusCode();
            }

            $contents = $response->getBody()->getContents();

            $data = json_decode($contents, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $data;
            }

            return trim($contents);

        } catch (\Exception $e) {
            return 'Request failed: ' . $e->getMessage();
        }
    }

    public function advbot($blockid, $tgid, $language = null)
    {

        $req = $this->request('advbot', compact('blockid', 'tgid', 'language'));
        print_r($req);
        if (is_string($req) && stripos($req, 'No available advertisement') !== false) {
            return false;
        }
        if (is_string($req) && stripos($req, 'No available advertisement') !== false) {
            return false;
        }
        return $req;
    }
}
