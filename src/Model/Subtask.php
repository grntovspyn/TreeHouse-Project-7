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
        $stmt = $this->database->prepare('SELECT * FROM subtasks WHERE task_id=:task_id');
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
        $stmt = $this->database->prepare('SELECT * FROM subtasks WHERE id=:id');
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
        if (empty($data['task_id']) || empty($data['name']) || empty($data['status'])) {
            // throw new error message
        }
        $stmt = $this->database->prepare('INSERT INTO Subtasks (task_id, name, status) VALUES (:task_id, :name, :status)');
        $stmt->bindParam('task_id', $data['task_id']);
        $stmt->bindParam('name', $data['name']);
        $stmt->bindParam('status', $data['status']);
        $stmt->execute();
        if ($stmt->rowCount()>0) {
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
        $this->getSubtask($data['subtask_id']);
        $stmt = $this->database->prepare('UPDATE Subtasks SET name=:name, status=:status WHERE id=:id');
        $stmt->bindParam('id', $data['subtask_id']);
        $stmt->bindParam('name', $data['name']);
        $stmt->bindParam('status', $data['status']);
        $stmt->execute();
        if ($stmt->rowCount()>0) {
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
        $this->getSubtask($subtask_id);
        $stmt = $this->database->prepare('DELETE FROM Subtasks WHERE id=:id');
        $stmt->bindParam('id', $subtask_id);
        $stmt->execute();
        if ($stmt->rowCount()>0) {
            return ['message' => 'The Subtask was deleted.'];
        } else {
          // throw new error message
        }
    }
}