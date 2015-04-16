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

  static function parseDateTime($ts) {
    return \DateTime::createFromFormat("U???", strval($ts));
  }
}
