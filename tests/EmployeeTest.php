<?php
namespace DsvSu\Daisy\Tests;

use DsvSu\Daisy\Employee;

class EmployeeTest extends TestCase
{
    const MOCK_EMPLOYEE_1 = '{"person":{"id":1234,"firstName":"Fil","lastName":"Ur","email":"fil-ur@dsv.su.se","lastChanged":1418743460013},"departments":[{}],"office":"10:9","workPhone":"161625","title":"Troll","title_en":"Ogre"}';
    
    public function testGetById()
    {
        $this->mockData(self::MOCK_EMPLOYEE_1);

        $e = Employee::findByUsername('fil-ur');
        $this->assertInstanceOf('DsvSu\Daisy\Employee', $e);

        $req = $this->getRequest();
        $this->assertEquals('GET', $req->getMethod());
        $this->assertEquals(
            '/rest/employee/username/fil-ur@su.se',
            $req->getPath()
        );
        $this->assertCount(0, $req->getQuery());

        $this->assertEquals('10:9', $e->getOffice());
        $this->assertEquals('Troll', $e->getTitle());
        $this->assertEquals('Ogre', $e->getTitle('en'));
        $this->assertEquals('161625', $e->getWorkPhone());

        $p = $e->getPerson();
        $this->assertInstanceOf('DsvSu\Daisy\Person', $p);
        $this->assertEquals(1234, $p->getId());
        $this->assertEquals('Fil', $p->getFirstName());
        $this->assertEquals('Ur', $p->getLastName());
        $this->assertEquals('fil-ur@dsv.su.se', $p->getEmail());
    }
}
