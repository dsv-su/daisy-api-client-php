<?php
namespace DsvSu\Daisy;

class Event extends Resource
{
    private static $EDUCATIONAL_TYPES = [
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

    private static $SCHEDULE_TYPES = [
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

    public static function find($room, \DateTime $start, \DateTime $end = null)
    {
        if (is_null($end)) {
            $end = clone $start;
            $end->add(new \DateInterval('P1D'));
        }
        $events = Client::get('schedule', [
            'room' => $room,
            'start' => $start->format('Y-m-d'),
            'end' => $end->format('Y-m-d')
        ]);
        return array_map(function ($data) { return new static($data); }, $events);
    }

    public function getCourseSegmentInstances()
    {
        return array_map(function ($csi) {
            return CourseSegmentInstance::getById($csi['id']);
        }, $this->data['courseSegmentInstances']);
    }

    public function getDescription()
    {
        return $this->data['description'];
    }

    public function getStart()
    {
        return self::parseDateTime($this->data['start']);
    }

    public function getEnd()
    {
        return self::parseDateTime($this->data['end']);
    }

    public function getEducationalType()
    {
        return $this->get('educationalType');
    }

    public function getEducationalTypeName($lang = 'en')
    {
        if ($this->getEducationalType() !== null) {
            return self::$EDUCATIONAL_TYPES[$lang][$this->getEducationalType()];
        } else {
            return null;
        }
    }

    public function getEducationalEventTitle($lang = 'en')
    {
        if ($this->getEducationalType() != null) {
            $type = self::$EDUCATIONAL_TYPES[$lang][$this->getEducationalType()];
            $number = $this->get('sequenceNumber');
            $groupNum = $this->get('groupNumber');

            $groupInfo = '';
            if ($groupNum != null) {
	        if($lang == 'en') {
	            $groupInfo = ' group ';
	        } else if ($lang == 'sv') {
	            $groupInfo = ' grupp ';
	        }
	        $groupInfo .= $groupNum;
            }
            return $type.' '.$number.$groupInfo;
        } else {
            return null;
        }
    }

    public function getTitle($lang = 'en')
    {
        $t = $this->getEducationalTypeName($lang) . ' ' . $this->get('sequenceNumber');
        if ($this->get('groupNumber') !== null) {
            $t .=  ' group ' . $this->get('groupNumber');
        }
        $t .= ' ' . $this->get('title');
        return trim($t);
    }
}
