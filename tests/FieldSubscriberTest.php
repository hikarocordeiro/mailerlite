<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class FieldSubscriberTest extends TestCase
{
    protected $client;

    protected function setUp()
    {
        $this->client = new Client(
            [
                'base_uri' => 'http://127.0.0.1:8000/field_subscriber',
                'timeout' => 2.0,
            ]
        );
    }

    public function testSetSampleData()
    {
        $formData = [
            'title' => 'Some title',
            'type' => 1

        ];
        $response = $this->client->post('field', ['body' => json_encode($formData)]);
        $this->assertEquals(201, $response->getStatusCode());

        $formSubscriberData = [
            'name' => 'Hikaro',
            'email' => 'hikaro.cordeiro@gmail.com',
            'state' => 1
        ];
        $response = $this->client->post('subscriber', ['body' => json_encode($formSubscriberData)]);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testGetSampleData()
    {
        $data = [];

        $client = new Client(
            [
                'base_uri' => 'http://127.0.0.1:8000/subscriber',
                'timeout' => 2.0,
            ]
        );

        $response = $client->request('GET');
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getBody());

        $data['subscriber'] = reset($body)->id;


        $client = new Client(
            [
                'base_uri' => 'http://127.0.0.1:8000/field',
                'timeout' => 2.0,
            ]
        );

        $response = $client->request('GET');
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getBody());

        $data['field'] = reset($body)->id;

        return $data;
    }

    /**
     * @depends testGetSampleData
     */
    public function testPost($data)
    {
        $formData = [
            'subscriber' => $data["subscriber"],
            'field' => $data["field"]
        ];
        $response = $this->client->post('field_subscriber', ['body' => json_encode($formData)]);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testGetInserted()
    {
        $response = $this->client->request('GET');
        $this->assertEquals(200, $response->getStatusCode());

        $body = json_decode($response->getBody());
        return reset($body);
    }

    public function testGetAll()
    {
        $response = $this->client->request('GET');
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @depends testGetInserted
     */
    public function testGet($obj)
    {
        $response = $this->client->request('GET', '/field_subscriber/' . $obj->subscriber);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @depends testGetInserted
     */
    public function testPut($obj)
    {
        $formData = [
            'subscriber' => $obj->subscriber,
            'field' => $obj->field
        ];
        $response = $this->client->put('field_subscriber/' . $obj->id, ['body' => json_encode($formData)]);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @depends testGetInserted
     */
    public function testDelete($obj)
    {
        $response = $this->client->delete('field_subscriber/' . $obj->id);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
