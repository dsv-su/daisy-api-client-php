<?php
namespace DsvSu\Daisy\Tests;

use DsvSu\Daisy\Person;

class PersonTest extends TestCase
{
    public function testGetById()
    {
        $this->mockData('{"id":23709,"firstName":"Per","lastName":"Olofsson","email":"pelle@dsv.su.se","lastChanged":1418743460013}');

        $p = Person::getById(23709);
        $this->assertInstanceOf('DsvSu\Daisy\Person', $p);
        $this->assertEquals($p->getId(), 23709);
        return $p;
    }

    /**
     * @depends testGetById
     */
    public function testGetDaisyPopupUrl(Person $p)
    {
        $this->assertEquals(
            'https://daisy.dsv.su.se/anstalld/anstalldinfo.jspa?personID=23709',
            $p->getDaisyPopupUrl()
        );
        $this->assertEquals(
            'https://testdaisy.dsv.su.se/anstalld/anstalldinfo.jspa?personID=23709',
            $p->getDaisyPopupUrl('https://testdaisy.dsv.su.se')
        );
    }
}
