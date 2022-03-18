<?php $v->layout("_theme"); ?>

<div class="app_invoice app_widget">
    <div class="color_888 app_header">
        <h2 class=" icon-calendar-check-o ">Cadastrar Centro de Custo:</h2>
    </div>
    <form class="app_form" action="<?= url("/app/centro-salvar"); ?>" method="post">
        <div class="ajax_response"><?= flash(); ?></div>

        <label>
            <span class="field icon-leanpub">Descrição:</span>
            <input class="radius" type="text" name="description" placeholder="Ex: Recibos" required/>
        </label>


        <div class="label_check">
            <p class="field icon-exchange">Emitir Recibos:</p>

            <label data-checkbox="true"
                   data-slideup=".repeate_item_expense"
                   data-slidedown=".repeate_item_income">
                <input type="radio" name="emit" value="1"> Sim
            </label>

            <label data-checkbox="true"
                   data-slideup=".repeate_item_income"
                   data-slidedown=".repeate_item_expense">
                <input type="radio" name="emit" value="2"> Não
            </label>
        </div>

        <button class="btn radius transition icon-check-square-o">Cadastrar Centro de Custo</button>
    </form>
</div>