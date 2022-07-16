<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <?= $head; ?>

    <link rel="stylesheet" href="<?= theme("/assets/style.css", CONF_VIEW_APP); ?>"/>
    <link rel="icon" type="image/png" href="<?= theme("/assets/images/favicon.png", CONF_VIEW_APP); ?>"/>
</head>
<body>

<div class="ajax_load">
    <div class="ajax_load_box">
        <div class="ajax_load_box_circle"></div>
        <p class="ajax_load_box_title">Aguarde, carregando...</p>
    </div>
</div>

<div class="app">
    <header class="app_header">
        <h1><a class="icon-bar-chart transition" href="<?= url("/app"); ?>" title="IH">IHSistema</a></h1>
        <ul class="app_header_widget">

            <li class="radius transition icon-life-ring"><a href='<?= url('app'); ?>' style="text-decoration: none"
                                                            class="color_green">INÍCIO</a></li>
            <li data-mobilemenu="open" class="app_header_widget_mobile radius transition icon-menu icon-notext"></li>
        </ul>
    </header>

    <div class="app_box">
        <nav class="app_sidebar radius box-shadow">
            <div data-mobilemenu="close"
                 class="app_sidebar_widget_mobile radius transition icon-error icon-notext" ></div>
            <?= $v->insert("views/sidebar"); ?>
        </nav>

        <main class="app_main">
            <?= $v->section("content"); ?>
        </main>
    </div>

    <footer class="app_footer">
        <span class="icon-bar-chart">
            *<br>
            &copy; Todos os direitos reservados
        </span>
    </footer>
    <?= $v->insert("views/modals"); ?>
</div>

<script async src="https://www.googletagmanager.com/gtag/js?id=UA-53658515-18"></script>
<?php if (false/*!strpos(url(), "localhost")*/): ?>
    <script src="<?= theme("/assets/scripts.js", CONF_VIEW_APP); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<?php else: ?>
    <script src="<?= url("shared/scripts/jquery.min.js") ?>"></script>
    <script src="<?= url("shared/scripts/jquery.mask.js") ?>"></script>
    <script src="<?= url("shared/scripts/highcharts.js") ?>"></script>
    <script src="<?= url("shared/scripts/ajaxCad.js") ?>"></script>
<?= $v->section("scriptsup"); ?>
    <script src="<?= theme("/assets/js/scripts.js", CONF_VIEW_APP) ?>"></script>
    <script src="<?= theme("/assets/js/sidebar.js", CONF_VIEW_APP) ?>"></script>
    <script src="<?= theme("/assets/js/removes.js", CONF_VIEW_APP) ?>"></script>
    <script src="<?= theme("/assets/js/home.js", CONF_VIEW_APP) ?>"></script>
    <script src="<?= theme("/assets/js/jquery-mask.js", CONF_VIEW_APP) ?>"></script>
    <script src="<?= theme("/assets/js/hour-scripts.js", CONF_VIEW_APP) ?>"></script>
    <script src="<?= theme("/assets/js/query-filters.js", CONF_VIEW_APP) ?>"></script>
    <script src="<?= theme("/assets/js/regexp-daydate.js", CONF_VIEW_APP) ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<?php endif; ?>
<?= $v->section("scripts"); ?>
</body>
</html>