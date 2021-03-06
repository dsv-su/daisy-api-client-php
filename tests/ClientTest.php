<?php
namespace DsvSu\Daisy\Tests;

use DsvSu\Daisy\Client;
use GuzzleHttp\Psr7\Response;

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
        Client::get('foo/bar', ['a' => 'b', 'c' => 3, 'd' => false,
                                'e' => true]);
        $req = $this->getRequest();
        $this->assertEquals(
            'http://api.example/rest/foo/bar?a=b&c=3&d=0&e=1',
            strval($req->getUri())
        );

        $this->mock->append(new Response(404));
        $result = Client::get('foo/bar');
        $this->assertNull($result);

        $this->mockData('{}');
        $result = Client::get('foo/bar', ['a' => ['b', 'c']]);
        $req = $this->getRequest();
        $this->assertEquals($req->getUri()->getQuery(), 'a=b&a=c');

        $this->mockData('{}');
        $result = Client::get(
            'foo/bar',
            [
                'a' => null,
                'b' => '',
                'c' => 1,
                'd' => [],
                'g' => [''],
                'h' => [null],
            ]
        );
        $req = $this->getRequest();
        $this->assertEquals($req->getUri()->getQuery(), 'c=1');
    }

    /** @expectedException DsvSu\Daisy\ServerException */
    public function testGetError()
    {
        $this->mock->append(new Response(500));
        Client::get('foo/bar', [ 'a' => 'b', 'c' => 3 ]);
    }
}
