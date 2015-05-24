<?php
namespace DsvSu\Daisy;

class Employee extends Resource
{
    private static $WORK_PHONE_PREFIXES = [
        [
            '816', 1000, 4999
        ],
        [
            '81207', 6000, 6999
        ],
        [
            '8674', 7000, 7999
        ],
        [
            '85537', 8000, 8999
        ]
    ];

    private $person;
    private $completeWorkPhone;
    private $workPhoneExtension;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->person = new Person($data['person']);
    }

    public static function findByPrincipalName($principal)
    {
        $data = Client::get("employee/username/$principal");
        return $data === null ? null : new self($data);
    }

    public static function findByUsername($username, $domain = 'su.se')
    {
        return self::findByPrincipalName("${username}@$domain");
    }

    public static function find(array $query)
    {
        $employees = Client::get("employee", $query);
        return array_map(function ($data) { return new self($data); }, $employees);
    }

    public function getOffice()
    {
        return $this->get('office');
    }

    public function getWorkPhone()
    {
        return $this->get('workPhone');
    }

    public function getTitle($lang = 'sv')
    {
        switch ($lang) {
            case 'sv':
                return $this->get('title');
            case 'en':
                return $this->get('title_en');
            default:
                throw new \DomainException("Language not supported: $lang");
        }
    }

    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Get the complete work phone number, including country code prefix.
     */
    public function getCompleteWorkPhone()
    {
        if (!isset($this->completeWorkPhone)) {
            $p = $this->getWorkPhone();
            if (empty($p)) {
                $this->completeWorkPhone = null;
            } else {
                $p = str_replace(['-', ' ', '(', ')'], '', $p);
                if (strlen($p) === 4) $p = '16' . $p;
                if ($p[0] !== '+' && $p[0] !== '0') $p = '8' . $p;
                if ($p[0] === '0') $p = substr($p, 1);
                if ($p[0] !== '+') $p = '+46' . $p;
                $this->completeWorkPhone = $p;
            }
        }
        return $this->completeWorkPhone;
    }

    /**
     * Get the extension for the work phone number.
     */
    public function getWorkPhoneExtension()
    {
        if (!isset($this->workPhoneExtension)) {
            $cp = $this->getCompleteWorkPhone();
            if (empty($cp)) {
                $this->workPhoneExtension = null;
            } else {
                if (substr($cp, 0, 3) === '+46') $cp = '0' . substr($cp, 3);
                if (substr($cp, 0, 2) === '08') {
                    $cp = substr_replace($cp, '-', 2, 0);
                } else {
                    $cp = substr_replace($cp, '-', 3, 0);
                }
                $this->workPhoneExtension = $cp;
            }
        }
        return $this->workPhoneExtension;
    }
}
