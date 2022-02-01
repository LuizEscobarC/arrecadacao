<?php $v->layout("_theme");
/** @var \Source\Models\Lists $list */
?>

<div class="app_launch_header">
    <div class="app_flex_title">
        <h2>
            <a class="color_white font_80_percent icon-user padding_btn transition gradient gradient-green gradient-hover radius box-shadow"
               title="usuários">Registros de lançamentos</a></h2>
    </div>
    <!-- <form class="ajax_off app_launch_form_filter app_form" action="<?= url('/app/listas'); ?>" method="post">
    </form> -->

    <!--<div class="app_launch_btn expense radius transition icon-plus-circle" data-modalopen=".app_modal_expense">
        Botão sem função
    </div>-->
    <div class="app_launch_btn income radius transition icon-plus-circle" data-modalopen=".app_modal_cash">
        Novo lançamento
    </div>
</div>

<section class="app_launch_box">
    <div class="app_launch_item header">
        <p class="wrap">ID</p>
        <p class="wrap">dia da semana</p>
        <p class="desc">descrição lançamento</p>
        <p class="desc">Horário desejado</p>
        <p class="desc">Centro de Custo</p>
        <p class="date">Horário de Movimentação</p>
        <p class="desc">Loja</p>
        <p class="price">Valor de movimento</p>
        <p class="desc">Tipo de movimento</p>
        <p class="wrap"></p>
    </div>
    <?php foreach ($cashFlows as $cash): ?>
        <article class="app_launch_item">
            <p class="wrap"><?= $cash->id; ?></p>
            <p class="wrap app_invoice_link transition">
                <a title="Ver Lista" href="<?= url("/app/horario/{$cash->id}"); ?>"><?= $cash->week_day; ?></a>
            </p>
            <p class="desc font_80_percent"><?= str_limit_words($cash->description, 4); ?></p>
            <p class="desc"><?= $cash->hour; ?></p>
            <p class="desc"><?= $cash->cost; ?></p>
            <p class="date"><?= date_fmt($cash->date_moviment, 'd/m/Y') . ' ' . $cash->week_day; ?></p>
            <p class="desc"><?= $cash->nome_loja; ?></p>
            <p class="price">
                <span>R$</span>
                <span><?= money_fmt_br($cash->value); ?></span>
            </p>
            <p class="desc">
                <span><?= ($cash->type == 1 ? 'Entrada' :  'Saída' );?></span>
            </p>
            <p class="wrap gradient gradient-red font_80_percent gradient-hover transition radius">
                <a class="color_white " style="text-decoration: none;" href="<?= url("/app/fluxo-de-caixa/{$cash->id}") ?>">Editar</a>
            </p>
        </article>
    <?php endforeach; ?>
    <div class="app_launch_item footer">
        <p class="desc"></p>
        <p></p>
        <p>Valor total:</p>
        <p class="icon-thumbs-o-up">R$ <?= money_fmt_br($allMoney->value); ?></p>
    </div>
    <?= $paginator; ?>
</section>

<?= $v->start('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<?= $v->end(); ?>
