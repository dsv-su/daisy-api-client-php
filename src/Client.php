<?php
namespace DsvSu\Daisy;

class Client {
  private static $guzzle;

  static function init(array $config) {
    static::$guzzle = new \GuzzleHttp\Client([
      'base_url' => $config['url'],
      'defaults' => [
        'headers' => [
          'Accept' => 'application/json',
        ],
        'exceptions' => FALSE,
        'auth' => [$config['user'], $config['pass']]
      ]
    ]);
  }

  static function initUsingConfigFile($file = 'daisy_api.json') {
    $config = json_decode(file_get_contents($file), TRUE);
    static::init($config);
  }

  static function get($path, array $query = []) {
    if (!isset(static::$guzzle)) {
      static::initUsingConfigFile();
    }
    $response = static::$guzzle->get($path, ['query' => $query]);
    switch ($response->getStatusCode()) {
      case 200:
        return $response->json();
      case 404:
        return NULL;
      default:
        throw new ServerException($response->getStatusCode() . " " . $response->getReasonPhrase());
    }
  }
}
