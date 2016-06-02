<?php
// Routes

$app->get('/', function ($request, $response, $args) {

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});


$app->get('/todo', function ($request, $response, $args) {
    include 'public/dbconn.php';
    include 'public/dbupdate.php';
    include 'public/formatResults.php';

    $model = new todoData($db);
    $successMessage = "";
    $todoList = $model->getSqlReminders();

    // For clarity
    $formStatus = "Pre-submission";
    // Render index view
    return $this->renderer->render($response, 'todo.phtml', ['todoList' => $todoList, 'successMessage' => $successMessage, 'formStatus' => $formStatus]);
});


//post todo
$app->post('/todo', function ($request, $response, $args) {
    include 'public/dbconn.php';
    include 'public/dbupdate.php';
    include 'public/formatResults.php';
    include 'public/controller.php';
    // instantiate the model handling class and the controller class
    $model = new todoData($db);
    $controller = new Controller();

    // Define whether to call an insert or update query
    $queryType = $controller->getQueryType();
    // Run an insert or update query, returns true or false
    $resultOfQuery = $model->selectAndRunSqlQuery($queryType);
    // set a success or failure message
    $successMessage = $controller->setSuccessMessage($resultOfQuery, $queryType);
    // Generate to do list
    $todoList = $model->getSqlReminders();

    // For clarity
    $formStatus = "<p>Post-submission</p>";
    // Render index view
    return $this->renderer->render($response, 'todo.phtml', ['todoList' => $todoList, 'successMessage' => $successMessage, 'formStatus' => $formStatus]);
});
// WOULD BE SIMPLER BY ADDING A NEW FILEPATH