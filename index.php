<?php
//autoloading classes
require_once __DIR__.'/vendor/autoload.php';

//fix cross origin blocked
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
if ($method == "OPTIONS") {
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
    header("HTTP/1.1 200 OK");
    die();
}

//events actions
use App\Controllers\EventController as EventController;
$event = new EventController;

//categories actions
use App\Controllers\CategoryController as CategoryController;
$category = new CategoryController;

//user actions
use App\Controllers\UserController as UserController;
$user = new UserController;

//break url to parts
$segments = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

if($_SERVER['REQUEST_METHOD'] === 'GET' && empty($segments[1])) {
    $event->index();
    exit;
}else if($_SERVER['REQUEST_METHOD'] === 'GET' && $segments[1] === 'events' && empty($segments[2])) {
    $event->index();
    exit;
}else if($_SERVER['REQUEST_METHOD'] === 'GET' && $segments[1] === 'event' && isset($segments[2])) {
    $event->show($segments[2]);
    exit;
}else if($_SERVER['REQUEST_METHOD'] === 'GET' && $segments[1] === 'events' && isset($segments[2]) && $segments[2] === 'category' && isset($segments[3])) {
    $event->eventsByCategory($segments[3]);
    exit;
}else if($_SERVER['REQUEST_METHOD'] === 'GET' && $segments[1] === 'categories' && empty($segments[2])) {
    $category->index();
    exit;
}else if($_SERVER['REQUEST_METHOD'] === 'POST' && $segments[1] === 'register' && empty($segments[2])) {
    $data = (array) json_decode(file_get_contents('php://input'), true);
    $user->register($data);
    exit;
}else if($_SERVER['REQUEST_METHOD'] === 'POST' && $segments[1] === 'login' && empty($segments[2])) {
    $data = (array) json_decode(file_get_contents('php://input'), true);
    $user->login($data);
    exit;
}else if($_SERVER['REQUEST_METHOD'] === 'POST' && $segments[1] === 'logout' && empty($segments[2])) {
    $data = (array) json_decode(file_get_contents('php://input'), true);
    $data['api_key'] = $_SERVER['HTTP_X_API_KEY'] ?? '';
    $user->logout($data);
    exit;
}else {
    http_response_code(404);
    echo json_encode([
        'error' => true,
        'message' => 'The page you are looking for does not exist.'
    ]);
}
