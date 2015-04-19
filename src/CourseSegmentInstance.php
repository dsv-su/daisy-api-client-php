<?php
namespace DsvSu\Daisy;

class CourseSegmentInstance extends Resource
{
    public static function getById($id)
    {
        return new static(Client::get("courseSegment/$id"));
    }

    public function getSemester()
    {
        return (substr($this->data['semester'], -1) == '2' ? 'HT' : 'VT') .
              substr($this->data['semester'], 0, -1);
    }

    public function getDesignation()
    {
        return $this->get('designation');
    }

    public function getName()
    {
        return $this->get('name');
    }
}
