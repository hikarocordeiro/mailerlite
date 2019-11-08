<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class SubscriberTest extends TestCase
{
    protected $client;

    protected function setUp()
    {
        $this->client = new Client(
            [
                'base_uri' => 'http://127.0.0.1:8000/subscriber',
                'timeout' => 2.0,
            ]
        );
    }

    public function testPost()
    {
        $formData = [
            'name' => 'Hikaro',
            'email' => 'hikaro.cordeiro@gmail.com',
            'state' => 1

        ];
        $response = $this->client->post('subscriber', ['body' => json_encode($formData)]);
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
    public function testGet($subscriber)
    {
        $response = $this->client->request('GET', '/subscriber/' . $subscriber);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @depends testGetInserted
     */
    public function testPut($subscriber)
    {
        $formData = [
            'name' => 'Hikaro',
            'email' => 'hikaro.cordeiro@gmail.com',
            'state' => 1

        ];
        $response = $this->client->put('subscriber/' . $subscriber, ['body' => json_encode($formData)]);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @depends testGetInserted
     */
    public function testDelete($subscriber)
    {
        $response = $this->client->delete('subscriber/' . $subscriber);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
