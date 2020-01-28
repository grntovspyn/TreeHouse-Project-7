<?php
/** Project heavily influenced by training videos from Alena Holligan at
 * https://teamtreehouse.com/library/build-a-rest-api-with-php
 * 
 */


use Slim\Http\Request;
use Slim\Http\Reponse;
// Routes 

$app->get('/', function ($request, $response, $args) {
    $endpoints = [
        'all tasks' => $this->api['api_url'].'/todos',
        'single task' => $this->api['api_url'].'/todos/{task_id}',
        'subtasks by task' => $this->api['api_url'].'/todos/{task_id}/subtasks',
        'single subtask' => $this->api['api_url'].'/todos/{task_id}/subtasks/{subtask_id}',
        'help' => $this->api['base_url'].'/',
    ];
    $results = [
        'endpoints' => $endpoints,
        'version' => $this->api['version'],
        'timestamp' => time(),
    ];
    return $response->withJson($results, 200, JSON_PRETTY_PRINT);
});

$app->group('/api/v1/todos', function () use($app) {
    $app->get('', function (Request $request, $response, $args) {
        $results = $this->todo->getTasks();
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    });
    $app->get('/{task_id}', function (Request $request, $response, $args) {
        $results = $this->todo->getTask($args['task_id']);
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    });
    $app->post('', function (Request $request, $response, $args) {
        $results = $this->todo->createTask($request->getParsedBody());
        return $response->withJson($results, 201, JSON_PRETTY_PRINT);
    });
    $app->put('/{task_id}', function (Request $request, $response, $args) {
        $data = $request->getParsedBody();
        $data['task_id'] = $args['task_id'];
        $results = $this->todo->updateTask($data);
        return $response->withJson($results, 201, JSON_PRETTY_PRINT);
    });
    $app->delete('/{task_id}', function (Request $request, $response, $args) {
        $results = $this->todo->deleteTask($args['task_id']);
        return $response->withJson($results, 200, JSON_PRETTY_PRINT);
    });
    $app->group('/{task_id}/subtasks', function () use($app) {
        $app->get('', function (Request $request, $response, $args) {
            $results = $this->subtasks->getSubtasksByTaskId($args['task_id']);
            return $response->withJson($results, 200, JSON_PRETTY_PRINT);
        });
        $app->get('/{subtask_id}', function (Request $request, $response, $args) {
            $results = $this->subtasks->getSubtask($args['subtask_id']);
            return $response->withJson($results, 200, JSON_PRETTY_PRINT);
        });
        $app->post('', function (Request $request, $response, $args) {
            $data = $request->getParsedBody();
            $data['task_id'] = $args['task_id'];
            $results = $this->subtasks->createSubtask($data);
            return $response->withJson($results, 201, JSON_PRETTY_PRINT);
        });
        $app->put('/{subtask_id}', function (Request $request, $response, $args) {
            $data = $request->getParsedBody();
            $data['task_id'] = $args['task_id'];
            $data['subtask_id'] = $args['subtask_id'];
            $results = $this->subtasks->updateSubtask($data);
            return $response->withJson($results, 201, JSON_PRETTY_PRINT);
        });
        $app->delete('/{subtask_id}', function (Request $request, $response, $args) {
            $results = $this->subtasks->deleteSubtask($args['subtask_id']);
            return $response->withJson($results, 200, JSON_PRETTY_PRINT);
        });
    });
});


