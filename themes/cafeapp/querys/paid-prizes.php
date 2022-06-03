<?php $v->layout("_theme");
/** @var \Source\Models\Lists $list */
?>

<div class="app_launch_header">
    <div class="app_flex_title">
        <h2>
            <a class="color_white font_60_percent icon-user padding_btn transition gradient gradient-green gradient-hover radius box-shadow"
               title="usuários">Prêmios Pagos</a>
        </h2>
    </div>
    <form class="ajax_off app_launch_form_filter app_form" id="filter_paid_prizes" action="<?= url('/consultas/filters'); ?>" method="post">

        <input autofocus type="text" list="search_stores" class="search_store" name="search_store" placeholder="Escolha uma Loja" autocomplete="off">
        <datalist id="search_stores">
            <?php foreach ((new \Source\Models\Store())->find()->fetch(true) as $store): ?>
                <option data-id="<?= $store->id; ?>" value="<?= $store->nome_loja; ?>">
            <?php endforeach; ?>
        </datalist>

        <input autofocus type="text" list="search_costs" name="search_cost" class="search_cost" placeholder="Escolha um Centro de Custo" autocomplete="off">
        <datalist id="search_costs">
            <?php foreach ((new \Source\Models\Center())->find('id IN(4,17,18)')->fetch(true) as $center): ?>
            <option data-id="<?= $center->id; ?>" value="<?= $center->description; ?>">
                <?php endforeach; ?>
        </datalist>

        <input autofocus type="text" list="search_hours" name="search_hour" class="search_hour" placeholder="Escolha um Horário" autocomplete="off">
        <datalist id="search_hours">
            <?php foreach ((new \Source\Models\Hour())->find()->fetch(true) as $hour): ?>
            <option value="<?= $hour->description; ?>">
                <?php endforeach; ?>
        </datalist>

        <input type="text" class="search_date radius mask-date"
               name="search_date" placeholder="Data de movimento">
        <button class="filter radius transition icon-filter icon-notext"></button>
    </form>

    <!--<div class="app_launch_btn expense radius transition icon-plus-circle" data-modalopen=".app_modal_expense">
        Botão sem função
    </div>
    <div class="app_launch_btn income radius transition icon-plus-circle" data-modalopen=".app_modal_list">
        Nova Lista
    </div>-->
</div>

<section class="app_launch_box">
    <div class="app_launch_item header">
        <p class="desc">Data de Movimento</p>
        <p class="desc">Descrição</p>
        <p class="desc">Centro de Custo</p>
        <p class="desc">Lojas</p>
        <p class="desc">Horário</p>
        <p class="price">Valor</p>
        <p class="desc_right"></p>
    </div>
    <?php if (isnt_empty($prizes, 'self')): ?>
        <?php foreach ($prizes as $prize): ?>
            <article class="app_launch_item">
                <p class="desc"><?= date_fmt($prize->date_moviment, 'd/m/Y'); ?></p>
                <p class="desc"><?= $prize->description; ?></p>
                <!--03 de 12-->
                <!--<span class="icon-exchange">Fixa</span>-->
                <p class="desc"><?= $prize->cost()->description; ?></p>
                <p class="desc"><?= $prize->store()->nome_loja; ?></p>
                <p class="desc"><?= $prize->hour()->description; ?></p>
                <p class="price"><?= money_fmt_br($prize->value); ?></p>
                <p class="desc_right gradient gradient-red font_80_percent gradient-hover transition radius">
                    <a class="color_white " style="text-decoration: none;"
                       href="<?= url("/app/fluxo-de-caixa/{$prize->id}") ?>">Editar</a>
                </p>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
    <div class=" app_launch_item footer">
        <p class="desc"></p>
        <p></p>
        <p class="font_80_percent jus">Total de Premios Pagos:</p>
        <p class=" font_80_percent icon-thumbs-o-up">R$ <?= money_fmt_br(($prize->totalPrizeExpense ?? "0")); ?></p>
    </div>
    <?= '';//$paginator;  ?>
</section>

<?= $v->start('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<?= $v->end(); ?>



