<?php $v->layout("_theme");
/** @var \Source\Models\Lists $list */
?>

<div class="app_launch_header">
    <div class="app_flex_title">
        <h2>
            <a class="color_white font_80_percent icon-user padding_btn transition
            gradient gradient-green gradient-hover radius box-shadow"
               title="usuários">R. de lançamentos</a></h2>
    </div>
    <form class="ajax_off app_launch_form_filter app_form" action="<?= url('/app/movimentacoes'); ?>" method="post">

        <select name="search_store" class="select2Input operator">
            <option value="">
                &ofcir; Selecione uma loja
            </option>
            <?php foreach ((new \Source\Models\Store())->find()->fetch(true) as $store): ?>
                <option <?= (($search->search_store) == $store->nome_loja && $search->search_store !== '' ? 'selected' : ""); ?>
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
        <p class="desc moviment font_80_percent">Data de Movimentação</p>
        <p class="desc moviment font_80_percent">dia da semana</p>
        <p class="desc moviment font_80_percent">Horário desejado</p>
        <p class="desc moviment font_80_percent">Loja</p>
        <p class="price moviment font_80_percent">Saldo Loja</p>
        <p class="price moviment font_80_percent">Valor Acertar</p>
        <p class="price moviment font_80_percent">Valor Dinheiro</p>
        <p class="price moviment font_80_percent">Valor Despesas</p>
        <p class="price moviment font_80_percent">Valor Recolhido</p>
        <p class="price moviment font_80_percent">Valor Anterior</p>
        <p class="price moviment font_80_percent">Valor Prêmio</p>
        <p class="desc moviment font_80_percent">Visualizar</p>
    </div>
    <?php if (isnt_empty($moviments, 'self')): ?>
        <?php foreach ($moviments as $moviment): ?>

            <article class="app_launch_item">
                <p class="desc moviment font_80_percent"><?= date_fmt($moviment->date_moviment, 'd/m/Y'); ?></p>
                <p class="desc moviment font_80_percent app_invoice_link transition">
                    <a  title="Ver Lista" class="desc moviment" href="<?= url("/app/horario/{$moviment->id}"); ?>"><?= date_fmt($moviment->date_moviment, 'd/m/Y') . ' ' . $moviment->week_day; ?></a>
                </p>
                <p class="desc moviment font_80_percent"><?= $moviment->hour; ?></p>
                <p class="desc moviment font_80_percent app_invoice_link transition">
                    <a class="desc moviment " style="text-decoration: none;"
                       href="<?= url("/app/loja/{$moviment->id_store}") ?>"><?= $moviment->store()->nome_loja; ?></a>
                </p>
                <p class="price category moviment font_80_percent">
                    <span>R$</span>
                    <span><?= money_fmt_br($moviment->new_value); ?></span>
                </p>
                <p class="price category moviment font_80_percent">
                    <span>R$</span>
                    <span><?= money_fmt_br($moviment->beat_value);?></span>
                </p>
                <p class="price category moviment font_80_percent">
                    <span>R$</span>
                    <span><?= money_fmt_br($moviment->paying_now); ?></span>
                </p>
                <p class="price category moviment font_80_percent">
                    <span>R$</span>
                    <span><?= money_fmt_br($moviment->expend); ?></span>
                </p>
                <p class="price category moviment font_80_percent">
                    <span>R$</span>
                    <span><?= money_fmt_br($moviment->get_value); ?></span>
                </p>
                <p class="price category moviment font_80_percent">
                    <span>R$</span>
                    <span><?= money_fmt_br($moviment->last_value); ?></span>
                </p>
                <p class="price category moviment font_80_percent">
                    <span>R$</span>
                    <span><?= money_fmt_br($moviment->prize); ?></span>
                </p>
                <p class="desc moviment gradient gradient-red font_80_percent gradient-hover transition radius">
                    <a class="color_white " style="text-decoration: none;"
                       href="<?= url("/app/movimentacao/{$moviment->id}") ?>">Visualizar</a>
                </p>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
    <div class="font_80_percent app_launch_item footer">
        <p class="desc"></p>
        <p></p>
        <p class="font_80_percent">Saldo da Valor Recolhido Total:</p>
        <p class="font_80_percent icon-thumbs-o-up"><?= money_fmt_br($allMoney->total_get_value, true); ?></p>
    </div>

     <div class="font_80_percent app_launch_item footer">
        <p class="desc"></p>
        <p></p>
        <p class="font_80_percent">Valor Despesas Total:</p>
        <p class="font_80_percent icon-thumbs-o-up"><?= money_fmt_br($allMoney->total_expend, true); ?></p>
    </div>
     <div class="font_80_percent app_launch_item footer">
        <p class="desc"></p>
        <p></p>
        <p class="font_80_percent">Valor Prêmio Total:</p>
        <p class="font_80_percent icon-thumbs-o-up"><?= money_fmt_br($allMoney->total_prize, true); ?></p>   </div>

    <div class="font_80_percent app_launch_item footer">
        <p class="desc"></p>
        <p></p>
        <p class="font_80_percent">Valor Líquido Total:</p>
        <p class="font_80_percent icon-thumbs-o-up"><?= money_fmt_br(($allMoney->total_get_value - ($allMoney->total_expend + $allMoney->total_prize)), true); ?></p>
    </div>

    <?= $paginator; ?>
</section>

<?= $v->start('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<?= $v->end(); ?>

