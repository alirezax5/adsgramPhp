<?php

namespace alirezax5\adsgramPhp;

use GuzzleHttp\Client as GuzzleClient;

class adsgram
{
    public $url = 'https://api.adsgram.ai/';
    private $httpClient;

    public function __construct($httpClient = null)
    {
        $this->httpClient = $httpClient ?? new GuzzleClient(['verify' => false]);
    }

    public function request($type, $query = [], $body = [], $method = 'GET')
    {
        $options = [
            'headers' => ['User-Agent' => 'adsgramPhp script'],
        ];

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
                return 'HTTP error: ' . $response->getStatusCode();
            }

            $contents = $response->getBody()->getContents();

            // اگر JSON معتبر بود، برگردونیم
            $data = json_decode($contents, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $data;
            }

            // اگر متن ساده بود، همون متن رو برگردونیم
            return trim($contents);

        } catch (\Exception $e) {
            return 'Request failed: ' . $e->getMessage();
        }
    }

    public function advbot($blockid, $tgid, $language = null)
    {
        error_log(123);

        $req = $this->request('advbot', compact('blockid', 'tgid', 'language'));
        error_log(print_r($req,true));
        // اگر متن خاص تبلیغ نبود، متن رو نمایش بده
        if (is_string($req) && stripos($req, 'No available advertisement') !== false) {
            return false;
        }

        return $req;
    }
}
