<?php $v->layout("_theme"); ?>
<div class="app_invoice app_widget">
    <div class="color_888 app_header">
        <h2 class=" icon-calendar-check-o ">Atualizar Lista:</h2>
    </div>
    <form class="app_form" action="<?= url("/app/lista"); ?>" method="post">
        <div class="ajax_response"><?= flash(); ?></div>
        <input class="radius" type="hidden" name="id" value="<?= $list->id; ?>" required/>
        <div class="label_group">
            <label>
                <span class="field icon-thumb-tack">Data de movimentação:</span>
                <input class="radius" value="<?= date_fmt($list->date_moviment, 'Y-m-d'); ?>" id="hour"
                       rel="<?= url('/app/get_hour') ?>" type="date" name="date_moviment"
                       required/>
            </label>

            <label>
                <span class="field icon-briefcase">Horário Desejado:</span>
                <select name="id_hour" id="callback">
                    <option value="<?= $list->hour()->id; ?>"><?= $list->hour()->description ?></option>
                </select>
            </label>
        </div>

        <label>
            <span class="field icon-briefcase">Loja:</span>
            <select name="id_store">
                <?php foreach ((new \Source\Models\Store())->find()->fetch(true) as $store): ?>
                    <option <?= ($list->id_store == $store->id ? 'selected' : ""); ?> value="<?= $store->id; ?>">
                        &ofcir; <?= $store->nome_loja; ?></option>
                <?php endforeach; ?>
            </select>
        </label>

        <div class="label_group">
            <label class="three_label">
                <span class="field icon-leanpub">Valor Total:</span>
                <input class="radius mask-money" type="text" name="total_value" value="<?= $list->total_value; ?>"
                       placeholder="Ex: 999"
                       required/>
            </label>


            <label class="three_label">
                <span class="field icon-leanpub">Valor de Comissão:</span>
                <input class="radius" type="number" name="" readonly value="<?= money_fmt_br($list->comission_value); ?>"
                       placeholder="Ex: 999"
                       required/>
            </label>

            <label class="three_label">
                <span class="field icon-leanpub">Valor Líquido:</span>
                <input class="radius" type="number" name="" readonly value="<?= money_fmt_br($list->net_value); ?>"
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