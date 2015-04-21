<?php
namespace DsvSu\Daisy;

class Event extends Resource
{

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
        return $this->get('description');
    }

    public function getStart()
    {
        return self::parseDateTime($this->get('start'));
    }

    public function getEnd()
    {
        return self::parseDateTime($this->get('end'));
    }

    public function getScheduleType()
    {
        return $this->get('scheduleType');
    }

    public function getEducationalType()
    {
        return $this->get('educationalType');
    }

    public function getEducationalTypeName($lang = 'en')
    {
        if ($this->getEducationalType()) {
            return EduType::getName($this->getEducationalType(), $lang);
        } else {
            return null;
        }
    }

    public function getGroup($lang = 'en')
    {
        if($this->get('groupNumber')) {
            switch($lang) {
                case 'en':
                    return 'group '.$this->get('groupNumber');
                case 'sv':
                    return 'grupp '.$this->get('groupNumber');
            }
        }
        return null;
    }
    
    public function getSequenceNumber()
    {
        if($this->get('sequenceNumber')) {
            return $this->get('sequenceNumber');
        }
        return null;
    }

    public function getTitle($lang = 'en')
    {
        $schedType = $this->getScheduleType();
        switch($schedType) {
            case ScheduleType::EDUCATION:
                $type = $this->getEducationalTypeName($lang);
                return trim($type.' '.$this->getSequenceNumber().' '.$this->getGroup($lang));
                
            case ScheduleType::PRIORITY:
            case ScheduleType::WRITTEN_EXAM:
            case ScheduleType::HAND_IN:
            case ScheduleType::OTHER:
                return ScheduleType::getName($schedType, $lang);

            case ScheduleType::ROOM_BOOKING:
                return $this->get('title');

        }
        return null;
    }
}
