<?php
namespace DsvSu\Daisy;

class Person extends Resource
{
    public static function getById($id)
    {
        return new static(Client::get("person/$id"));
    }

    public static function findByPrincipalName($principal)
    {
        $data = Client::get("person/username/$principal");
        return $data === null ? null : new static($data);
    }

    public static function findByUsername($username, $domain = 'su.se')
    {
        return static::findByPrincipalName("${username}@$domain");
    }

    public function getPrincipals()
    {
        return Client::get('person/'.$this->getId().'/usernames');
    }

    public function getUsername($domain = 'su.se')
    {
        $domain = strtolower($domain);
        $principals = $this->getPrincipals();

        foreach ($principals as $p) {
            if (strtolower($p['realm']) == $domain) {
                return $p['username'];
            }
        }
        return null;
    }

    public function getFirstName()
    {
        return $this->get('firstName');
    }

    public function getLastName()
    {
        return $this->get('lastName');
    }

    public function getMail()
    {
        return $this->get('email');
    }
}
