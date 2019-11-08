<?php

namespace Src\TableGateways;

class SubscriberGateway
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
                su.id, su.name, su.email, st.state
            FROM
                subscriber su
            LEFT JOIN
                state as st ON (su.state = st.id)
            ORDER BY
                su.id DESC
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
                id, name, email, state
            FROM
                subscriber su
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

    public function insert(array $input)
    {
        $statement = "
            INSERT INTO subscriber
                (name, email, state)
            VALUES
                (:name, :email, :state);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(
                array(
                    'name' => $input['name'],
                    'email' => $input['email'],
                    'state' => $input['state'] ?? null
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
            UPDATE subscriber
            SET
                name = :name,
                email  = :email,
                state = :state
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(
                array(
                    'id' => (int)$id,
                    'name' => $input['name'],
                    'email' => $input['email'],
                    'state' => $input['state'] ?? null
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
            DELETE FROM subscriber
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
