<?php
namespace DsvSu\Daisy;

class Person extends Resource {
  static function getById($id) {
    new static(Client::get("person/$id"));
  }

  static function getByUsername($username) {
    new static(Client::get("person/username/$username"));
  }

  function getUsernames() {
    return Client::get('person/'.$this->getId().'/usernames');
  }
}
