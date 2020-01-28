<?php
namespace App\Model;

class Subtask
{
    protected $database;
    public function __construct(\PDO $database)
    {
        $this->database = $database;
    }
    public function getSubtasksByTaskId($taskId)
    {
        if (empty($taskId)) {
            // throw new error message
        }
        $sql = "SELECT * FROM subtasks WHERE task_id=:task_id";
        $stmt = $this->database->prepare($sql);
        $stmt->bindParam('task_id', $taskId);
        $stmt->execute();
        $subtasks = $stmt->fetchAll();
        if (empty($subtasks)) {
            // throw new error message
        }
        return $subtasks;
    }
    public function getSubtask($taskId)
    {
        if (empty($taskId)) {
           // throw new error message;
        }
        $sql = "SELECT * FROM subtasks WHERE id=:id";
        $stmt = $this->database->prepare($sql);
        $stmt->bindParam('id', $taskId);
        $stmt->execute();
        $subtask = $stmt->fetch();
        if (empty($subtask)) {
           // throw new error message
        }
        return $subtask;
    }
    public function createSubtask($data)
    {
       
        $sql = "INSERT INTO subtasks (name, status, task_id) VALUES (:name, :status, :task_id)";
        $stmt = $this->database->prepare($sql);
        $stmt->bindParam('name', $data['name']);
        $stmt->bindParam('status', $data['status']);
        $stmt->bindParam('task_id', $data['task_id']);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return $this->getSubtask($this->database->lastInsertId());
        } else {
           // throw new error message
        }
    }


    public function updateSubtask($data)
    {
        if (empty($data['subtask_id']) || empty($data['name']) || empty($data['status'])) {
           // throw new error message
        }
        $sql = "UPDATE subtasks SET name=:name, status=:status WHERE id=:id";

        // This will handle the check to see if the subtasks exists otherwise throw an error
        $this->getSubtask($data['subtask_id']);

        $stmt = $this->database->prepare($sql);
        $stmt->bindParam('id', $data['subtask_id']);
        $stmt->bindParam('name', $data['name']);
        $stmt->bindParam('status', $data['status']);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return $this->getSubtask($data['subtask_id']);
        } else {
           // throw new error message
        }
    }


    public function deleteSubtask($subtask_id)
    {
        if (empty($subtask_id)) {
           // throw new error message
        }
        $sql = "DELETE FROM subtasks WHERE id=:id";

          // This will handle the check to see if the subtasks exists otherwise throw an error
        $this->getSubtask($subtask_id);

        $stmt = $this->database->prepare($sql);
        $stmt->bindParam('id', $subtask_id);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return ['message' => 'The Subtask was deleted.'];
        } else {
          // throw new error message
        }
    }
}