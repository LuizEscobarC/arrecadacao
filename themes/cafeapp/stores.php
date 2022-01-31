<?php $v->layout("_theme"); ?>
<div class="app_launch_header" xmlns="http://www.w3.org/1999/html">
    <div class="app_flex_title">
        <h2>
            <a class="color_white font_80_percent icon-user padding_btn transition gradient gradient-green gradient-hover radius box-shadow"
               title="usuários">Registros de Lojas</a></h2>
    </div>
    <form class="ajax_off app_launch_form_filter app_form" action="<?= url('/app/lojas') ?>" method="post">

        <input type="text" name="search" alt="pesquise por nome ou código" value="<?= $search; ?>"
               placeholder="Nome da loja/código">

        <input list="datelist" type="text" value="<?= $search; ?>" class="radius " name="day" placeholder="Dia da criação">
        <datalist id="datelist">
            <?php for ($range = 1; $range <= date('d'); $range++):
                $date = date("d", strtotime("{$range} day")); ?>
                <option <?= ($search == $range ? 'selected' : null); ?> value="<?= $range; ?>"/>
            <?php endfor; ?>
        </datalist>
        <button class="filter radius transition icon-search icon-notext"></button>

    </form>


    <!--<div class="app_launch_btn expense radius transition icon-plus-circle" data-modalopen=".app_modal_expense">
        Botão sem função
    </div>-->
    <div class="app_launch_btn income radius transition icon-plus-circle" data-modalopen=".app_modal_store">
        Nova loja
    </div>
</div>
<div class="ajax_response"><?= flash(); ?></div>

<section class="app_launch_box">
    <div class="app_launch_item header">
        <p class="wrap">Código</p>
        <p class="desc">Nome</p>
        <p class="price">Saldo</p>
        <p class="price">Comissão</p>
        <p class="price">Aluguel</p>
        <p class="price">Aluguel Dia Pagar</p>
        <p class="price">Gratificação</p>
        <p class="price">Gratificação dia Pagar</p>
        <p class="price"></p>
    </div>
    <?php
    foreach ($stores as $store):
        /** @var \Source\Models\Store $store */
        ?>
        <article class="app_launch_item">
            <p class="wrap"><?= $store->code; ?></p>
            <p class="desc app_invoice_link transition">
                <a title="Ver fatura" href="<?= url("/app/loja/{$store->id}"); ?>"><?= $store->nome_loja ?></a>
            </p>
            <p class="price"><?= money_fmt_br($store->valor_saldo, true); ?></p>
            <p class="price"><?= money_fmt_br($store->comissao, true); ?></p>
            <p class="price"><?= money_fmt_br($store->valor_aluguel, true); ?></p>
            <p class="date"><?= $store->aluguel_dia; ?></p>
            <p class="price"><?= money_fmt_br($store->valor_gratificacao, true); ?></p>
            <p class="date"><?= $store->gratificacao_dia; ?></p>
            <!-- <p class="enrollment">
                 <span class="icon-calendar-check-o">algo</span>
                 03 de 12
                 <span class="icon-exchange">Fixa</span>
             </p>-->
            <p class="wrap gradient gradient-red font_80_percent gradient-hover transition radius">
                <a class="color_white " style="text-decoration: none;" href="<?= url("/app/loja/{$store->id}") ?>">Editar</a>
            </p>
        </article>
    <?php endforeach; ?>


    <div class="app_launch_item footer">
        <p class="desc"></p>
        <p></p>
        <p></p>
        <p class="icon-calendar-check-o"></p>
        <p class="icon-thumbs-o-up"></p>
    </div>
    <?= ($paginator ?? null); ?>
</section>
