<?php
namespace DsvSu\Daisy\Tests;

use DsvSu\Daisy\Employee;
use libphonenumber\PhoneNumberFormat;

class EmployeeTest extends TestCase
{
    private function mockEmployee($data)
    {
        if (!isset($data['person'])) {
            $data['person'] = [];
        }
        $this->mockData(json_encode($data));
        return Employee::findByUsername('dummy');
    }

    public function testFindByUsername()
    {
        $this->mockData('{"person":{"id":1234,"firstName":"Fil","lastName":"Ur","email":"fil-ur@dsv.su.se","lastChanged":1418743460013},"departments":[{}],"office":"10:9","workPhone":"161625","title":"Troll","title_en":"Ogre"}');
        $e = Employee::findByUsername('fil-ur');

        $this->assertInstanceOf('DsvSu\Daisy\Employee', $e);

        $req = $this->getRequest();
        $this->assertEquals('GET', $req->getMethod());
        $this->assertEquals(
            '/rest/employee/username/fil-ur@su.se',
            $req->getPath()
        );
        $this->assertCount(0, $req->getQuery());

        return $e;
    }

    /**
     * @depends testFindByUsername
     */
    public function testGetOffice(Employee $e)
    {
        $this->assertEquals('10:9', $e->getOffice());

        $e = $this->mockEmployee([]);
        $this->assertNull($e->getOffice());
    }

    /**
     * @depends testFindByUsername
     */
    public function testGetTitle(Employee $e)
    {
        $this->assertEquals('Troll', $e->getTitle());
        $this->assertEquals('Troll', $e->getTitle('sv'));
        $this->assertEquals('Ogre', $e->getTitle('en'));

        $e = $this->mockEmployee([]);
        $this->assertNull($e->getTitle());
        $this->assertNull($e->getTitle('en'));
    }

    /**
     * @depends testFindByUsername
     * @expectedException DomainException
     */
    public function testGetTitleLang(Employee $e)
    {
        $e->getTitle('xyz');
    }

    /**
     * @depends testFindByUsername
     */
    public function testGetWorkPhone(Employee $e)
    {
        $p = $e->getWorkPhone();
        $util = \libphonenumber\PhoneNumberUtil::getInstance();

        $this->assertInstanceOf('\libphonenumber\PhoneNumber', $p);
        $this->assertEquals(46, $p->getCountryCode());
        $this->assertEquals('8161625', $p->getNationalNumber());
        $this->assertEquals('+468161625', strval($p));
        $this->assertEquals(
            '08-16 16 25',
            $util->format($p, PhoneNumberFormat::NATIONAL)
        );
        $this->assertEquals(
            '+46 8 16 16 25',
            $util->format($p, PhoneNumberFormat::INTERNATIONAL)
        );

        $numbers = [
            '08-16 16 25', '+46 (8) 161625', '1625',
            '08-161625, 08-16 16 50', '08 16 16 25'
        ];
        foreach ($numbers as $number) {
            $p = $this->mockEmployee(['workPhone' => $number])
                      ->getWorkPhone();
            $this->assertEquals(
                '+468161625',
                strval($p),
                "Failed to parse phone number correctly: $number"
            );
        }

        $p = $this->mockEmployee(['workPhone' => '0525 123 45 67'])
                  ->getWorkPhone();
        $this->assertEquals('+465251234567', strval($p));

        $nikos = 'SU: 08161295 KTH: 087904460';
        $p = $this->mockEmployee(['workPhone' => $nikos])
                  ->getWorkPhone();
        $this->assertSame($nikos, $p);
    }

    /**
     * @depends testFindByUsername
     */
    public function testGetPerson(Employee $e)
    {
        $p = $e->getPerson();
        $this->assertInstanceOf('DsvSu\Daisy\Person', $p);
        $this->assertEquals(1234, $p->getId());
        $this->assertEquals('Fil', $p->getFirstName());
        $this->assertEquals('Ur', $p->getLastName());
        $this->assertEquals('fil-ur@dsv.su.se', $p->getMail());
    }
}
