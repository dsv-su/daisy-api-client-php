<?php
namespace DsvSu\Daisy\Tests;

use DsvSu\Daisy\Employee;

class EmployeeTest extends TestCase
{
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
    }

    /**
     * @depends testFindByUsername
     */
    public function testGetTitle(Employee $e)
    {
        $this->assertEquals('Troll', $e->getTitle());
        $this->assertEquals('Ogre', $e->getTitle('en'));
    }

    /**
     * @depends testFindByUsername
     */
    public function testGetWorkPhone(Employee $e)
    {
        $p = $e->getWorkPhone();
        $this->assertInstanceOf('\libphonenumber\PhoneNumber', $p);
        $this->assertEquals(46, $p->getCountryCode());
        $this->assertEquals('161625', $p->getNationalNumber());
        $this->assertEquals('+468161625', strval($p));
        $this->assertEquals('08161650', $p->getNationalNumber());
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
