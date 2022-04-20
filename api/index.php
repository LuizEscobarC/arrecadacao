<?php
ob_start();

require __DIR__ . "/../vendor/autoload.php";

/**
 * BOOTSTRAP
 */

use CoffeeCode\Router\Router;

/**
 * API ROUTES
 * index
 */
$route = new Router(url(), ":");
$route->namespace("Source\App\Api");

//user
$route->group("/users");
$route->get("/", "UserApi:index");
$route->post("/", "UserApi:create");
$route->get("/{user_id}", "UserApi:read");
$route->put("/{user_id}", "UserApi:update");
$route->delete("/{user_id}", "UserApi:delete");

//stores
$route->group("/stores");
$route->get("/", "StoreApi:index");
$route->post("/", "StoreApi:create");
$route->get("/{store_id}", "StoreApi:read");
$route->put("/{store_id}", "StoreApi:update");
$route->delete("/{store_id}", "StoreApi:delete");

/**
 * ROUTE
 */
$route->dispatch();

/**
 * ERROR REDIRECT
 */
if ($route->error()) {
    header('Content-Type: application/json; charset=UTF-8');
    http_response_code(404);

    echo json_encode([
        "errors" => [
            "type " => "endpoint_not_found",
            "message" => "Não foi possível processar a requisição"
        ]
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}

ob_end_flush();