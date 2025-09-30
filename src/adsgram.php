<?php

namespace alirezax5\adsgramPhp;

use GuzzleHttp\Client as GuzzleClient;

class adsgram
{
    public $url = 'https://api.adsgram.ai/';
    private $httpClient;

    public function __construct()
    {
        $this->httpClient = $httpClient ?? new GuzzleClient(['verify' => false]);
    }

    public function request($type, $query = [], $body = [], $method = 'GET')
    {
        $options = [
            'headers' => ['User-Agent' => 'adsgramPhp script'],
        ];

        // Build URL with query parameters if they exist
        $requestUrl = $this->url . $type;
        if (!empty($query)) {
            $requestUrl .= '?' . http_build_query($query);
        }

        if ($method === 'POST') {
            $options['form_params'] = $body;
        }

        try {
            $response = $this->httpClient->request($method, $requestUrl, $options);
            if ($response->getStatusCode() !== 200) {
                throw new \Exception('HTTP error: ' . $response->getStatusCode());
            }
            $data = json_decode($response->getBody()->getContents(), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('JSON decode error: ' . json_last_error_msg());
            }
            return $data;
        } catch (\Throwable $e) {
            throw new \Exception('Request failed: ' . $e->getMessage(), 0, $e);
        }
    }

    public function advbot($blockid, $tgid, $language = null)
    {
        $req = $this->request('advbot', compact('blockid', 'tgid', 'language'));
        if ($req == 'No available advertisement at the moment, try again later!')
            return false;

        return $req;
    }
}