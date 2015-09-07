<?php
namespace DsvSu\Daisy;

use GuzzleHttp\Psr7;

class Client
{
    private static $guzzle;
    private static $daisyBaseUrl;

    public static function init(array $config)
    {
        self::$guzzle = new \GuzzleHttp\Client(
            [
                'base_uri' => $config['url'],
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'http_errors' => false,
                'auth' => [$config['user'], $config['pass']]
            ]
        );
        self::$daisyBaseUrl = isset($config['daisy_base_url']) ?
                              $config['daisy_base_url'] :
                              'https://daisy.dsv.su.se';
    }

    public static function initUsingConfigFile($file = 'daisy_api.json')
    {
        $config = json_decode(file_get_contents($file), true);
        self::init($config);
    }

    public static function get($path, $query = [])
    {
        if (!isset(self::$guzzle)) {
            self::initUsingConfigFile();
        }
        if (is_array($query)) {
            $query = http_build_query($query, null, '&', PHP_QUERY_RFC3986);
            $query = preg_replace('/%5[bB]\d+%5[dD]=/', '=', $query);
        }

        $response = self::$guzzle->get($path, ['query' => $query]);
        switch ($response->getStatusCode()) {
            case 200:
                return json_decode($response->getBody(), true);
            case 404:
                return null;
            default:
                $uri = Psr7\Uri::resolve(
                    Psr7\uri_for(self::$guzzle->getConfig('base_uri')),
                    $path
                );
                $uri = $uri->withQuery($query);
                throw new ServerException(
                    $response->getStatusCode()
                    . " " . $response->getReasonPhrase()
                    . ". URI: " . $uri
                );
        }
    }

    public static function getGuzzle()
    {
        return self::$guzzle;
    }

    public static function getDaisyBaseUrl()
    {
        return self::$daisyBaseUrl;
    }
}
