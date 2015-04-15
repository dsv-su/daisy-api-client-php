<?php
namespace DsvSu\Daisy;

class Person extends Resource {
  static function getById($id) {
    return new static(Client::get("person/$id"));
  }

  static function findByPrincipalName($principal) {
    $data = Client::get("person/username/$principal");
    return $data === NULL ? NULL : new static($data);
  }

  static function findByUsername($username, $domain = 'su.se') {
    return static::findByPrincipalName("${username}@$domain");
  }

  function getPrincipals() {
    return Client::get('person/'.$this->getId().'/usernames');
  }

  function getUsername($domain = 'su.se') {
    $domain = strtolower($domain);
    $principals = $this->getPrincipals();

    foreach ($principals as $p) {
      if (strtolower($p['realm']) == $domain) {
        return $u['username'];
      }
    }
    return NULL;
  }
}
