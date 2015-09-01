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
            'https://daisy.dsv.su.se/anstalld/anstalldinfo.jspa?personID=23709&daisy__lang=sv',
            $p->getDaisyPopupUrl()
        );
        $this->assertEquals(
            'https://daisy.dsv.su.se/anstalld/anstalldinfo.jspa?personID=23709&daisy__lang=en',
            $p->getDaisyPopupUrl('en')
        );
        $this->assertEquals(
            'https://daisy.dsv.su.se/anstalld/anstalldinfo.jspa?personID=23709&daisy__lang=sv',
            $p->getDaisyPopupUrl('sv')
        );
    }

    /**
     * @depends testGetById
     * @expectedException DomainException
     */
    public function testGetDaisyPopupUrlLang(Person $p)
    {
        $p->getDaisyPopupUrl('xyz');
    }
}
