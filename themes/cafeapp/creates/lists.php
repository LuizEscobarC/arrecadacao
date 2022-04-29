<?php $v->layout("_theme"); ?>

<div class="app_invoice app_widget">
    <div class="color_888 app_header">
        <h2 class=" icon-calendar-check-o ">Cadastrar Lista:</h2>
    </div>
    <form class="app_form" action="<?= url("/app/lista"); ?>" method="post">
        <div class="ajax_response"><?= flash(); ?></div>

        <div class="label_group">
            <label class="three_label">
                <span class="field icon-thumb-tack">DATA DE MOVIMENTO:</span>
                <input class="radius hour" rel="<?= url('/app/get_hour') ?>" type="date" name="date_moviment"
                       required/>
            </label>

            <label class="three_label">
                <p id="label" class="app_widget_title"></p>
            </label>

            <label class="three_label">
                <span class="field icon-briefcase"> HORÁRIO:</span>
                <input type="hidden" class="current_hour" name="current_hour" value="<?= ($currentHour ? $currentHour->id : null); ?>">
                <select name="id_hour" class="callback" rel="<?= url("/app/get_week_day") ?>">
                </select>
            </label>
        </div>


        <label>
            <span class="field icon-briefcase">Loja:</span>
            <input type="hidden" name="id_store">
            <input type="text" class="store_data_list" list="code_store" name="id_store_fake" autocomplete="off">

            <datalist class="datalist_store" id="code_store">
                <?php foreach ((new \Source\Models\Store())->find()->fetch(true) as $store): ?>
                <option data-id_store="<?= $store->id; ?>" value="<?= $store->nome_loja; ?>" name="<?= $store->nome_loja; ?>">
                <option data-id_store="<?= $store->id; ?>" value="<?= $store->code; ?>" name="<?= $store->code; ?>">
                    <?php endforeach; ?>
            </datalist>
        </label>

        <label>
            <span class="field icon-leanpub">Valor Bruto:</span>
            <input class="radius mask-money" type="text" name="total_value" placeholder="Ex: 999"
                   required/>
        </label>


        <div class="al-center">
            <div>
                <button class="btn btn_inline radius transition icon-pencil-square-o">Atualizar</button>
            </div>
        </div>
    </form>
</div>