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

    public function __initialize($identifier, $svName, $enName)
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

    private static function init()
    {
        new PublicationType('comprehensiveDoctoralThesis');
        new PublicationType('comprehensiveLicentiateThesis');
        new PublicationType('studentThesis');
        new PublicationType('article');
        new PublicationType('survey');
        new PublicationType('review');
        new PublicationType('book');
        new PublicationType('chapter');
        new PublicationType('collect');
        new PublicationType('conference');
        new PublicationType('paper');
        new PublicationType('keynote');
        new PublicationType('manuscript');
        new PublicationType('board');
        new PublicationType('opponent');
        new PublicationType('patent');
        new PublicationType('report');
        new PublicationType('other');
    }

    public static function getAll()
    {
        init();
    }

    public static function getByIdentifier($identifier)
    {
        init();
        return self::$registry[$identifier];
    }
}
