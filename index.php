<?php
ob_start('ob_gzhandler');
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

$route->namespace("Source\App\Store");

/** STORES */
$route->get('/lojas', 'StoreController:stores');
$route->get('/cadastrar-loja', 'StoreController:createStore');
$route->get('/lojas/{page}', 'StoreController:stores');
$route->post('/lojas', 'StoreController:stores');
$route->get('/loja/{id}', 'StoreController:store');
$route->post('/loja-salvar', 'StoreController:saveStore');
$route->post('/remove-store/{id}', 'StoreController:removeStore');

$route->namespace("Source\App");

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
$route->post('/remove-lists/{id}', 'App:removeAllLists');

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

/** RELATÓRIOS */
$route->group('relatorios');
$route->post('/fechamento-de-caixa', 'App:boxClosing');

/** LISTS SEARCH */
$route->group('/consultas');
$route->post('/filters', 'Query:filters');
// PRIZE
$route->get('/premios-pagos', 'Query:paidPrizes');
$route->get('/premios-pagos/{cost}/{date}/{store}/{hour}', 'Query:paidPrizes');
// INCOME
$route->get('/lancamento-de-entradas', 'Query:attachIncome');
$route->post('/lancamento-de-entradas', 'Query:attachIncome');
// EXPENSE
$route->get('/despesas-pagas', 'Query:attachExpense');
$route->post('/despesas-pagas', 'Query:attachExpense');
// STORE BALANCE
$route->get('/consultar-saldo-da-loja', 'Query:storeBalance');
$route->get('/consultar-saldo-da-loja/{search_date}/{search_store}/{search_hour}', 'Query:storeBalance');


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