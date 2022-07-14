<?php $v->layout("_theme"); ?>

<div class="app_invoice app_launch_box">
    <div class="color_888 app_header">
        <h2 class=" icon-calendar-check-o ">Cadastrar Lançamento:</h2>
    </div>
    <form class="app_form cash_flow ajax_off" action="<?= url("/app/fluxo-de-caixa"); ?>" method="post">
        <div class="ajax_response"><?= flash(); ?></div>
       <!-- Apresenta se a valor foi manual ou maquina -->
        <input type="hidden" name="system" value="0">

        <div class="label_group">
            <label class="three_label">
                <span class="field icon-thumb-tack">DATA DE MOVIMENTO:</span>
                <input class="radius hour" rel="<?= url('/app/get_hour') ?>" type="date"
                       name="date_moviment"
                       required/>
            </label>

            <label class="three_label app_launch_item">
                <p id="label"></p>
            </label>

            <label class="three_label">
                <span class="field icon-briefcase"> Horário:</span>
                <input type="hidden" class="current_hour" name="current_hour" value="<?= ($currentHour ? $currentHour->id : null); ?>">
                <select name="id_hour" class="callback" rel="<?= url("/app/get_week_day") ?>">
                </select>
            </label>
        </div>
        <div class="label_group">
            <label>
                <span class="field icon-briefcase">Loja:</span>
                <input type="hidden" name="id_store">
                <input type="text" class="store_data_list cash_flow" list="code_store" name="id_store_fake" autofocus
                       autocomplete="off" data-url="<?= url('/app/get_store'); ?>">

                <datalist class="datalist_store" id="code_store">
                    <?php foreach ((new \Source\Models\Store())->find()->fetch(true) as $store): ?>
                    <option data-id_store="<?= $store->id; ?>" value="<?= $store->nome_loja; ?>"
                            name="<?= $store->nome_loja; ?>">
                    <option data-id_store="<?= $store->id; ?>" value="<?= $store->code; ?>" name="<?= $store->code; ?>">
                        <?php endforeach; ?>
                </datalist>
            </label>

            <label class="app_launch_item">
                <span class="icon-leanpub">Saldo Atual da Loja:</span>
                <p class=" last_value"></p>
                <input type="hidden" name="last_value">
            </label>
        </div>

        <div class="label_group">
            <label class="app_launch_item">
                <span class="icon-leanpub">valor despesa loja:</span>
                <p class="store_expense"></p>
            </label>

            <label class="app_launch_item">
                <span class="icon-leanpub">valor despesa escritório:</span>
                <p class="office_expense"></p>
            </label>
        </div>

        <label>
            <span class="field icon-briefcase">Centro de custo:</span>
            <select name="id_cost" class="select2Input">
                <option value="">Escolha</option>
                <?php foreach ((new \Source\Models\Center())->find()->fetch(true) as $center): ?>
                    <option value="<?= $center->id; ?>">&ofcir; <?= $center->description; ?></option>
                <?php endforeach; ?>
            </select>
        </label>

        <div class="label_group">

            <label class="three_label">
                <span class="field icon-leanpub">Valor do lançamento:</span>
                <input class="radius mask-money cash_value" type="text" name="value" placeholder="Ex: 999"
                       required/>
            </label>

            <label class="three_label">
                <span class="field">Entrada:</span>
                <input type="radio" name="type" value="1">
                <span class="field">Saída: </span>
                <input type="radio" name="type" value="2">
            </label>

            <label class="three_label ">
                <span class="field">Descrição:</span>
                <textarea class="radius" name="description"></textarea>
            </label>
        </div>

        <label>
            <label class="prize_output">

            </label>
        </label>


        <div class="al-center">
            <div>
                <button class="btn btn_inline radius transition icon-pencil-square-o">Calcular</button>
            </div>
        </div>
    </form>

    <?= $v->start('scripts'); ?>
    <script src="<?= theme("/assets/js/cashflow-form-manager.js", CONF_VIEW_APP) ?>"></script>
    <?= $v->end('scripts'); ?>

</div>