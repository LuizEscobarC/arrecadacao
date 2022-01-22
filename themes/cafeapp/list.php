<?php $v->layout("_theme"); ?>
<div class="app_invoice app_widget">
    <form class="app_form" action="<?= url("/app/lista"); ?>" method="post">
        <div class="ajax_response"><?= flash(); ?></div>
        <input class="radius" type="hidden" name="id" value="<?= $list->id; ?>" required/>
        <label>
            <span class="field icon-briefcase">Horário Desejado:</span>
            <select name="id_hour">
            <?php foreach((new \Source\Models\Hour())->find()->fetch(true) as $hour):?>
                <option <?= ($list->id_hour == $hour->id ? 'selected' : ""); ?> value="<?= $hour->id; ?>">&ofcir; <?= $hour->description; ?></option>
            <?php endforeach;?>
            </select>
        </label>


        <label>
            <span class="field icon-briefcase">Loja:</span>
            <select name="id_hour">
            <?php foreach((new \Source\Models\Store())->find()->fetch(true) as $store):?>
                <option <?= ($list->id_store == $store->id ? 'selected' : ""); ?> value="<?= $store->id; ?>">&ofcir; <?= $store->nome_loja; ?></option>
            <?php endforeach;?>
            </select>
        </label>

        <label>
            <span class="field icon-leanpub">Valor de Comissão:</span>
            <input class="radius" type="number" name="comission_value" value="<?= $list->comission_value; ?>" placeholder="Ex: 999"
                   required/>
        </label>

        <div class="label_group">
            <label>
                <span class="field icon-leanpub">Valor Total:</span>
                <input class="radius" type="number" name="total_value" value="<?= $list->total_value; ?>" placeholder="Ex: 999"
                       required/>
            </label>

            <label>
                <span class="field icon-leanpub">Valor Líquido:</span>
                <input class="radius" type="number" name="net_value" value="<?= $list->net_value; ?>" placeholder="Ex: 999"
                       required/>
            </label>
        </div>
        <div class="al-center">
            <div>
                <span data-listremove="<?= url("/app/remove-list/{$list->id}") ?>" class="btn_remove transition icon-error">Excluir</span>
                <button class="btn btn_inline radius transition icon-pencil-square-o">Atualizar</button>
            </div>
        </div>
    </form>
</div>