<?php $v->layout("_theme"); ?>
<div class="app_launch_header" xmlns="http://www.w3.org/1999/html">
    <div class="app_flex_title">
        <h2>
            <a class="color_white font_80_percent icon-user padding_btn transition
            gradient gradient-green gradient-hover radius box-shadow"
               title="usuários">R. de Lojas</a></h2>
    </div>

    <form class="ajax_off app_launch_form_filter app_form" action="<?= url('/app/lojas') ?>" method="post">

        <select name="store_situation">
            <option value="">Escolha</option>
            <option value="1">Lojas de Haver</option>
            <option vaLue="2">Lojas que Devem</option>
        </select>

        <input type="search" name="search" alt="pesquise por nome ou código" value="<?= $search; ?>"
               placeholder="Nome da loja/código" list="code_store" autocomplete="off"/>

        <datalist id="code_store">
            <option></option>
            <?php foreach ((new \Source\Models\Store())->find()->fetch(true) as $store): ?>
                <option><?= $store->nome_loja; ?></option>
                <option><?= $store->code; ?></option>
            <?php endforeach; ?>
        </datalist>

        <button class="filter radius transition icon-search icon-notext"></button>

    </form>


    <!--<div class="app_launch_btn expense radius transition icon-plus-circle" data-modalopen=".app_modal_expense">
        Botão sem função
    </div>
    <div class="app_launch_btn income radius transition icon-plus-circle" data-modalopen=".app_modal_store">
        Nova loja
    </div>-->
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
    if (isnt_empty($stores, 'self')):
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
    <?php endif; ?>

    <div class="font_80_percent app_launch_item footer">
        <p class="desc"></p>
        <p></p>
        <p class="font_80_percent">Saldo Total:</p>
        <p class="font_80_percent icon-thumbs-o-up"><?= money_fmt_br(($values->total ?? '0.0'), true); ?></p>
    </div>

    <div class="font_80_percent app_launch_item footer">
        <p class="desc"></p>
        <p></p>
        <p class="font_80_percent">Saldo Receita Total:</p>
        <p class="font_80_percent icon-thumbs-o-up"><?= money_fmt_br(($values->totalPositive  ?? '0.0'), true); ?></p>
    </div>

    <div class="font_80_percent app_launch_item footer">
        <p class="desc"></p>
        <p></p>
        <p class="font_80_percent">Saldo Despesa Total:</p>
        <p class="font_80_percent icon-thumbs-o-up"><?= money_fmt_br(($values->totalNegative ?? '0.0'), true); ?></p>
    </div>


    <?= ($paginator ?? null); ?>
</section>
