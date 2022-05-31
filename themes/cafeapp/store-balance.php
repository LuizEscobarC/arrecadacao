<?php $v->layout("_theme");
/** @var \Source\Models\Lists $list */
?>

<div class="app_launch_header">
    <div class="app_flex_title">
        <h2>
            <a class="color_white font_60_percent icon-user padding_btn transition gradient gradient-green gradient-hover radius box-shadow"
               title="usuários">Saldo do Movimento</a>
        </h2>
    </div>
    <form class="ajax_off app_launch_form_filter app_form" id="filter_store_balance" action="<?= url('consultas/filters'); ?>" method="post">

        <input type="hidden" name="route" value="consultar-saldo-da-loja">

        <input autofocus type="text" list="search_hours" name="search_hour" class="search_hour" placeholder="Escolha um Horário" autocomplete="off">
        <datalist id="search_hours">
            <?php foreach ((new \Source\Models\Hour())->find()->fetch(true) as $hour): ?>
            <option data-id="<?= $hour->id; ?>" value="<?= $hour->description; ?>"><?= $hour->week_day ?></option>
                <?php endforeach; ?>
        </datalist>

        <input list="datelist" type="text" value="<?= $search->search_date; ?>" class="radius search_date mask-date"
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
    <div class="font_120_percent app_launch_item footer app_launch_box_flex_basis_100">
        <p class="desc not"></p>
        <p></p>
        <p class="font_120_percent not">Saldo da Valor Recolhido Total:</p>
        <p class="font_120_percent icon-thumbs-o-up not"><?= money_fmt_br($allMoney->total_get_value, true); ?></p>
    </div>

    <div class="font_120_percent app_launch_item footer app_launch_box_flex_basis_100">
        <p class="desc"></p>
        <p></p>
        <p class="font_120_percent not">Valor Despesas Total:</p>
        <p class="font_120_percent icon-thumbs-o-up not"><?= money_fmt_br($allMoney->total_expend, true); ?></p>
    </div>
    <div class="font_120_percent app_launch_item footer app_launch_box_flex_basis_100">
        <p class="desc not"></p>
        <p></p>
        <p class="font_120_percent not">Valor Prêmio Total:</p>
        <p class="font_120_percent icon-thumbs-o-up not"><?= money_fmt_br($allMoney->total_prize, true); ?></p>   </div>

    <div class="font_120_percent app_launch_item footer app_launch_box_flex_basis_100">
        <p class="desc not"></p>
        <p></p>
        <p class="font_120_percent not">Valor Líquido Total:</p>
        <p class="font_120_percent icon-thumbs-o-up not"><?= money_fmt_br(($allMoney->total_get_value - ($allMoney->total_expend + $allMoney->total_prize)), true); ?></p>
    </div>
</section>

<?= $v->start('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<?= $v->end(); ?>

