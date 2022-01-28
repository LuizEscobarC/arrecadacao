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
$route->get("/", "Web:login");

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

/** USER */
$route->get("/perfil/{id}", "App:profile");
$route->post("/perfil", "App:profile");
$route->get("/usuarios", "App:users");
$route->get("/usuarios/{page}", "App:users");
$route->post("/cadastrar", "App:register");
$route->post('/remove-user/{id}', 'App:removeUser');
$route->get("/sair", "App:logout");

/** STORES */
$route->get('/lojas', 'App:stores');
$route->get('/lojas/{page}', 'App:stores');
$route->get('/loja/{id}', 'App:store');
$route->post('/loja-salvar', 'App:storeSave');
$route->post('/remove-store/{id}', 'App:removeStore');


/** COST CENTER */
$route->get('/centros-de-custo', 'App:costCenters');
$route->get('/centros-de-custo/{page}', 'App:costCenters');
$route->get('/centro-de-custo/{id}', 'App:costCenter');
$route->post('/centro-salvar', 'App:saveCenter');
$route->post('/remove-center/{id}', 'App:removeCenter');


/** HOURS */
$route->get('/horarios', 'App:hours');
$route->get('/horarios/{page}', 'App:hours');
$route->get('/horario/{id}', 'App:hour');
$route->post('/horario', 'App:saveHour');
$route->post('/remove-hour/{id}', 'App:removeHour');



/** LISTS */
$route->post('/get_hour', 'App:getHour');
$route->get('/get_week_day/{id}', 'App:getWeekDay');
$route->get('/listas', 'App:lists');
$route->get('/listas/{page}', 'App:lists');
$route->get('/lista/{id}', 'App:list');
$route->post('/lista', 'App:saveList');
$route->post('/remove-list/{id}', 'App:removeList');



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