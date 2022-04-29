<?php $v->layout("_theme"); ?>

<div class="app_invoice app_widget">
    <div class="color_888 app_header">
        <h2 class=" icon-calendar-check-o ">Cadastrar Movimentação:</h2>
    </div>
    <form class="app_form moviment" action="<?= url("/app/movimentacao"); ?>" method="post">
        <div class="ajax_response"><?= flash(); ?></div>
        <div class="label_group">
            <label class="three_label">
                <span class="field icon-thumb-tack">DATA DE MOVIMENTO:</span>
                <span class="app_widget_title"><?= date_fmt($moviment->date_moviment, 'd/m/Y'); ?></span>
            </label>

            <label class="three_label">
                <span class="field icon-info"> Dia da semana:</span>
                <span class="app_widget_title"><?= $moviment->hour()->week_day; ?></span>
            </label>

            <label class="three_label">
                <span class="field icon-briefcase"> Horário:</span>
                <span class="app_widget_title"><?= $moviment->hour()->description; ?></span>
            </label>
        </div>

        <div class="label_group">
            <label class="three_label">
                <span class="field icon-briefcase">Loja:</span>
                <span class="app_widget_title"><?= $moviment->store()->nome_loja; ?></span>
            </label>

            <label class="three_label">
                <span class="field icon-leanpub">Saldo Atual da Loja:</span>
                <span class="app_widget_title"><?= money_fmt_br($moviment->store()->valor_saldo); ?></span>
            </label>

            <label class="three_label">
                <span class="field icon-leanpub">Saldo Anterior da Loja:</span>
                <span class="app_widget_title last_value"><?= money_fmt_br($moviment->last_value); ?></span>
            </label>
        </div>

        <div class="label_group">
            <input type="hidden" name="id_list">
            <label class="three_label">
                <span class="field icon-leanpub">(lista)Valor Venda:</span>
                <span class="app_widget_title total_value"><?= (!empty($moviment->id_list) ? money_fmt_br($moviment->lists()->total_value): money_fmt_br(0)); ?></span>
            </label>

            <label class="three_label">
                <span class="field icon-leanpub">(lista)Valor Comissão:</span>
                <span class="app_widget_title"><?= (!empty($moviment->id_list) ? money_fmt_br($moviment->lists()->comission_value): money_fmt_br(0)); ?></span>
            </label>

            <label class="three_label">
                <span class="field icon-leanpub">(lista)Valor Líquido:</span>
                <span class="app_widget_title"><?= (!empty($moviment->id_list) ? money_fmt_br($moviment->lists()->net_value): money_fmt_br(0)); ?></span>
            </label>
        </div>

        <div class="label_group">
            <label class="three_label">
                <span class="field icon-leanpub">Valor Dinheiro:</span>
                <span class="app_widget_title"><?= money_fmt_br($moviment->paying_now); ?></span>
            </label>
            <label class="three_label">
                <span class="field icon-leanpub">Valor Despesas:</span>
                <span class="app_widget_title"><?= money_fmt_br($moviment->expend); ?></span>
            </label>
            <label class="three_label">
                <span class="field icon-leanpub">Valor Recolhido:</span>
                <span class="app_widget_title"><?= money_fmt_br($moviment->get_value); ?></span>
            </label>
        </div>

        <div class="label_group">
            <label>
                <span class="field icon-leanpub">Saldo Horário:</span>
                <span class="app_widget_title"><?= money_fmt_br($moviment->beat_value); ?></span>
            </label>

            <label>
                <span class="field icon-leanpub">Novo Saldo da Loja:</span>
                <span class="app_widget_title"><?= money_fmt_br($moviment->new_value); ?></span>
            </label>

        </div>

        <div class="label_group">
            <label>
                <span class="field icon-leanpub">Valor Premio:</span>
                <span class="app_widget_title"><?= money_fmt_br($moviment->prize); ?></span>
            </label>

            <label class="prize_output">
                <span class="field icon-leanpub">Valor de Abate Premio:</span>
                <span class="app_widget_title"><?= money_fmt_br($moviment->beat_prize); ?></span>
            </label>
        </div>

        <div class="label_group">
            <label>
                <span class="field icon-leanpub">Valor de Abate do Escritório:</span>
                <span class="app_widget_title"><?= money_fmt_br($moviment->prize_office); ?></span>
            </label>

            <label class="prize_output">
                <span class="field icon-leanpub">Valor de Abate da Loja:</span>
                <span class="app_widget_title"><?= money_fmt_br($moviment->prize_store); ?></span>
            </label>
        </div>

        <div class="al-center">
            <div>
                <span data-movimentremove="<?= url("/app/remove-moviment/{$moviment->id}") ?>"
                      class="btn_remove transition icon-error">Excluir</span>
            </div>
        </div>
    </form>
</div>
