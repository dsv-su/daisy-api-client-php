<?php
namespace DsvSu\Daisy;

class Publication extends Resource
{
    /** @var Contributor[]|null */
    private $contributors;

    /**
     * Retrieve an array of Publication objects according to a search query.
     *
     * @param array $query The query.
     * @return Publication[]
     */
    public static function find(array $query)
    {
        $pubs = Client::get('publication', $query);
        return array_map(function ($data) { return new self($data); }, $pubs);
    }

    /**
     * @return string|null
     */
    public function getTitle()
    {
        return $this->get('title');
    }

    /**
     * @return int|null
     */
    public function getYear()
    {
        return $this->get('year');
    }

    /**
     * @return string|null
     */
    public function getType()
    {
        return $this->get('type');
    }

    /**
     * @return Contributor[]|null
     */
    public function getContributors()
    {
        if (!isset($this->contributors)) {
            if ($this->get('contributors') !== null) {
                $this->contributors = array_map(
                    function ($data) { return new Contributor($data); },
                    $contributors
                );
            } else {
                $this->contributors = null;
            }
        }
        return $this->contributors;
    }
}
