<?php
ob_start();
require __DIR__ . "/vendor/autoload.php";

/**
 * BOOTSTRAP
 */

use CoffeeCode\Router\Router;
use Source\Core\Session;

$session = new Session();
$route = new Router(url(), ":");
$route->namespace("Source\App");

/*
 * WEB ROUTES
 */
$route->group(null);
$route->get("/", "Web:entrar");

//auth
$route->group(null);
$route->get("/entrar", "Web:login");
$route->post("/entrar", "Web:login");

//services
$route->group(null);
$route->get("/termos", "Web:terms");

/**
 * APP
 */
$route->group("/app");
$route->get("/", "App:home");
$route->get("/receber", "App:income");
$route->get("/pagar", "App:expense");
$route->get("/fatura/{invoice_id}", "App:invoice");

$route->get("/perfil/{id}", "App:profile");
$route->post("/perfil", "App:profile");
$route->get("/usuarios", "App:users");
$route->get("/usuarios/{page}", "App:users");
$route->post("/cadastrar", "App:register");
$route->get("/sair", "App:logout");

$route->get('/lojas', 'App:stores');
$route->get('/lojas', 'App:stores');

/** OPTIN */
$route->get("/obrigado/{email}", "App:success");


/*
 * ERROR ROUTES
 */
$route->group("/ops");
$route->get("/{errcode}", "Web:error");

/**
 * ROUTE
 */
$route->dispatch();

/**
 * ERROR REDIRECT
 */
if ($route->error()) {
    $route->redirect("/ops/{$route->error()}");
}

ob_end_flush();