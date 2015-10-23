<?php
namespace DsvSu\Daisy;

class ResearchArea
{
    /** @var ResearchArea[] */
    private static $registry = [];

    /** @var int */
    private $id;

    /** @var string */
    private $name;

    private function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
        self::$registry[$id] = $this;
    }

    /** @return int */
    public function getId()
    {
        return $this->id;
    }

    /** @return string */
    public function getName()
    {
        return $this->name;
    }

    private static function getRegistry()
    {
        if (empty(self::$registry)) {
            new self(10, "Business Process Management and Enterprise Modeling");
            new self(11, "Consumer-oriented mobile services");
            new self(12, "Data Science");
            new self(13, "Digital games");
            new self(14, "Digital Systems Security");
            new self(15, "E-government and E-democracy");
            new self(17, "Healthcare Informatics");
            new self(16, "ICT for Development");
            new self(24, "Immersive Networking");
            new self(18, "Interaction design");
            new self(19, "IT Management");
            new self(20, "Language Technology");
            new self(21, "Risk and decision analysis");
            new self(22, "Service Science & Innovation");
            new self(23, "Technology Enhanced Learning");
            new self(9, "(Datorspel)");
            new self(2, "(Informationssystem)");
            new self(1, "(Informationssäkerhet)");
            new self(7, "(IT för hälsa)");
            new self(8, "(IT för lärande)");
            new self(3, "(Kunskap och kommunikation)");
            new self(4, "(Mobile Life)");
            new self(5, "(Programvaruutveckling)");
            new self(6, "(Systemanalys)");
        }
        return self::$registry;
    }

    /** @return ResearchArea[] */
    public static function getAll()
    {
        return array_values(self::getRegistry());
    }

    /** @return ResearchArea */
    public static function getById($id)
    {
        $registry = self::getRegistry();

        if (isset($registry[$id])) {
            return $registry[$id];
        } else {
            return null;
        }
    }
}
