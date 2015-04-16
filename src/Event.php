<?php
namespace DsvSu\Daisy;
use \DateTime;
use \DateInterval;

class Event extends Resource {
  static $EDUCATIONAL_TYPES = [
    'en' => [
      1 => 'Teaching session',
      2 => 'Seminar',
      3 => 'Lecture',
      4 => 'Laboratory session',
      5 => 'Presentation',
      6 => 'Introduction',
      7 => 'Exercise',
      8 => 'Supervision',
      9 => 'Tutorial',
      10 => 'Project',
      11 => 'Workshop'
    ]
  ];

  static $SCHEDULE_TYPES = [
    'en' => [
      0   => 'Undefined',
      100 => 'Education',
      200 => 'Written examination',
      250 => 'Hand in',
      300 => 'Room booking',
      400 => 'Priority',
      500 => 'Other'
    ]
  ];

  static function find($room, DateTime $start, DateTime $end = NULL) {
    if (is_null($end)) {
      $end = clone $start;
      $end->add(new DateInterval('P1D'));
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

  function getDescription() {
    return $this->data['description'];
  }

  function getStart() {
    return self::parseDateTime($this->data['start']);
  }

  function getEnd() {
    return self::parseDateTime($this->data['end']);
  }

  function getEducationalType() {
    return $this->get('educationalType');
  }

  function getEducationalTypeName($lang = 'en') {
    if ($this->getEducationalType() !== NULL) {
      return self::$EDUCATIONAL_TYPES[$lang][$this->getEducationalType()];
    } else {
      return NULL;
    }
  }

  function getTitle($lang = 'en') {
    $t = $this->getEducationalTypeName($lang) . ' ' . $this->get('sequenceNumber');
    if ($this->get('groupNumber') !== NULL) {
      $t .=  ' group ' . $this->get('groupNumber');
    }
    $t .= ' ' . $this->get('title');
    return trim($t);
  }
}
