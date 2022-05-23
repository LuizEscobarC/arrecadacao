<?php $v->layout("_theme"); ?>
<div class="app_invoice app_widget">
    <div class="color_888 app_header">
        <h2 class=" icon-calendar-check-o ">Atualizar Lista:</h2>
    </div>
    <form class="app_form" action="<?= url("/app/lista"); ?>" method="post">
        <div class="ajax_response"><?= flash(); ?></div>
        <input class="radius" type="hidden" name="id" value="<?= $list->id; ?>" required/>
        <input class="radius" type="hidden" name="edit" value="1" required/>
        <div class="label_group">
            <label class="three_label">
                <span class="field icon-thumb-tack">Data de movimentação:</span>
                <input class="radius hour" value="<?= date_fmt($list->date_moviment, 'Y-m-d'); ?>"
                       rel="<?= url('/app/get_hour') ?>" type="date" name="date_moviment"
                       required/>
            </label>

            <label class="three_label">
                <p id="label" class="app_widget_title"> <?= $list->hour()->week_day; ?></p>
            </label>

            <label class="three_label">
                <span class="field icon-briefcase">Horário Desejado:</span>
                <select name="id_hour" class="callback" rel="<?= url("/app/get_week_day") ?>">
                    <option value="0">Escolha</option>
                    <option selected value="<?= $list->hour()->id; ?>"><?= $list->hour()->description ?></option>
                </select>
            </label>
        </div>

        <label>
            <span class="field icon-briefcase">Loja:</span>
            <input type="hidden" name="id_store" value="<?= $list->store()->id; ?>">
            <input type="text" class="store_data_list" list="code_store" name="id_store_fake"
                   value="<?= $list->store()->nome_loja; ?>" autocomplete="off">

            <datalist class="datalist_store" id="code_store">
                <?php foreach ((new \Source\Models\Store())->find()->fetch(true) as $store): ?>
                <!-- name necessário para manipulação de id no javascript -->
                <option data-id_store="<?= $store->id; ?>" value="<?= $store->nome_loja; ?>" name="<?= $store->nome_loja; ?>">
                <option data-id_store="<?= $store->id; ?>" value="<?= $store->code; ?>" name="<?= $store->code; ?>">
                    <?php endforeach; ?>
            </datalist>
        </label>

        <div class="label_group">
            <label class="three_label">
                <span class="field icon-leanpub">Valor Total:</span>
                <input class="radius mask-money" type="text" name="total_value" value="<?= money_fmt_br($list->total_value); ?>"
                       placeholder="Ex: 999"
                       required/>
            </label>


            <label class="three_label">
                <span class="field icon-leanpub">Valor de Comissão:</span>
                <input class="radius" type="text" name="" readonly value="<?= money_fmt_br($list->comission_value); ?>"
                       placeholder="Ex: 999"
                       required/>
            </label>

            <label class="three_label">
                <span class="field icon-leanpub">Valor Líquido:</span>
                <input class="radius" type="text" name="" readonly value="<?= money_fmt_br($list->net_value); ?>"
                       placeholder="Ex: 999"
                       required/>
            </label>


        </div>

        <div class="al-center">
            <div>
                <span data-listremove="<?= url("/app/remove-list/{$list->id}") ?>"
                      class="btn_remove transition icon-error">Excluir</span>
                <button class="btn btn_inline radius transition icon-pencil-square-o">Atualizar</button>
            </div>
        </div>
    </form>
</div>

<?= $v->start('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<?= $v->end(); ?>
