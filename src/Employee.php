<?php
namespace DsvSu\Daisy;

use \libphonenumber\PhoneNumber;

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

    /**
     * @param array $data Decoded JSON data for this employee from Daisy API
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->person = new Person($data['person']);
    }

    /**
     * @param string $principal The principal name, e.g. user@su.se.
     * @return Employee|null
     */
    public static function findByPrincipalName($principal)
    {
        $data = Client::get("employee/username/$principal");
        return $data === null ? null : new self($data);
    }

    /**
     * @param string $username User name of the employee.
     * @param string $domain The domain of the username (su.se by default).
     * @return Employee|null
     */
    public static function findByUsername($username, $domain = 'su.se')
    {
        return self::findByPrincipalName("${username}@$domain");
    }

    /**
     * Retrieve an array of Employee objects according to a search query.
     *
     * @param array $query The query.
     * @return Employee[]
     */
    public static function find(array $query)
    {
        $employees = Client::get("employee", $query);
        return array_map(function ($data) { return new self($data); }, $employees);
    }

    /**
     * @return string|null
     */
    public function getOffice()
    {
        return $this->get('office');
    }

    /**
     * @return string|null
     */
    public function getRawWorkPhone()
    {
        return $this->get('workPhone');
    }

    /**
     * @param string|int $ext Phone number extension
     * @return string|null
     */
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

    /**
     * Get work phone number of this employee. Try to parse it to a
     * PhoneNumber, otherwise return the raw string.
     *
     * @return PhoneNumber|string|null The work phone number, or null if missing.
     */
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

    /**
     * @param string $lang Language to get title in (sv or en).
     * @throws \DomainException If language is unsupported (not sv or en).
     * @return string
     */
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

    /**
     * Get Person object for this employee.
     *
     * @return Person
     */
    public function getPerson()
    {
        return $this->person;
    }
}
