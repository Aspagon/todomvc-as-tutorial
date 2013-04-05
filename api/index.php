<?php

require 'Slim/Slim.php';

$app = new Slim();

$app->get('/todos', 'getTodos');
$app->get('/todos/:id',	'getTodo');
$app->get('/todos/search/:query', 'findByName');
$app->post('/todos', 'addTodo');
$app->put('/todos/:id', 'updateTodo');
$app->delete('/todos/:id', 'deleteTodo');

$app->run();

function getTodos() {
	$sql = "select * FROM todos ORDER BY title";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$todos['todos'] = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($todos);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function getTodo($id) {
	$sql = "SELECT * FROM todo WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$todo = $stmt->fetchObject();  
		$db = null;
		echo json_encode($todo); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function addTodo() {
	$request = Slim::getInstance()->request();
	$todo = json_decode($request->getBody());
	$sql = "INSERT INTO todos (title, iscompleted) VALUES (:title, :iscompleted)";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("title", $todo->todo->title);
		$stmt->bindParam("iscompleted", $todo->todo->iscompleted);
		$stmt->execute();
		$todo->id = $db->lastInsertId();
		$db = null;
		echo json_encode($todo); 
	} catch(PDOException $e) {
		error_log($e->getMessage(), 3, 'D:\xampp177\apache\logs\error.log');
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function updateTodo($id) {
	$request = Slim::getInstance()->request();
	$body = $request->getBody();
	$todo = json_decode($body);
	$sql = "UPDATE todos SET title=:title, iscompleted=:iscompleted WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("title", $todo->todo->title);
		$stmt->bindParam("iscompleted", $todo->todo->iscompleted);
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$db = null;
		getTodo($id);
		//echo json_encode($todo); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function deleteTodo($id) {
	$sql = "DELETE FROM todos WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$db = null;
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function findByTask($query) {
	$sql = "SELECT * FROM todo WHERE UPPER(task) LIKE :query ORDER BY name";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$query = "%".$query."%";  
		$stmt->bindParam("query", $query);
		$stmt->execute();
		$todos = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"todo": ' . json_encode($todos) . '}';
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function getConnection() {
	$dbhost="127.0.0.1";
	$dbuser="root";
	$dbpass="";
	$dbname="todos";
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}

?>