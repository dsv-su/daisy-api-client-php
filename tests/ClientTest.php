<?php
namespace DsvSu\Daisy\Tests;

use DsvSu\Daisy\Client;

class ClientTest extends TestCase
{
    public function testGet()
    {
        $this->mockData('{"a":"b"}');
        $result = Client::get('foo/bar');
        $req = $this->getRequest();
        $this->assertEquals('GET', $req->getMethod());
        $this->assertEquals(
            'http://api.example/rest/foo/bar',
            strval($req->getUri())
        );
        $this->assertEquals(['a' => 'b'], $result);

        $this->mockData('{}');
        Client::get('foo/bar', [ 'a' => 'b', 'c' => 3 ]);
        $req = $this->getRequest();
        $this->assertEquals(
            'http://api.example/rest/foo/bar?a=b&c=3',
            strval($req->getUri())
        );
    }
}
