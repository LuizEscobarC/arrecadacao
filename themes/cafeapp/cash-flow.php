<?php $v->layout("_theme"); ?>
<div class="app_invoice app_widget">
    <div class="color_888 app_header">
        <h2 class=" icon-calendar-check-o ">Atualizar Lançamento:</h2>
    </div>
    <form class="app_form" action="<?= url("/app/fluxo-de-caixa"); ?>" method="post">
        <div class="ajax_response"><?= flash(); ?></div>

        <input type="hidden" name="id" value="<?= $cash->id; ?>">

        <div class="label_group">
            <label class="three_label">
                <span class="field icon-thumb-tack">DATA DE MOVIMENTO:</span>
                <input class="radius hour" value="<?= date_fmt($cash->date_moviment, 'Y-m-d'); ?>"
                       rel="<?= url('/app/get_hour') ?>" type="date"
                       name="date_moviment"
                       required/>
            </label>

            <label class="three_label">
                <p id="label" class="app_widget_title"><?= $cash->hour()->week_day; ?></p>
            </label>

            <label class="three_label">
                <span class="field icon-briefcase"> HORÁRIO:</span>
                <select name="id_hour" class="callback" rel="<?= url("/app/get_week_day") ?> ">
                    <option value="0">Escolha</option>
                    <option selected value="<?= $cash->hour()->id; ?>"><?= $cash->hour()->description ?></option>
                </select>
            </label>
        </div>

        <label>
            <span class="field icon-briefcase">Loja:</span>
            <select name="id_store" class="select2Input"">
                <option value="">Escolha</option>
                <?php foreach ((new \Source\Models\Store())->find()->fetch(true) as $store): ?>
                    <option value="<?= $store->id; ?>" <?= ($store->id == $cash->Store()->id ? 'selected' : ""); ?>>
                        &ofcir; <?= $store->nome_loja; ?></option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>
                <span class="field icon-briefcase">Centro de custo:</span>
            <select name="id_cost" class="select2Input">
                <option value="">Escolha</option>
                <?php foreach ((new \Source\Models\Center())->find()->fetch(true) as $center): ?>
                    <option value="<?= $center->id; ?>" <?= ($center->id == (!empty($cash->Cost() ?  $cash->Cost()->id : '')) ?
                        'selected' : ""); ?>>&ofcir; <?= $center->description; ?></option>
                <?php endforeach; ?>
            </select>
        </label>

        <div class="label_group">

            <label class="three_label">
                <span class="field icon-leanpub">Valor do lançamento:</span>
                <input class="radius mask-money" type="text" name="value" placeholder="Ex: 999"
                       required value="<?= money_fmt_br($cash->value); ?>"/>
            </label>

            <label class="three_label">
                <span class="field">Entrada:</span>
                <input type="radio" name="type" value="1"  <?= ($cash->type == 1 ? "checked" : ""); ?>>
                <span class="field">Saída: </span>
                <input type="radio" name="type" value="2" <?= ($cash->type == 2 ? "checked" : ""); ?>>
            </label>

            <label class="three_label ">
                <span class="field">Descrição:</span>
                <textarea class="radius" name="description"><?= $cash->description; ?></textarea>
            </label>
        </div>

        <div class="al-center">
            <div>
                <span data-cashremove="<?= url("/app/remove-cash-flow/{$cash->id}") ?>"
                      class="btn_remove transition icon-error">Excluir</span>
                <button class="btn btn_inline radius transition icon-pencil-square-o">Atualizar</button>
            </div>
        </div>
    </form>
</div>

<?= $v->start('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<?= $v->end(); ?>
