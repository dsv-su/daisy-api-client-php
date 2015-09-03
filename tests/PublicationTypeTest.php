<?php
namespace DsvSu\Daisy\Tests;

use DsvSu\Daisy\PublicationType;

class PublicationTypeTest extends TestCase
{
    public function testGetAll()
    {
        $pts = PublicationType::getAll();
        $this->assertGreaterThanOrEqual(18, count($pts));
        
        foreach ($pts as $pt) {
            $this->assertInstanceOf('DsvSu\Daisy\PublicationType', $pt);
        }
    }

    public function testGetByIdentifier()
    {
        $pt = PublicationType::getByIdentifier('paper');
        $this->assertInstanceOf('DsvSu\Daisy\PublicationType', $pt);
        $this->assertNull(PublicationType::getByIdentifier('xyz'));
        return $pt;
    }

    /**
     * @depends testGetByIdentifier
     */
    public function testGetIdentifier($pt)
    {
        $this->assertEquals('paper', $pt->getIdentifier());
    }

    /**
     * @depends testGetByIdentifier
     */
    public function testGetName($pt)
    {
        $this->assertEquals('Conference paper', $pt->getName());
    }
}
