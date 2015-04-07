<?php
namespace DsvSu\Daisy;

class Client {
  private static $guzzle;

  static function init($config) {
    static::$guzzle = new GuzzleHttp\Client([
      'base_url' => $config['url'],
      'defaults' => [
        'headers' => [
          'Accept' => 'application/json',
        ],
        'auth' => [$config['user'], $config['pass']]
      ]
    ]);
  }

  static function initUsingConfigFile($file = 'daisy_api_config.json') {
    $config = json_decode(file_get_contents($file), TRUE);
    init($config);
  }

  static function get($path, $query = []) {
    if (!isset(static::$guzzle)) {
      initUsingConfigFile();
    }
    return static::$guzzle->get($path, ['query' => $query])->json();
  }
}
