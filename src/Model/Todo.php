<?php

namespace App\Model;

class Todo
{
    protected $database;
    public function __construct(\PDO $database)
    {
        $this->database = $database;
    }

    public function getTasks()
    {
        $sql = "SELECT * FROM tasks ORDER BY id";
        $stmt = $this->database->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTask($taskId)
    {
        $sql = "SELECT * FROM tasks WHERE id = :id";
        $stmt = $this->database->prepare($sql);
        $stmt->bindParam('id', $taskId);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function createTask($data)
    {
        $sql = "INSERT INTO tasks(task, status) VALUES(:task, :status)";
        $stmt = $this->database->prepare($sql);
        $stmt->bindParam('task', $data['task']);
        $stmt->bindParam('status', $data['status']);
        $stmt->execute();
        if($stmt->rowCount() > 0){
            return $this->getTask($this->database->lastInsertId());
        } else{
           // throw an error
        }
    }

    public function updateTask($data)
    {
        $sql = "UPDATE tasks SET task = :task, status = :status WHERE id=:id";
        $stmt = $this->database->prepare($sql);
        $stmt->bindParam('task', $data['task']);
        $stmt->bindParam('status', $data['status']);
        $stmt->bindParam('id', $data['task_id']);
        $stmt->execute();
        if($stmt->rowCount() > 0){
            return $this->getTask($data['task_id']);
        } else {
            //throw new error message
        }
    }

    public function deleteTask($taskId)
    {
        // This will handle the check to see if the task exists otherwise throw an error
        $this->getTask($taskId);
        
        $sql = "DELETE FROM tasks WHERE id = :id";
        $stmt = $this->database->prepare($sql);
        $stmt->bindParam('id', $taskId);

        // To enforce cascading in SQLITE
        $this->database->exec( 'PRAGMA foreign_keys = ON;' );
        
        $stmt->execute();
        if($stmt->rowCount() > 0){
            return ['message' => 'Task deleted successfully'];
        } else{
            //throw new error message
        }
    }

}