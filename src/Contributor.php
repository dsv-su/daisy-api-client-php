<?php
namespace DsvSu\Daisy;

class Contributor extends Resource
{
    /**
     * @return string|null
     */
    public function getFirstName()
    {
        return $this->get('firstName');
    }

    /**
     * @return string|null
     */
    public function getLastName()
    {
        return $this->get('lastName');
    }

    /**
     * @return string|null
     */
    public function getFunction()
    {
        return $this->get('function');
    }
}
