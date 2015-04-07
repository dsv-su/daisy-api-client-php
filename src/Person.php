<?php
namespace DsvSu\Daisy;

class Person extends Resource {
  static function getById(int $id) {
    new static(Client::get("person/$id"));
  }

  static function getByUsername(string $username) {
    new static(Client::get("person/username/$username"));
  }

  function getUsernames() {
    return Client::get('person/'.$this->getId().'/usernames');
  }
}
