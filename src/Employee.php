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
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        return $phoneUtil->parse($this->get('workPhone'), 'SE');
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
}
