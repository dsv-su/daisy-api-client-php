<?php
namespace DsvSu\Daisy;

class Resource {
  function __construct(array $data) {
    $this->data = $data;
  }

  function getData() {
    return $this->data;
  }

  function getId() {
    return $this->data['id'];
  }

  function get($key) {
    return isset($this->data[$key]) ? $this->data[$key] : NULL;
  }

  static function parseDateTime($ts) {
    $dt = new \DateTime();
    $dt->setTimestamp(intval($ts / 1000));
    return $dt;
  }
}
