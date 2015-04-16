<?php
namespace DsvSu\Daisy;
use \DateTime;

class Event extends Resource {
  static function find($room, DateTime $start, DateTime $end = NULL) {
    if (is_null($end)) {
      $end = $start;
    }
    $events = Client::get('schedule', [
      'room' => $room,
      'start' => $start->format('Y-m-d'),
      'end' => $end->format('Y-m-d')
    ]);
    return array_map(function ($data) { return new static($data); }, $events);
  }

  function getCourseSegmentInstances() {
    return array_map(function ($csi) {
      return CourseSegmentInstance::getById($csi['id']);
    }, $this->data['courseSegmentInstances']);
  }

  function getStart() {
    return new DateTime("@".intval($this->data['start']/1000));
  }

  function getEnd() {
    return new DateTime("@".intval($this->data['end']/1000));
  }

}
