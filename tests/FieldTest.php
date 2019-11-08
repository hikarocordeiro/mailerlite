<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class FieldTest extends TestCase
{
    protected $client;

    protected function setUp()
    {
        $this->client = new Client(
            [
                'base_uri' => 'http://127.0.0.1:8000/field',
                'timeout' => 2.0,
            ]
        );
    }

    public function testPost()
    {
        $formData = [
            'title' => 'Some title',
            'type' => 1

        ];
        $response = $this->client->post('field', ['body' => json_encode($formData)]);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testGetInserted()
    {
        $response = $this->client->request('GET');
        $this->assertEquals(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        return reset($body)->id;
    }

    public function testGetAll()
    {
        $response = $this->client->request('GET');
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @depends testGetInserted
     */
    public function testGet($id)
    {
        $response = $this->client->request('GET', '/field/' . $id);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @depends testGetInserted
     */
    public function testPut($id)
    {
        $formData = [
            'title' => 'Another Title',
            'type' => 2

        ];
        $response = $this->client->put('field/' . $id, ['body' => json_encode($formData)]);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @depends testGetInserted
     */
    public function testDelete($id)
    {
        $response = $this->client->delete('field/' . $id);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
