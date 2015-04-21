<?php
namespace DsvSu\Daisy;

abstract class EduType
{
    const TEACHING_SESSION = 1;
    const SEMINAR = 2;
    const LECTURE = 3;
    const LAB_SESSION = 4;
    const PRESENTATION = 5;
    const INTRO = 6;
    const EXCERCISE = 7;
    const SUPERVISION = 8;
    const TUTORIAL = 9;
    const PROJECT = 10;
    const WORKSHOP = 11;

    private static $EDUCATIONAL_NAMES = [
        'en' => [
            self::TEACHING_SESSION => 'Teaching session',
            self::SEMINAR => 'Seminar',
            self::LECTURE => 'Lecture',
            self::LAB_SESSION => 'Laboratory session',
            self::PRESENTATION => 'Presentation',
            self::INTRO => 'Introduction',
            self::EXCERCISE => 'Exercise',
            self::SUPERVISION => 'Supervision',
            self::TUTORIAL => 'Tutorial',
            self::PROJECT => 'Project',
            self::WORKSHOP => 'Workshop'
        ]
    ];

    public static function getName($type, $lang = 'en') {
        return $EDUCATIONAL_NAMES[$lang][$type];
    }
}
