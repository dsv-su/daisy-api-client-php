<?php
namespace DsvSu\Daisy;

class CourseSegmentInstance extends Resource {
  static function getById($id) {
    return new static(Client::get("courseSegment/$id"));
  }
}
