<?php
namespace DsvSu\Daisy;

class CourseSegmentInstance extends Resource
{
    public static function getById($id)
    {
        return new static(Client::get("courseSegment/$id"));
    }

    /**
     * Retrieve an array of CourseSegmentInstance objects according to
     * a search query.
     *
     * @param array $query The query.
     * @return CourseSegmentInstance[]
     */
    public static function find(array $query)
    {
        if (isset($query['semester']) &&
            $query['semester'] instanceof Semester) {
            $query['semester'] = $query['semester']->daisyFormat();
        }
        $csis = Client::get("courseSegment", $query);
        return array_map(function ($data) { return new self($data); }, $csis);
    }

    public function getSemester()
    {
        return Semester::parse($this->get('semester'));
    }

    public function getDesignation()
    {
        return $this->get('designation');
    }

    public function getName($lang = 'sv')
    {
        if ($lang === 'sv') {
            return $this->get('name');
        } else if ($lang === 'en') {
            return $this->get('name_en');
        } else {
            throw new \DomainException("unsupported language: $lang");
        }
    }

    public function getCredits()
    {
        return $this->get('credits');
    }

    public function getStartDate()
    {
        return self::parseDate($this->get('startDate'));
    }

    public function getEndDate()
    {
        return self::parseDate($this->get('endDate'));
    }

    public function isVisible()
    {
        return $this->get('visible');
    }

    public function isPublished()
    {
        return $this->get('published');
    }

    /**
     * @return string The Daisy URL for the info pop-up for this
     *                course segment
     */
    public function getDaisyPopupUrl($lang = 'sv')
    {
        return Client::getDaisyBaseUrl() .
                       '/servlet/Momentinfo?id=' .
                       $this->getId() . '&daisy__lang=' . $lang;
    }

    /**
     * @return string The Daisy URL for the schedule for this
     *                course segment
     */
    public function getDaisyScheduleUrl($lang = 'sv')
    {
        return Client::getDaisyBaseUrl() .
                       '/servlet/schema.moment.Momentschema?id=' .
                       $this->getId() . '&daisy__lang=' . $lang;
    }
}
