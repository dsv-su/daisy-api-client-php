<?php
namespace DaisyApiClient;

class Client {
  function __construct($config) {
    $this->guzzle = new GuzzleHttp\Client([
      'base_url' => $config['url'],
      'defaults' => [
        'headers' => [
          'Accept' => 'application/json',
        ],
        'auth' => [$config['user'], $config['pass']]
      ]
    ]);
  }

  static function createUsingConfigFile($file) {
    $config = json_decode(file_get_contents($file), TRUE);
    return new static($config);
  }

  function get($url, $query = []) {
    $request = $this->guzzle->createRequest('GET', $url, ['query' => $query]);
    return $this->guzzle->send($request)->json();
  }

  function getSchedule($room_id, $days) {
    return $this->get('schedule', [
      'room'  => $room_id,
      'start' => date("Y-m-d", time()),
      'end'   => date("Y-m-d", strtotime("$days weekdays"))
    ]);
  }

  function getCourseSegmentInstance($id) {
    return $this->get("courseSegment/$id");
  }
}
