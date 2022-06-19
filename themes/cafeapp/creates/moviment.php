<?php $v->layout("_theme"); ?>

<div class="message icon-info animated color_888">INFO: [ clique I para informações, CTRL para calculadora ]</div>
<div class="color_888 app_header">
    <h2 class=" icon-calendar-check-o ">Cadastrar Movimentação:</h2>
</div>
<div class="app_widget app_launch_box">
    <form class="app_form " id="moviment" action="<?= url("/app/movimentacao"); ?>"
          data-getmoviment="<?= url("app/get-moviment"); ?>" method="post">
        <div class="ajax_response"><?= flash(); ?></div>
        <input type="hidden" class="id_temporary_moviment" name="id_temporary_moviment">

        <div class="label_group">
            <label class="three_label moviment">
                <span class="field icon-thumb-tack">DATA DE MOVIMENTO:</span>
                <input class="radius hour box-shadow required-input" rel="<?= url('/app/get_hour') ?>" type="date"
                       name="date_moviment"
                       required/>
            </label>

            <label class="three_label moviment">
                <p id="label" class="app_widget_title"></p>
            </label>

            <label class="three_label moviment">
                <span class="field icon-briefcase"> Horário:</span>
                <input type="hidden" class="current_hour" name="current_hour"
                       value="<?= ($currentHour ? $currentHour->id : null); ?>">
                <select name="id_hour" class="callback box-shadow required-input" rel="<?= url("/app/get_week_day") ?>"
                        required>
                </select>
            </label>
        </div>

        <div class="label_group">
            <label class=" moviment">
                <span class="field icon-briefcase">Loja:</span>
                <input type="hidden" name="id_store">
                <input autofocus type="text" placeholder="Ex: loja 001" class="store_data_list" list="code_store"
                       name="id_store_fake" autocomplete="off"
                       rel="<?= url('/app/get_list'); ?>"
                       data-url="<?= url('/app/get_store'); ?>"
                       data-verify="<?= url("/app/moviment_verify"); ?>">

                <datalist class="datalist_store" id="code_store">
                    <?php foreach ((new \Source\Models\Store())->find()->fetch(true) as $store): ?>
                    <option data-id_store="<?= $store->id; ?>" value="<?= $store->nome_loja; ?>"
                            name="<?= $store->nome_loja; ?>">
                    <option data-id_store="<?= $store->id; ?>" value="<?= $store->code; ?>" name="<?= $store->code; ?>">
                        <?php endforeach; ?>
                </datalist>
            </label>

            <label class="app_launch_item moviment">
                <span class="field icon-leanpub">Saldo Atual da Loja:</span>
                <p class="last_value"></p>
                <input type="hidden" name="last_value">
            </label>
        </div>

        <div class="label_group">
            <input type="hidden" name="id_list">

            <label class=" moviment">
                <span class="field icon-leanpub">Valor Dinheiro:</span>
                <input class="radius box-shadow mask-money required-input" autocomplete="off" type="text"
                       name="paying_now"
                       placeholder="Ex: 999"
                       />
            </label>

            <label class="app_launch_item moviment">
                <span class="field icon-leanpub">(lista)Valor Venda:</span>
                <p class="total_value"></p>
                <input type="hidden" name="total_value">
            </label>

        </div>

        <div class="label_group">
            <label class=" moviment">
                <span class="field icon-leanpub">Valor Despesas:</span>
                <input class="radius box-shadow mask-money required-input" autocomplete="off" type="text" name="expend"
                       placeholder="Ex: 999"
                       />
            </label>

            <label class="app_launch_item moviment">
                <span class="field icon-leanpub">(lista)Valor Comissão:</span>
                <p class="comission_value"></p>
                <input type="hidden" name="comission_value">
            </label>
        </div>

        <div class="label_group">

            <label class="prize_input  moviment">
                <span class="field icon-leanpub">Valor Premio:</span>
                <input class="radius mask-money prize" type="text" name="prize" autocomplete="off" placeholder="Ex: 999"/>
            </label>

            <label class="app_launch_item moviment">
                <span class="field icon-leanpub">(lista)Valor Líquido:</span>
                <p class="net_value"></p>
                <input type="hidden" name="net_value">
            </label>
        </div>

        <div class="message icon-info animated info">Os dados abaixo serão gerados automaticamente para a confirmação:
        </div>
            <div class="label_group">
                <label class=" moviment app_launch_item">
                    <span class="field icon-leanpub">Valor Recebido:</span>
                    <p class=" get_value price"></p>
                    <input type="hidden" name="get_value"/>
                </label>
                <label class=" moviment app_launch_item">
                    <span class="field icon-leanpub">PG prêmio Escritório:</span>
                    <p class=" prize_office price"></p>
                    <input type="hidden" name="prize_office"/>
                </label>
            </div>
            <div class="label_group">
                <label class=" moviment app_launch_item">
                    <span class="field icon-leanpub">Saldo Horário:</span>
                    <p class=" beat_value price"></p>
                    <input type="hidden" name="beat_value"/>
                </label>
                <label class=" moviment app_launch_item">
                    <span class="field icon-leanpub">PG prêmio Loja:</span>
                    <p class=" prize_store price"></p>
                    <input type="hidden" name="prize_store"/>
                </label>
            </div>
            <div class="label_group">
                <label class=" moviment app_launch_item">
                    <span class="field icon-leanpub">Centavos Trânsferidos:</span>
                    <p class="cents price"></p>
                    <input type="hidden" name="cents"/>
                </label>
                <label class=" moviment app_launch_item">
                    <span class="field icon-leanpub">Novo Saldo:</span>
                    <p class=" new_value price"></p>
                    <input type="hidden" name="new_value"/>
                </label>
            </div>

        <div class="al-center">
            <div id="div_moviment_btn">
                <button class="btn btn_inline radius transition icon-pencil-square-o" data-savetemp="" id="moviment_btn">Lançar</button>
            </div>
        </div>
    </form>


    <!-- Section about moviment press i -->
    <?= $view->render('views/fragments/moviment-viewer-no-data', []); ?>

    <?= $v->start('scripts'); ?>
    <script src="<?= theme("/assets/js/moviment-form-manager.js", CONF_VIEW_APP) ?>"></script>
    <?= $v->end('scripts'); ?>

</div>