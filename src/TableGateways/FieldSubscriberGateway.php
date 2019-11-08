<?php

namespace Src\TableGateways;

class FieldSubscriberGateway
{

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        $statement = "
            SELECT
                id, subscriber, field
            FROM
                field_subscriber
            ORDER BY
                id
            DESC;
        ";

        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function find($id)
    {
        $statement = "
            SELECT
                id, subscriber, field
            FROM
                field_subscriber
            WHERE id = ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($id));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function findSubscriberFields($id)
    {
        $statement = "
            SELECT
                fs.id, fs.subscriber, f.title
            FROM
                field_subscriber fs
            INNER JOIN
				mailerlite.field f ON (f.id = fs.field)
            WHERE fs.subscriber = ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($id));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function insert(array $input)
    {
        $statement = "
            INSERT INTO field_subscriber
                (subscriber, field)
            VALUES
                (:subscriber, :field);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(
                array(
                    'subscriber' => $input['subscriber'],
                    'field' => $input['field'] ?? null
                )
            );
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function update($id, array $input)
    {
        $statement = "
            UPDATE field_subscriber
            SET
                subscriber = :subscriber,
                field = :field
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(
                array(
                    'id' => (int)$id,
                    'subscriber' => $input['subscriber'],
                    'field' => $input['field'] ?? null
                )
            );
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function delete($id)
    {
        $statement = "
            DELETE FROM field_subscriber
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('id' => $id));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}
