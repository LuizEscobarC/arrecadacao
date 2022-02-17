<?php $v->layout("_theme");
/** @var \Source\Models\Lists $list */
?>

<div class="app_launch_header">
    <div class="app_flex_title">
        <h2>
            <a class="color_white font_80_percent icon-user padding_btn transition gradient gradient-green gradient-hover radius box-shadow"
               title="usuários">R. de lançamentos</a></h2>
    </div>
    <form class="ajax_off app_launch_form_filter app_form" action="<?= url('/app/fluxos-de-caixa'); ?>" method="post">

        <select name="search_store" class="select2Input operator">
            <option value="">
                &ofcir; Selecione uma loja
            </option>
            <?php foreach ((new \Source\Models\Store())->find()->fetch(true) as $store): ?>
                <option <?= (($search->search_store) == $store->nome_loja ? 'selected' : ""); ?>
                        value="<?= $store->nome_loja; ?>">
                    &ofcir; <?= $store->nome_loja; ?></option>
            <?php endforeach; ?>
        </select>

        <select name="search_hour" class="select2Input operator">
            <option value="">
                &ofcir; Selecione um horário
            </option>
            <?php foreach ((new \Source\Models\Hour())->find()->fetch(true) as $hour): ?>
                <option <?= ($search->search_hour == $hour->description ? 'selected' : ""); ?>
                        value="<?= $hour->description; ?>">
                    &ofcir; <?= $hour->description; ?></option>
            <?php endforeach; ?>
        </select>

        <input list="datelist" type="text" value="<?= $search->search_date; ?>" class="radius mask-date"
               name="search_date" placeholder="Data de Movimento">
        <datalist id="datelist">
            <?php for ($range = 1; $range <= 30; $range++):
                $date = date("d/m/Y", strtotime("+{$range} month")); ?>
                <option <?= ($search->search_date == $date ? 'selected' : null); ?> value="<?= $date; ?>"/>
            <?php endfor; ?>
        </datalist>
        <button class="filter radius transition icon-filter icon-notext"></button>
    </form>


    <!--<div class="app_launch_btn expense radius transition icon-plus-circle" data-modalopen=".app_modal_expense">
        Botão sem função
    </div>
    <div class="app_launch_btn income radius transition icon-plus-circle" data-modalopen=".app_modal_cash">
        Novo lançamento
    </div>-->
</div>
<div class="ajax_response"><?= flash(); ?></div>


<section class="app_launch_box">
    <div class="app_launch_item header">
        <p class="wrap">ID</p>
        <p class="date">Horário de Movimentação</p>
        <p class="wrap">dia da semana</p>
        <p class="desc">Horário desejado</p>
        <p class="desc">Loja</p>
        <p class="desc">Centro de Custo</p>
        <p class="price">Valor de movimento</p>
        <p class="desc">descrição lançamento</p>
        <p class="desc">Tipo de movimento</p>
        <p class="wrap"></p>
    </div>
    <?php if (isnt_empty($cashFlows, 'self')): ?>
        <?php foreach ($cashFlows as $cash): ?>
            <article class="app_launch_item">
                <p class="wrap"><?= $cash->id; ?></p>
                <p class="date"><?= date_fmt($cash->date_moviment, 'd/m/Y') . ' ' . $cash->week_day; ?></p>
                <p class="wrap app_invoice_link transition">
                    <a title="Ver Lista" href="<?= url("/app/horario/{$cash->id}"); ?>"><?= $cash->week_day; ?></a>
                </p>
                <p class="desc"><?= $cash->hour; ?></p>
                <p class="desc"><?= $cash->nome_loja; ?></p>
                <p class="desc"><?= $cash->cost; ?></p>
                <p class="price">
                    <span>R$</span>
                    <span><?= money_fmt_br($cash->value); ?></span>
                </p>
                <p class="desc font_80_percent"><?= str_limit_words($cash->description, 4); ?></p>
                <p class="desc">
                <span <?= ($cash->type == 1 ? ' title="Receber" class="check income color_green icon-thumbs-o-up transition"' :
                    ' title="Receber" class="check income icon-thumbs-o-down color_red transition"'); ?>><?= ($cash->type == 1 ?
                        'Entrada' : 'Saída'); ?></span>
                </p>
                <p class="wrap gradient gradient-red font_80_percent gradient-hover transition radius">
                    <a class="color_white " style="text-decoration: none;"
                       href="<?= url("/app/fluxo-de-caixa/{$cash->id}") ?>">Editar</a>
                </p>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
    <div class="font_80_percent app_launch_item footer">
        <p class="desc"></p>
        <p></p>
        <p class="font_80_percent">Valor total:</p>
        <p class="font_80_percent icon-thumbs-o-up"><?= money_fmt_br($allMoney); ?></p>
    </div>
    <?= $paginator; ?>
</section>

<?= $v->start('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<?= $v->end(); ?>
