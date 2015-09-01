<?php
namespace DsvSu\Daisy;

class PublicationType
{
    /** @var PublicationType[] */
    private static $registry = [];

    /** @var string */
    private $identifier;

    /** @var string */
    private $svName;

    /** @var string */
    private $enName;

    private function __initialize($identifier, $svName, $enName)
    {
        $this->identifier = $identifier;
        $this->svName = $svName;
        $this->enName = $enName;
        self::$registry[$identifier] = $this;
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function getName($lang = 'sv')
    {
        if ($lang === 'sv') {
            return $this->svName;
        } else if ($lang === 'en') {
            return $this->enName;
        } else {
            
        }
    }

    private static function initRegistry()
    {
        if (empty(self::$registry)) {
            new self('comprehensiveDoctoralThesis');
            new self('comprehensiveLicentiateThesis');
            new self('studentThesis');
            new self('article');
            new self('survey');
            new self('review');
            new self('book');
            new self('chapter');
            new self('collect');
            new self('conference');
            new self('paper');
            new self('keynote');
            new self('manuscript');
            new self('board');
            new self('opponent');
            new self('patent');
            new self('report');
            new self('other');
        }
    }

    public static function getAll()
    {
        self::initRegistry();
        return array_values(self::$registry);
    }

    public static function getByIdentifier($identifier)
    {
        self::initRegistry();

        if (isset(self::$registry[$identifier])) {
            return self::$registry[$identifier];
        } else {
            // throw exception
        }
    }
}
