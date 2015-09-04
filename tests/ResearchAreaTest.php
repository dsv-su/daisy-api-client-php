<?php
namespace DsvSu\Daisy\Tests;

use DsvSu\Daisy\ResearchArea;

class ResearchAreaTest extends TestCase
{
    public function testGetAll()
    {
        $areas = ResearchArea::getAll();
        $this->assertGreaterThan(0, count($areas));
        
        foreach ($areas as $area) {
            $this->assertInstanceOf('DsvSu\Daisy\ResearchArea', $area);
        }
    }

    public function testGetById()
    {
        $area = ResearchArea::getById(13);
        $this->assertInstanceOf('DsvSu\Daisy\ResearchArea', $area);
        $this->assertNull(ResearchArea::getById(99999));
        return $area;
    }

    /**
     * @depends testGetById
     */
    public function testGetId($area)
    {
        $this->assertEquals(13, $area->getId());
    }

    /**
     * @depends testGetById
     */
    public function testGetName($area)
    {
        $this->assertEquals('Digital games', $area->getName());
    }
}
