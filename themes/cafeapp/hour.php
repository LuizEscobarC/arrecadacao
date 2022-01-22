<?php $v->layout("_theme"); ?>
<div class="app_invoice app_widget">
    <form class="app_form" action="<?= url("/app/horario"); ?>" method="post">
        <div class="ajax_response"><?= flash(); ?></div>

        <input class="radius" type="hidden" name="id" value="<?= $hour->id; ?>" required/>

        <label>
            <span class="field icon-leanpub">Nome do Horário:</span>
            <input class="radius" type="text" name="description" value="<?= $hour->description; ?>"
                   placeholder="Ex: Bentão" required/>
        </label>


        <div class="label_check">

            <p class="field check icon-exchange">Escolha o dia:</p>
            <select name="number_day">
                <option value="">Todas</option>
                <option <?= ($hour->number_day == 1 ? 'selected' : null) ?> value="1">Domingo</option>
                <option <?= ($hour->number_day == 2 ? 'selected' : null) ?> value="2">Segunda</option>
                <option <?= ($hour->number_day == 3 ? 'selected' : null) ?> value="3">Terça</option>
                <option <?= ($hour->number_day == 4 ? 'selected' : null) ?> value="4">Quarta</option>
                <option <?= ($hour->number_day == 5 ? 'selected' : null) ?> value="5">Quinta</option>
                <option <?= ($hour->number_day == 6 ? 'selected' : null) ?> value="6">Sexta</option>
                <option <?= ($hour->number_day == 7 ? 'selected' : null) ?> value="7">Sábado</option>
            </select>

        </div>

        <div class="al-center">
            <div>
                <span data-hourremove="<?= url("/app/remove-hour/{$hour->id}") ?>" class="btn_remove transition icon-error">Excluir</span>
                <button class="btn btn_inline radius transition icon-pencil-square-o">Atualizar</button>
            </div>
        </div>
    </form>
</div>