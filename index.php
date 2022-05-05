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
$route->post("/app/ajax_grap", "App:ajaxGrap");

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
$route->get("/cadastrar-usuario", "App:createUser");
$route->post("/perfil", "App:profile");
$route->get("/usuarios", "App:users");
$route->get("/usuarios/{page}", "App:users");
$route->post("/cadastrar", "App:register");
$route->post('/remove-user/{id}', 'App:removeUser');
$route->get("/sair", "App:logout");

/** STORES */
$route->get('/lojas', 'App:stores');
$route->get('/cadastrar-loja', 'App:createStore');
$route->get('/lojas/{page}', 'App:stores');
$route->post('/lojas', 'App:stores');
$route->get('/loja/{id}', 'App:store');
$route->post('/loja-salvar', 'App:saveStore');
$route->post('/remove-store/{id}', 'App:removeStore');


/** COST CENTER */
$route->get('/centros-de-custo', 'App:costCenters');
$route->get('/cadastrar-centro-de-custo', 'App:createCost');
$route->get('/centros-de-custo/{page}', 'App:costCenters');
$route->post('/centros-de-custo', 'App:costCenters');
$route->get('/centro-de-custo/{id}', 'App:costCenter');
$route->post('/centro-salvar', 'App:saveCenter');
$route->post('/remove-center/{id}', 'App:removeCenter');


/** HOURS */
$route->get('/horarios', 'App:hours');
$route->get('/cadastrar-horario', 'App:createHour');
$route->get('/horarios/{page}', 'App:hours');
$route->get('/horario/{id}', 'App:hour');
$route->post('/horario', 'App:saveHour');
$route->post('/remove-hour/{id}', 'App:removeHour');


/** LISTS */
$route->get('/listas', 'App:lists');
$route->get('/cadastrar-lista', 'App:createList');
$route->get('/listas/{page}', 'App:lists');
$route->post('/listas', 'App:lists');
$route->get('/lista/{id}', 'App:list');
$route->post('/lista', 'App:saveList');
$route->post('/remove-list/{id}', 'App:removeList');

/** FINANCES */
$route->get('/fluxos-de-caixa', 'App:cashFlows');
$route->get('/cadastrar-fluxo-de-caixa', 'App:createCashFlow');
$route->get('/fluxos-de-caixa/{page}', 'App:cashFlows');
$route->post('/fluxos-de-caixa', 'App:cashFlows');
$route->get('/fluxo-de-caixa/{id}', 'App:cashFlow');
$route->post('/fluxo-de-caixa', 'App:saveCashFlow');
$route->post('/remove-cash-flow/{id}', 'App:removeCashFlow');

/** MOVIMENTS */
$route->get('/movimentacoes', 'App:movimentations');
$route->get('/cadastrar-movimentacao', 'App:createMoviment');
$route->get('/movimentacoes/{page}', 'App:movimentations');
$route->post('/movimentacoes', 'App:movimentations');
$route->get('/movimentacao/{id}', 'App:moviment');
$route->post('/movimentacao', 'App:saveMoviment');
$route->post('/remove-moviment/{id}', 'App:removeMoviment');


/** SHARED */
$route->post('/moviment_verify', 'App:movimentVerify');
$route->post('/get_hour', 'App:getHour');
$route->post('/get_list', 'App:getList');
$route->post('/get_store', 'App:getStore');
$route->get('/get_week_day/{id}', 'App:getWeekDay');
$route->post('/get-moviment', 'App:getMoviment');

/** OPTIN */
$route->get("/obrigado/{email}", "App:success");


/** CONFIGS */
$route->group('/configuracoes');
$route->get('/horario', 'Config:closeHour');
$route->post('/fechamento-de-horario/{id_hour}', 'Config:closeHour');
$route->post('/abate-de-lojas-inadimplentes', 'Config:calcStore');

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