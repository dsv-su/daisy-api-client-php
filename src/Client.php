<?php
namespace DsvSu\Daisy;

class Client
{
    private static $guzzle;

    public static function init(array $config)
    {
        static::$guzzle = new \GuzzleHttp\Client([
            'base_url' => $config['url'],
            'defaults' => [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'exceptions' => false,
                'auth' => [$config['user'], $config['pass']]
            ]
        ]);
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
                return $response->json();
            case 404:
                return null;
            default:
                throw new ServerException(
                    $response->getStatusCode() . " " . $response->getReasonPhrase()
                );
        }
    }
}
