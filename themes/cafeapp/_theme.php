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


<div class="app">
    <header class="app_header">
        <h1><a class="icon-bar-chart transition" href="<?= url("/app"); ?>" title="IH">IHSistema</a></h1>
        <ul class="app_header_widget">

            <li class="radius transition icon-life-ring" > <a href='<?= url('app'); ?>' style="text-decoration: none" class="color_green">INÍCIO</a></li>
            <li data-mobilemenu="open" class="app_header_widget_mobile radius transition icon-menu icon-notext"></li>
        </ul>
    </header>

    <div class="app_box">
        <nav class="app_sidebar radius box-shadow">
            <div data-mobilemenu="close"
                 class="app_sidebar_widget_mobile radius transition icon-error icon-notext"></div>
            <?= $v->insert("views/sidebar"); ?>
        </nav>

        <main class="app_main">
            <?= $v->section("content"); ?>
        </main>
    </div>

    <footer class="app_footer">
        <span class="icon-bar-chart">
            IH<br>
            &copy; IHSistemas - Todos os direitos reservados
        </span>
    </footer>
    <?= $v->insert("views/modals"); ?>
</div>

<script async src="https://www.googletagmanager.com/gtag/js?id=UA-53658515-18"></script>
<script src="<?= theme("/assets/scripts.js", CONF_VIEW_APP); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<?= $v->section("scripts"); ?>
</body>
</html>