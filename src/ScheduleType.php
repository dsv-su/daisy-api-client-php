<?php
namespace DsvSu\Daisy;

abstract class ScheduleType 
{
    const UNDEFINED = 0;
    const EDUCATION = 100;
    const WRITTEN_EXAM = 200;
    const HAND_IN = 250;
    const ROOM_BOOKING = 300;
    const PRIORITY = 400;
    const OTHER = 500;

    private static $SCHEDULE_TYPES = [
        'en' => [
            self::UNDEFINED => 'Undefined',
            self::EDUCATION => 'Education',
            self::WRITTEN_EXAM => 'Written examination',
            self::HAND_IN => 'Hand in',
            self::ROOM_BOOKING => 'Room booking',
            self::PRIORITY => 'Priority',
            self::OTHER => 'Other'
        ]
    ];

    public static getName($type, $lang = 'en') {
        return $SCHEDULE_TYPES[$lang][$type];
    }
}
