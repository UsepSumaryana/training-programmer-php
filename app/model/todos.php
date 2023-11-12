<?php

namespace App\Model;

use PDO;
use App\Connection\Database as Db;

class Todos
{

    private $conn;

    public $id;
    public $title;
    public $description;
    public $userId;
    public $status;
    public $dueDate;
    public $createdAt;
    public $attachment;

    public function __construct()
    {
        $db = new Db();
        $this->conn = $db->conn;
    }

    private function mapper($data)
    {
        $this->id = $data->id;
        $this->title = $data->title;
        $this->description = $data->description;
        $this->userId = $data->user_id;
        $this->status = $data->status;
        $this->dueDate = $data->due_date;
        $this->createdAt = $data->created_at;
        $this->attachment = $data->attachment;
    }

    public function getAll()
    {
        $stmt = $this->conn->query('SELECT * FROM todos');
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getById($id)
    {

        try {
            $stmt = $this->conn->prepare('SELECT * FROM todos WHERE id = :id');
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_OBJ);

            $this->mapper($result);

            return $this;
        } catch (\PDOException $e) {

            echo $e->getMessage();
            return null;
        }
    }

    public function save($id = null)
    {
        try {

            if (empty($id)) {
                $stmt = $this->conn->prepare('INSERT INTO
                todos (title, description,  user_id, due_date, attachment)
                VALUES (:title, :desc, :userId, :dueDate, :attachment)');

                $stmt->bindValue(':title', $this->title);
                $stmt->bindValue(':desc', $this->description);
                $stmt->bindValue(':userId', $this->userId);
                $stmt->bindValue(':dueDate', $this->dueDate);
                $stmt->bindValue(':attachment', $this->attachment);
            } else {

                $sql = "UPDATE todos
                    SET
                    title = :title,description = :desc, due_date = :due_date, status = :status, attachment = :attachment
                    WHERE id = :id";
                $stmt = $this->conn->prepare($sql);

                $stmt->bindParam(':id', $this->id);
                $stmt->bindParam(':title', $this->title);
                $stmt->bindParam(':desc', $this->description);
                $stmt->bindParam(':due_date', $this->dueDate);
                $stmt->bindParam(':status', $this->status);
                $stmt->bindParam(':attachment', $this->attachment);
            }

            $stmt->execute();
        } catch (\PDOException $e) {

            echo $e->getMessage();
            return null;
        }
    }

    public function getUserTodos($userId)
    {

        try {
            $sql = "SELECT
                        id, title, description, due_date, status, attachment, created_at
                    FROM todos WHERE user_id = :user_id";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':user_id', $userId);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {

            echo $e->getMessage();
            return null;
        }
    }

    public function delete($id)
    {
        try {
            $stmt = $this->conn->prepare('DELETE FROM todos WHERE id = :id');

            $stmt->bindValue(':id', $id);
            if ($stmt->execute()) {
                return true;
            }
        } catch (\PDOException $e) {

            echo $e->getMessage();
        }

        return false;
    }
}
