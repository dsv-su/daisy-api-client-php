<?php
namespace DsvSu\Daisy;

class Employee extends Resource
{
    private static $WORK_PHONE_PREFIXES = [
        [
            '16', 1000, 4999
        ],
        [
            '1207', 6000, 6999
        ],
        [
            '674', 7000, 7999
        ],
        [
            '5537', 8000, 8999
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

    public function getRawWorkPhone()
    {
        return $this->get('workPhone');
    }

    public static function phonePrefixForExt($ext)
    {
        $ext = intval($ext);
        foreach (self::$WORK_PHONE_PREFIXES as $prefix) {
            if ($ext >= $prefix[1] && $ext <= $prefix[2]) {
                return $prefix[0];
            }
        }
        return null;
    }

    public function getWorkPhone()
    {
        if (!isset($this->workPhone)) {
            $wp = trim($this->getRawWorkPhone());
            if (empty($wp)) {
                $this->workPhone = null;
            } else {
                if (preg_match('/^\+?[-\d\s()]+/', $wp, $matches)) {
                    $wp = $matches[0];
                    $wp = trim(str_replace(['(', ')'], '', $wp));
                    if (strlen(str_replace(' ', '', $wp)) === 4) {
                        $wp = self::phonePrefixForExt($wp) . $wp;
                    }
                    if ($wp[0] !== '0' && $wp[0] !== '+') {
                        $wp = '08' . $wp;
                    }
                    $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
                    try {
                        $this->workPhone = $phoneUtil->parse($wp, 'SE');
                    } catch (\libphonenumber\NumberParseException $e) {
                        $this->workPhone = $this->getRawWorkPhone();
                    }
                } else {
                    $this->workPhone = $this->getRawWorkPhone();
                }
            }
        }
        return $this->workPhone;
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
