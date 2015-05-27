<?php
namespace DsvSu\Daisy;

abstract class Resource
{
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getId()
    {
        return $this->data['id'];
    }

    public function get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    protected static function parseDateTime($ts)
    {
        $dt = new \DateTime();
        $dt->setTimestamp(intval($ts / 1000));
        return $dt;
    }

    protected static function parseDate($str)
    {
        return DateTime::createFromFormat('Y-m-d', $str);
    }
}
