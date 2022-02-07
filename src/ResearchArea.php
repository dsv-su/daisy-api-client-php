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
            new self(26, "Cyber Security");
            new self(12, "Data Science");
            new self(27, "Digital Games and Simulation");
            new self(28, "Digital Innovation");
            new self(15, "E-government and E-democracy");
            new self(17, "Health Informatics");
            new self(16, "ICT for Development");
            new self(24, "Immersive Networking");
            new self(18, "Interaction design");
            new self(25, "IT Management and Governance");
            new self(20, "Language Technology");
            new self(21, "Risk and decision analysis");
            new self(30, "Software Science");
            new self(23, "Technology Enhanced Learning");
            new self(9, "Computer Games (inactive)");
            new self(11, "Consumer-oriented mobile services (inactive)");
            new self(14, "Digital Systems Security (inactive)");
            new self(1, "Information security (inactive)");
            new self(2, "Information system (inactive)");
            new self(7, "IT for health (inactive)");
            new self(8, "IT for learning (inactive)");
            new self(19, "IT Management (inactive)");
            new self(3, "Knowledge and communication (inactive)");
            new self(4, "Mobile Life (inactive)");
            new self(22, "Service Science & Innovation (inactive)");
            new self(5, "Software development (inactive)");
            new self(6, "System analysis (inactive)");
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
