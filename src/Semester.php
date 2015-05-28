<?php
namespace DsvSu\Daisy;

class Semester
{
    const SPRING = 1;
    const FALL   = 2;
    const AUTUMN = 2;

    /** @var int */
    private $year;
    /** @var int */
    private $season;

    /**
     * @param int $season Semester::SPRING or
                          Semester::FALL == Semester::AUTUMN
     * @param int $year
     */
    public function __construct($year, $season)
    {
        assert(is_numeric($year));
        assert($season == self::SPRING || $season == self::FALL);

        if ($year < 1980) {
            throw new \DomainException("year too early: $year");
        }

        $this->year = intval($year);
        $this->season = intval($season);
    }

    /**
     * @param string $str A semester in the format 20081 or VT2008
     *                    (case-insensitive)
     * @return Semester|null
     */
    public static function parse($str)
    {
        if (preg_match('/^(\d{4})(\d)$/', $str, $matches)) {
            return new self(intval($matches[1]), intval($matches[2]));
        } else if (preg_match('/^([vh]t)(\d{4})$/i', $str, $matches)) {
            return new self(
                intval($matches[2]),
                strtolower($matches[1]) === 'vt' ? self::SPRING : self::FALL
            );
        } else {
            throw new \DomainException("invalid semester: $str");
        }
    }

    /** @return int */
    public function getYear()
    {
        return $this->year;
    }

    /** @return int Semester::SPRING or Semester::FALL */
    public function getSeason()
    {
        return $this->season;
    }

    /** @return string Semester on the format VT2008 */
    public function __toString()
    {
        return ($this->season === self::SPRING ? 'V' : 'H') . 'T'
            . $this->year;
    }

    /** @return string Semester in Daisy format (e.g., 20081) */
    public function daisyFormat()
    {
        return $this->year . $this->season;
    }
}
