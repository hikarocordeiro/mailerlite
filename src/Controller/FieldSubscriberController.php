<?php

namespace Src\Controller;

use Src\TableGateways\FieldSubscriberGateway;
use Src\TableGateways\SubscriberGateway;

class FieldSubscriberController
{

    private $db;
    private $requestMethod;
    private $id;

    private $fieldSubscriberGateway;

    public function __construct($db, $requestMethod, $id)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->id = $id;

        $this->fieldSubscriberGateway = new FieldSubscriberGateway($db);
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
        $result = $this->fieldSubscriberGateway->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function get($id)
    {
        $result = $this->fieldSubscriberGateway->findSubscriberFields($id);
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
        $this->fieldSubscriberGateway->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
    }

    private function updateFromRequest($id)
    {
        $result = $this->fieldSubscriberGateway->find($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $input = (array)json_decode(file_get_contents('php://input'), true);
        if (!$this->validate($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->fieldSubscriberGateway->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function delete($id)
    {
        $result = $this->fieldSubscriberGateway->find($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $this->fieldSubscriberGateway->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function validate($input)
    {
        if (!isset($input['subscriber'])) {
            return false;
        }
        if (!isset($input['field'])) {
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
