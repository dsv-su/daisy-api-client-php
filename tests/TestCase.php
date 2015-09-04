<?php
namespace DsvSu\Daisy\Tests;

use DsvSu\Daisy;

class TestCase extends \PHPUnit_Framework_TestCase
{
    protected $mock;

    public static function setUpBeforeClass()
    {
        Daisy\Client::init([
            'url' => 'http://api.example/rest/',
            'user' => 'apitestuser',
            'pass' => 'apitestpass'
        ]);
    }

    protected function setUp()
    {
        $this->mock = new \GuzzleHttp\Handler\MockHandler();
        Daisy\Client::getGuzzle()
                ->getConfig('handler')
                ->setHandler($this->mock);
    }

    protected function mockData($data)
    {
        $this->mock->append(new \GuzzleHttp\Psr7\Response(
            200,
            [ 'Content-Type' => 'application/json' ],
            $data
        ));
    }

    protected function getRequest()
    {
        return $this->mock->getLastRequest();
    }

    protected function assertPath($path)
    {
        assertEquals($path, $this->getRequest()->getUri()->getPath());
    }
}
