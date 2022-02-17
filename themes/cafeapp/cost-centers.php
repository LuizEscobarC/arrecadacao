<?php $v->layout("_theme"); ?>

<div class="app_launch_header">
    <div class="app_flex_title">
        <h2>
            <a class="color_white font_80_percent icon-user padding_btn transition gradient gradient-green gradient-hover radius box-shadow"
               title="usuários">Registros de centro de custos</a></h2>
    </div>
    <form class="ajax_off app_launch_form_filter app_form" action="<?= url('/app/centros-de-custo'); ?>" method="post">
        <input list="datelist" type="text" value="<?= $search; ?>" class="radius mask-day" name="day"
               placeholder="Data de movimento">
        <datalist id="datelist">
            <?php for ($range = 1; $range <= date('d'); $range++):
                $date = date("d", strtotime("{$range} day")); ?>
                <option <?= ($search == $range ? 'selected' : null); ?> value="<?= $range; ?>"/>
            <?php endfor; ?>
        </datalist>
        <button class="filter radius transition icon-filter icon-notext"></button>
    </form>

    <!--<div class="app_launch_btn expense radius transition icon-plus-circle" data-modalopen=".app_modal_expense">
        Botão sem função
    </div>
    <div class="app_launch_btn income radius transition icon-plus-circle" data-modalopen=".app_modal_cost">
        Novo centro de custo
    </div>-->
</div>
<div class="ajax_response"><?= flash(); ?></div>

<section class="app_launch_box">
    <div class="app_launch_item header">
        <p class="desc">descrição</p>
        <p class="emit">Emitir</p>
        <p class="price">Data de Movimento</p>
        <p class="desc_right"></p>
    </div>
    <?php if (isnt_empty($costCenters, 'self')): ?>
        <?php foreach ($costCenters as $center): ?>
            <article class="app_launch_item">
                <p class="desc app_invoice_link transition">
                    <a title="Ver fatura"
                       href="<?= url("/app/centro-de-custo/{$center->id}") ?>"><?= str_limit_words($center->description,
                            3); ?></a>
                </p>
                <p class="emit"><?= $center->emit; ?></p>
                <p class="date"><?= date_fmt_br($center->created_at) ?></p>
                <!--03 de 12-->
                <!--<span class="icon-exchange">Fixa</span>-->
                <p class="desc_right cost gradient gradient-red font_80_percent gradient-hover transition radius">
                    <a class="color_white " style="text-decoration: none;"
                       href="<?= url("/app/centro-de-custo/{$center->id}") ?>">Editar</a>
                </p>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
    <div class="app_launch_item footer">
        <p class="desc"></p>
        <p></p>
        <p></p>
    </div>
    <?= $paginator; ?>
</section>
