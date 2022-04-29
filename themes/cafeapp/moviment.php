<?php $v->layout("_theme");
/** @var \Source\Models\Moviment $moviment */
?>

<div class="app_invoice app_widget">
    <div class="color_888 app_header">
        <h2 class=" icon-calendar-check-o ">Atualizar Movimentação:</h2>
    </div>
    <form class="app_form edit" id="moviment" action="<?= url("/app/movimentacao"); ?>" method="post">
        <input name="edit" value="true">
        <div class="ajax_response"><?= flash(); ?></div>

        <div class="label_group">
            <label class="three_label">
                <span class="field icon-thumb-tack">DATA DE MOVIMENTO:</span>
                <input class="radius hour box-shadow required-input" rel="<?= url('/app/get_hour') ?>" type="date"
                       name="date_moviment" value="<?= date_fmt_app($moviment->date_moviment) ?>"
                       required/>
            </label>

            <label class="three_label">
                <p id="label" class="app_widget_title"><?= $moviment->hour()->week_day ?></p>
            </label>

            <label class="three_label">
                <span class="field icon-briefcase"> Horário:</span>
                <select name="id_hour" class="callback box-shadow required-input" rel="<?= url("/app/get_week_day") ?>"
                        required>
                    <option value="<?= $moviment->hour()->id ?>"><?= $moviment->hour()->description; ?></option>
                </select>
            </label>
        </div>

        <div class="label_group">
            <label>
                <span class="field icon-briefcase">Loja:</span>
                <input type="hidden" name="id_store" value="<?= $moviment->store()->id ?>">
                <input autofocus type="text" class="store_data_list" list="code_store" name="id_store_fake" autocomplete="off"
                       value="<?= $moviment->store()->nome_loja; ?>"
                       rel="<?= url('/app/get_list'); ?>"
                       data-url="<?= url('/app/get_store'); ?>"
                       data-verify="<?= url("/app/moviment_verify"); ?>">

                <datalist class="datalist_store" id="code_store">
                    <?php foreach ((new \Source\Models\Store())->find()->fetch(true) as $store): ?>
                            <option data-id_store="<?= $store->id; ?>" value="<?= $store->nome_loja; ?>" name="<?= $store->nome_loja; ?>" <?php ($moviment->store()->id == $store->id ? 'selected' : ''); ?>>
                            <option data-id_store="<?= $store->id; ?>" value="<?= $store->code; ?>" name="<?= $store->code; ?>">
                    <?php endforeach; ?>
                </datalist>
            </label>

            <label>
                <span class="field icon-leanpub">Saldo Atual da Loja:</span>
                <input class="mask-money-negative" type="text" name="last_value" value="<?= money_fmt_br($moviment->last_value - $moviment->lists()->net_value); ?>">
                <p style="display:none;" class="app_widget_title last_value"><?= $moviment->last_value - $moviment->lists()->net_value; ?></p>
            </label>
        </div>

        <div class="label_group">
            <input type="hidden" name="id_list">
            <label class="three_label">
                <span class="field icon-leanpub">(lista)Valor Venda:</span>
                <p class="app_widget_title total_value">0</p>
                <input type="hidden" name="total_value">

            </label>
            <label class="three_label">
                <span class="field icon-leanpub">(lista)Valor Comissão:</span>
                <p class="app_widget_title comission_value">0</p>
                <input type="hidden" name="comission_value">
            </label>
            <label class="three_label">
                <span class="field icon-leanpub">(lista)Valor Líquido:</span>
                <p class="app_widget_title net_value">0</p>
                <input type="hidden" name="net_value">
            </label>
        </div>

        <div class="label_group">
            <label class="three_label">
                <span class="field icon-leanpub">Valor Dinheiro:</span>
                <input class="radius box-shadow mask-money required-input" type="text" name="paying_now"
                       value="<?= $moviment->paying_now; ?>"
                       placeholder="Ex: 999"
                       value="0" required/>
            </label>
            <label class="three_label">
                <span class="field icon-leanpub">Valor Despesas:</span>
                <input class="radius box-shadow mask-money required-input" type="text" name="expend"
                    <?= $moviment->expend; ?>
                       placeholder="Ex: 999"
                       value="0" required/>
            </label>
            <label class="three_label">
                <span class="field icon-leanpub">Valor Recolhido:</span>
                <p class="app_widget_title get_value"><?= $moviment->get_value; ?></p>
                <input type="hidden" name="get_value" value="<?= $moviment->get_value; ?>"/>
            </label>
        </div>

        <div class="label_group">
            <label>
                <span class="field icon-leanpub">Saldo Horário:</span>
                <p class="app_widget_title beat_value"><?= $moviment->beat_value; ?></p>
                <input type="hidden" name="beat_value" value="<?= $moviment->beat_value; ?>"/>
            </label>

            <label>
                <span class="field icon-leanpub">Novo Saldo:</span>
                <p class="app_widget_title new_value"><?= $moviment->new_value; ?></p>
                <input type="hidden" name="new_value" value="<?= $moviment->new_value; ?>"/>

            </label>

            <!--
            <label class="three_label">
                <span class="field icon-leanpub">Valor Premio:</span>
                <input class="radius mask-money" type="text" name="value" placeholder="Ex: 999"
                       required/>
            </label> -->
        </div>
        <div class="label_group">
            <label class="prize_input">
                <span class="field icon-leanpub">Valor Premio:</span>
                <input class="radius mask-money" type="text" name="prize" placeholder="Ex: 999" value="<?= ($moviment->prize ?? 0 )?>"/>
            </label>

            <label class="prize_output">
                <input type="hidden" name="beat_prize" value="<?= ( $moviment->beat_prize ?? 0); ?>">
                <input type="hidden" name="prize_office" value="<?= ( $moviment->prize_office ?? 0); ?>">
                <input type="hidden" name="prize_store" value="<?= ( $moviment->prize_store ?? 0); ?>">
            </label>
        </div>

        <div class="al-center">
            <div>
                <button class="btn btn_inline radius transition icon-pencil-square-o" id="moviment_btn">Lançar</button>
            </div>
        </div>
    </form>







<!-- moviment -->
    <div class="app_modal app_form" data-modalclose="true">
        <!--Calc-->
        <div class="app_modal_box app_modal_moviment">
            <p class="title icon-calendar-check-o">Dados:</p>
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
        </div>
    </div>

</div>
