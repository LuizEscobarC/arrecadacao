<?php $v->layout("_theme");
/** @var \Source\Models\SelfList $list */
?>

<div class="app_launch_header">
    <div class="app_flex_title">
        <h2>
            <a class="color_white font_80_percent icon-user padding_btn transition
            gradient gradient-green gradient-hover radius box-shadow"
               title="usuários">R. de Listas</a></h2>
    </div>
    <form class="ajax_off app_launch_form_filter app_form" action="<?= url('/app/listas'); ?>" method="post">

        <select name="search_store" class="select2Input operator">
            <option value="0">
                &ofcir; Selecione uma loja
            </option>
            <?php foreach ((new \Source\Models\Store())->find()->fetch(true) as $store): ?>
                <option <?= ($search->search_store == $store->nome_loja ? 'selected' : ""); ?>
                        value="<?= $store->nome_loja; ?>">
                    &ofcir; <?= $store->nome_loja; ?></option>
            <?php endforeach; ?>
        </select>

        <select name="search_hour" class="select2Input operator">
            <option value="0">
                &ofcir; Selecione um horário
            </option>
            <?php foreach ((new \Source\Models\Hour())->find()->fetch(true) as $hour): ?>
                <option <?= ($search->search_hour == $hour->description ? 'selected' : ""); ?>
                        value="<?= $hour->description; ?>">
                    &ofcir; <?= $hour->description; ?></option>
            <?php endforeach; ?>
        </select>

        <input list="datelist" type="text" value="<?= $search->search_date; ?>" class="radius mask-date"
               name="search_date" placeholder="Data de movimento">
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
    <div class="app_launch_btn income radius transition icon-plus-circle" data-modalopen=".app_modal_list">
        Nova Lista
    </div>-->
</div>

<section class="app_launch_box">
    <div class="app_launch_item header">
        <p class="wrap font_80_percent list">dia da semana</p>
        <p class="desc font_80_percent list">Horário desejado</p>
        <p class="date font_80_percent list">Horário de Movimentação</p>
        <p class="desc font_80_percent list">Loja</p>
        <p class="price font_80_percent list">Valor da Lista</p>
        <p class="price font_80_percent list">Valor Total das Listas</p>
        <p class="desc_center font_80_percent list">Valor Líquido</p>
        <p class="desc_center font_80_percent list">Valor de Comissão</p>
        <p class="wrap font_80_percent list"></p>
    </div>
    <?php if (isnt_empty($lists, 'self')): ?>
        <?php foreach ($lists as $list): ?>
            <article class="app_launch_item font_80_percent">
                <p class="wrap app_invoice_link transition list">
                    <a title="Ver Lista" href="<?= url("/app/horario/{$list->id}"); ?>"><?= $list->week_day; ?></a>
                </p>
                <!--03 de 12-->
                <!--<span class="icon-exchange">Fixa</span>-->
                <p class="desc  list"><?= $list->description; ?></p>
                <p class="date list"><?= date_fmt($list->date_moviment, 'd/m/Y') . ' ' . $list->week_day; ?></p>
                <p class="category list"><?= $list->nome_loja; ?></p>
                <p class="price list">
                    <span>R$</span>
                    <span><?= money_fmt_br($list->value); ?></span>
                </p>
                <p class="price list font_120_percent">
                    <span>R$</span>
                    <span><?= money_fmt_br($list->lists()->total_value); ?></span>
                </p>
                <p class="desc_center list">
                    <span>R$</span>
                    <span><?= money_fmt_br($list->lists()->net_value); ?></span>
                </p>
                <p class="desc_center list">
                    <span>R$</span>
                    <span><?= money_fmt_br($list->lists()->comission_value); ?></span>
                </p>
                <p class="wrap list gradient gradient-red font_80_percent gradient-hover transition radius">
                    <a class="color_white list" style="text-decoration: none;" href="<?= url("/app/lista/{$list->id}") ?>">Editar</a>
                </p>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
    <div class=" app_launch_item footer">
        <p class="desc"></p>
        <p></p>
        <p class="font_80_percent jus">Valor Total das Listas:</p>
        <p class=" font_80_percent icon-thumbs-o-up">R$ <?= money_fmt_br(($allMoney->total ?? "0")); ?></p>
    </div>
    <div class=" app_launch_item footer">
        <p class="desc"></p>
        <p></p>
        <p class="font_80_percent">Valor total de comissão:</p>
        <p class=" font_80_percent icon-thumbs-o-up">R$ <?= money_fmt_br(($allMoney->total_comission ?? "0")); ?></p>
    </div>
    <div class=" app_launch_item footer">

        <p class="desc"></p>
        <p></p>
        <p class="font_80_percent" >Valor líquido total:</p>
        <p class=" font_80_percent icon-thumbs-o-up">R$ <?= money_fmt_br(($allMoney->total_net ?? "0")); ?></p>
    </div>
    <?= $paginator; ?>
</section>

<?= $v->start('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<?= $v->end(); ?>
