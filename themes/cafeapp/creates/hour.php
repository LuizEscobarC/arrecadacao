<?php $v->layout("_theme"); ?>

<div class="app_invoice app_widget">
    <div class="color_888 app_header">
        <h2 class=" icon-calendar-check-o ">Cadastrar Horário:</h2>
    </div>
    <form class="app_form" action="<?= url("/app/horario"); ?>" method="post">
        <div class="ajax_response"><?= flash(); ?></div>

        <label>
            <span class="field icon-leanpub">Nome do Horário:</span>
            <input class="radius" type="text" name="description" placeholder="Ex: Primeiro turno" required/>
        </label>


        <div class="select2-container">
            <p class="field check icon-exchange">Escolha o dia:</p>
            <label>
                <select name="number_day">
                    <option value="0">Domingo</option>
                    <option value="1">Segunda</option>
                    <option value="2">Terça</option>
                    <option value="3">Quarta</option>
                    <option value="4">Quinta</option>
                    <option value="5">Sexta</option>
                    <option value="6">Sábado</option>
                </select>
            </label>

        </div>

        <div class="al-center">
            <div>
                <button class="btn btn_inline radius transition icon-pencil-square-o">Cadastrar Horário</button>
            </div>
        </div>
    </form>
</div>