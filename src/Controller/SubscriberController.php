<?php

namespace Src\Controller;

use Src\TableGateways\SubscriberGateway;

class SubscriberController
{

    private $db;
    private $requestMethod;
    private $id;

    private $subscriberGateway;

    public function __construct($db, $requestMethod, $id)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->id = $id;

        $this->subscriberGateway = new SubscriberGateway($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->id) {
                    $response = $this->get($this->id);
                } else {
                    $response = $this->getAll();
                };
                break;
            case 'POST':
                $response = $this->createFromRequest();
                break;
            case 'PUT':
                $response = $this->updateFromRequest($this->id);
                break;
            case 'DELETE':
                $response = $this->delete($this->id);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function getAll()
    {
        $result = $this->subscriberGateway->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function get($id)
    {
        $result = $this->subscriberGateway->find($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function createFromRequest()
    {
        $input = (array)json_decode(file_get_contents('php://input'), true);

        if (!$this->validate($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->subscriberGateway->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
    }

    private function updateFromRequest($id)
    {
        $result = $this->subscriberGateway->find($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $input = (array)json_decode(file_get_contents('php://input'), true);
        if (!$this->validate($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->subscriberGateway->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function delete($id)
    {
        $result = $this->subscriberGateway->find($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $this->subscriberGateway->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function validate($input)
    {
        if (!isset($input['name'])) {
            return false;
        }
        if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $checkEmail = explode("@", $input['email']);

        if (!checkdnsrr(array_pop($checkEmail), "MX")) {
            return false;
        }

        return true;
    }

    private function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode(
            [
                'error' => 'Invalid input'
            ]
        );
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}
