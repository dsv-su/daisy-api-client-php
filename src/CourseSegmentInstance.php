<?php
namespace DsvSu\Daisy;

class CourseSegmentInstance extends Resource {
  static function getById($id) {
    return new static(Client::get("courseSegment/$id"));
  }

  function getSemester() {
    return (substr($this->data['semester'], -1) == '2' ? 'HT' : 'VT') .
          substr($this->data['semester'], 0, -1);
  }

  function getDesignation() {
    return $this->get('designation');
  }

  function getName() {
    return $this->get('name');
  }
}
