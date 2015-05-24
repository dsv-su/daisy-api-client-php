<?php
namespace DsvSu\Daisy\Tests;

use DsvSu\Daisy;

class TestCase extends \PHPUnit_Framework_TestCase
{
    protected $mock;
    protected $history;

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
        $this->mock = new \GuzzleHttp\Subscriber\Mock();
        Daisy\Client::getGuzzle()->getEmitter()->attach($this->mock);

        $this->history = new \GuzzleHttp\Subscriber\History();
        Daisy\Client::getGuzzle()->getEmitter()->attach($this->history);
    }

    protected function tearDown()
    {
        Daisy\Client::getGuzzle()->getEmitter()->detach($this->mock);
        unset($this->mock);

        Daisy\Client::getGuzzle()->getEmitter()->detach($this->history);
        unset($this->history);
    }

    protected function mockData($data)
    {
        $body = \GuzzleHttp\Stream\Stream::factory($data);
        $this->mock->addResponse(new \GuzzleHttp\Message\Response(
            200,
            [ 'Content-Type' => 'application/json' ],
            $body
        ));
    }

    protected function getRequest()
    {
        return $this->history->getLastRequest();
    }

    protected function assertPath($path)
    {
        assertEquals($this->getRequest()->getPath(), $path);
    }
}
