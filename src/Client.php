<?php
namespace DsvSu\Daisy;

class Client
{
    private static $guzzle;
    private static $daisyBaseUrl;

    public static function init(array $config)
    {
        static::$guzzle = new \GuzzleHttp\Client(
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
        static::init($config);
    }

    public static function get($path, array $query = [])
    {
        if (!isset(static::$guzzle)) {
            static::initUsingConfigFile();
        }
        $response = static::$guzzle->get($path, ['query' => $query]);
        switch ($response->getStatusCode()) {
            case 200:
                return json_decode($response->getBody(), true);
            case 404:
                return null;
            default:
                throw new ServerException(
                    $response->getStatusCode() . " " . $response->getReasonPhrase()
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
