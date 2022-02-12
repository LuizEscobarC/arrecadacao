<?php $v->layout("_theme"); ?>

<div class="app_invoice app_widget">
    <div class="color_888 app_header">
        <h2 class=" icon-calendar-check-o ">Cadastrar Lançamento:</h2>
    </div>
    <form class="app_form" action="<?= url("/app/fluxo-de-caixa"); ?>" method="post">
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

        <label>
            <span class="field icon-briefcase">Loja:</span>
            <select name="id_store" class="select2Input">
                <option value="">Escolha</option>
                <?php foreach ((new \Source\Models\Store())->find()->fetch(true) as $store): ?>
                    <option value="<?= $store->id; ?>">&ofcir; <?= $store->nome_loja; ?></option>
                <?php endforeach; ?>
            </select>
        </label>

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
                <input class="radius mask-money" type="text" name="value" placeholder="Ex: 999"
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


        <div class="al-center">
            <div>
                <button class="btn btn_inline radius transition icon-pencil-square-o">Lançar</button>
            </div>
        </div>
    </form>
</div>