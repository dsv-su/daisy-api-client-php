<?php
namespace DsvSu\Daisy;

class Person extends Resource {
  static function getById($id) {
    return new static(Client::get("person/$id"));
  }

  static function getByUsername($username) {
    $data = Client::get("person/username/$username");
    return $data === NULL ? NULL : new static($data);
  }

  function getUsernames() {
    return Client::get('person/'.$this->getId().'/usernames');
  }
}
