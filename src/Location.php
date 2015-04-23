<?php
namespace DsvSu\Daisy;

class Location extends Resource
{
    public static function getById($id)
    {
        return new static(Client::get("location/$id"));
    }

    public function getName()
    {
        return $this->get('designation');
    }
}
