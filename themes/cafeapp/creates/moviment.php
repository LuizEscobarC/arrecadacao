<?php $v->layout("_theme"); ?>

<div class="message icon-info animated color_888">INFO: [ clique I para informações, CTRL para calculadora ]</div>
<div class="app_invoice app_widget">
    <div class="color_888 app_header">
        <h2 class=" icon-calendar-check-o ">Cadastrar Movimentação:</h2>
        <h2 ></h2>
    </div>
    <form class="app_form" id="moviment" action="<?= url("/app/movimentacao"); ?>"  data-getmoviment="<?= url("app/get-moviment"); ?>" method="post">
        <div class="ajax_response"><?= flash(); ?></div>

        <div class="label_group">
            <label class="three_label">
                <span class="field icon-thumb-tack">DATA DE MOVIMENTO:</span>
                <input class="radius hour box-shadow required-input" rel="<?= url('/app/get_hour') ?>" type="date"
                       name="date_moviment"
                       required/>
            </label>

            <label class="three_label">
                <p id="label" class="app_widget_title"></p>
            </label>

            <label class="three_label">
                <span class="field icon-briefcase"> Horário:</span>
                <input type="hidden" class="current_hour" name="current_hour" value="<?= ($currentHour ? $currentHour->id : null); ?>">
                <select name="id_hour" class="callback box-shadow required-input" rel="<?= url("/app/get_week_day") ?>"
                        required>
                </select>
            </label>
        </div>

        <div class="label_group">
            <label>
                <span class="field icon-briefcase">Loja:</span>
                <input type="hidden" name="id_store">
                <input autofocus type="text" class="store_data_list" list="code_store" name="id_store_fake" autocomplete="off"
                       rel="<?= url('/app/get_list'); ?>"
                       data-url="<?= url('/app/get_store'); ?>"
                       data-verify="<?= url("/app/moviment_verify"); ?>">

                <datalist class="datalist_store" id="code_store">
                    <?php foreach ((new \Source\Models\Store())->find()->fetch(true) as $store): ?>
                    <option data-id_store="<?= $store->id; ?>" value="<?= $store->nome_loja; ?>" name="<?= $store->nome_loja; ?>">
                    <option data-id_store="<?= $store->id; ?>" value="<?= $store->code; ?>" name="<?= $store->code; ?>">
                        <?php endforeach; ?>
                </datalist>
            </label>

            <label>
                <span class="field icon-leanpub">Saldo Atual da Loja:</span>
                <p class="app_widget_title last_value"></p>
                <input type="hidden" name="last_value">
            </label>
        </div>

        <div class="label_group">
            <input type="hidden" name="id_list">
            <label class="three_label">
                <span class="field icon-leanpub">(lista)Valor Venda:</span>
                <p class="app_widget_title total_value"></p>
                <input type="hidden" name="total_value">

            </label>
            <label class="three_label">
                <span class="field icon-leanpub">(lista)Valor Comissão:</span>
                <p class="app_widget_title comission_value"></p>
                <input type="hidden" name="comission_value">
            </label>
            <label class="three_label">
                <span class="field icon-leanpub">(lista)Valor Líquido:</span>
                <p class="app_widget_title net_value"></p>
                <input type="hidden" name="net_value">
            </label>
        </div>

        <div class="label_group">
            <label class="three_label">
                <span class="field icon-leanpub">Valor Dinheiro:</span>
                <input class="radius box-shadow mask-money required-input" type="text" name="paying_now"
                       placeholder="Ex: 999"
                       value="0" required/>
            </label>
            <label class="three_label">
                <span class="field icon-leanpub">Valor Despesas:</span>
                <input class="radius box-shadow mask-money required-input" type="text" name="expend"
                       placeholder="Ex: 999"
                       value="0" required/>
            </label>
            <label class="three_label">
                <span class="field icon-leanpub">Valor Recolhido:</span>
                <p class="app_widget_title get_value"></p>
                <input type="hidden" name="get_value"/>
            </label>
        </div>

        <div class="label_group">
            <label>
                <span class="field icon-leanpub">Saldo Horário:</span>
                <p class="app_widget_title beat_value"></p>
                <input type="hidden" name="beat_value"/>
            </label>

            <label>
                <span class="field icon-leanpub">Novo Saldo:</span>
                <p class="app_widget_title new_value"></p>
                <input type="hidden" name="new_value"/>
                <input type="hidden" name="new_value_with_cents"/>

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
                <input class="radius mask-money" type="text" name="prize" placeholder="Ex: 999"/>
            </label>

            <label class="prize_output">

            </label>
        </div>

        <div class="al-center">
            <div id="div_moviment_btn">
                <button class="btn btn_inline radius transition icon-pencil-square-o" id="moviment_btn">Lançar</button>
            </div>
        </div>
    </form>


    <!-- Section about moviment press i -->
    <?= $view->render('views/fragments/moviment-viewer-no-data', []); ?>

</div>