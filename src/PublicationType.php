<?php
namespace DsvSu\Daisy;

class PublicationType
{
    /** @var PublicationType[] */
    private static $registry = [];

    /** @var string */
    private $identifier;

    /** @var string */
    private $name;

    private function __construct($identifier, $name)
    {
        $this->identifier = $identifier;
        $this->name = $name;
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function getName()
    {
        return $this->name;
    }

    private static function initRegistry()
    {
        static $types = [
            [ 'article', 'Article in journal' ],
            [ 'survey', 'Article, review/survey' ],
            [ 'review', 'Article, book review' ],
            [ 'book', 'Book' ],
            [ 'chapter', 'Chapter in book' ],
            [ 'collect', 'Collection (editor)' ],
            [ 'conference', 'Conference proceedings (editor)' ],
            [ 'paper', 'Conference paper' ],
            [ 'report', 'Report' ],
            [ 'other', 'Other' ],
        ];

        if (empty(self::$registry)) {
            foreach ($types as $type) {
                self::$registry[$type[0]] = new self($type[0], $type[1]);
            }
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
            return null;
        }
    }
}
