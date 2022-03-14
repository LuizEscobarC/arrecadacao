<?php $v->layout("_theme"); ?>

<div class="app_invoice app_widget">
    <div class="color_888 app_header">
        <h2 class=" icon-calendar-check-o ">Cadastrar Movimentação:</h2>
    </div>
    <form class="app_form" action="<?= url("/app/movimentacao"); ?>" method="post">
        <div class="ajax_response"><?= flash(); ?></div>

        <div class="label_group">
            <label class="three_label">
                <span class="field icon-thumb-tack">DATA DE MOVIMENTO:</span>
                <input class="radius hour" rel="<?= url('/app/get_hour') ?>" type="date"
                       name="date_moviment"
                       required/>
            </label>

            <label class="three_label">
                <p id="label" class="app_widget_title"></p>
            </label>

            <label class="three_label">
                <span class="field icon-briefcase"> Horário:</span>
                <select name="id_hour" id="callback" rel="<?= url("/app/get_week_day") ?>">
                </select>
            </label>
        </div>

        <div class="label_group">
            <label>
                <span class="field icon-briefcase">Loja:</span>
                <select name="id_store" class="select2Input store_select" rel="<?= url('/app/get_list'); ?>"
                        data-url="<?= url('/app/get_store'); ?>">
                    <option value="">Escolha</option>
                    <?php foreach ((new \Source\Models\Store())->find()->fetch(true) as $store): ?>
                        <option value="<?= $store->id; ?>">&ofcir; <?= $store->nome_loja; ?></option>
                    <?php endforeach; ?>
                </select>

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
            </label>
            <label class="three_label">
                <span class="field icon-leanpub">(lista)Valor Comissão:</span>
                <p class="app_widget_title comission_value"></p>
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
                <input class="radius mask-money" type="text" name="paying_now" placeholder="Ex: 999"
                       required/>
            </label>
            <label class="three_label">
                <span class="field icon-leanpub">Valor Despesas:</span>
                <input class="radius mask-money" type="text" name="expend" placeholder="Ex: 999"
                       required/>
            </label>
            <label class="three_label">
                <span class="field icon-leanpub">Valor Recolhido:</span>
                <p class="app_widget_title get_value"></p>
                <input type="hidden" name="get_value"/>
            </label>
        </div>

        <div class="label_group">
            <label >
                <span class="field icon-leanpub">Saldo Horário:</span>
                <p class="app_widget_title beat_value"></p>
                <input type="hidden" name="beat_value"/>
            </label>

            <label>
                <span class="field icon-leanpub">Novo Saldo:</span>
                <p class="app_widget_title new_value"></p>
                <input type="hidden" name="new_value"/>

            </label>

            <!--
            <label class="three_label">
                <span class="field icon-leanpub">Valor Premio:</span>
                <input class="radius mask-money" type="text" name="value" placeholder="Ex: 999"
                       required/>
            </label> -->
        </div>
        <div class="label_group">
            <label style="opacity: 0" class="prize_input">
                <span class="field icon-leanpub">Valor Premio:</span>
                <input class="radius mask-money" type="text" name="prize" placeholder="Ex: 999"
                       required/>
            </label>

            <label class="prize_output">

            </label>
        </div>

        <div class="al-center">
            <div>
                <button class="btn btn_inline radius transition icon-pencil-square-o">Lançar</button>
            </div>
        </div>
    </form>
</div>