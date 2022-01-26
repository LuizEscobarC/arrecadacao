<?php $v->layout("_theme"); ?>
<div class="app_invoice app_widget">
    <div class="color_888 app_header">
        <h2 class=" icon-calendar-check-o ">Atualizar Centro de Custo:</h2>
    </div>
    <form class="app_form" action="<?= url("/app/centro-salvar"); ?>" method="post">
        <div class="ajax_response"><?= flash(); ?></div>

        <input class="radius" type="hidden" name="id" value="<?= $costCenter->id; ?>" required/>

        <label>
            <span class="field icon-leanpub">Descrição:</span>
            <input class="radius" type="text" name="description" value="<?= $costCenter->description; ?>" placeholder="Ex: Bentão" required/>
        </label>


        <div class="label_check">
            <p class="field icon-exchange">Emitir Recibos:</p>
            <label data-checkbox="true"
                   data-slideup=".repeate_item_expense"
                   data-slidedown=".repeate_item_income" class="<?= ($costCenter->emit == 1 ? "check" : null) ?>">
                <input type="radio" name="emit" value="1" <?= ($costCenter->emit == 1 ? "checked" : null) ?>> Sim
            </label>

            <label data-checkbox="true"
                   data-slideup=".repeate_item_expense"
                   data-slidedown=".repeate_item_income" class="<?= ($costCenter->emit == 2 ? "check" : null) ?>">
                <input type="radio" name="emit" value="2" <?= ($costCenter->emit == 2 ? "checked" : null) ?>> Não
            </label>
        </div>

        <div class="al-center">
            <div>
                <span data-centerremove="<?= url("/app/remove-center/{$costCenter->id}") ?>" class="btn_remove transition icon-error">Excluir</span>
                <button class="btn btn_inline radius transition icon-pencil-square-o">Atualizar</button>
            </div>
        </div>
    </form>
</div>