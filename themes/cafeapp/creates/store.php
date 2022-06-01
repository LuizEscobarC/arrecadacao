<?php $v->layout("_theme"); ?>

<div class="app_invoice app_widget">
    <div class="color_888 app_header">
        <h2 class=" icon-calendar-check-o ">Cadastrar Loja:</h2>
    </div>
    <form class="app_form" action="<?= url("/app/loja-salvar"); ?>" method="post">
        <div class="ajax_response"><?= flash(); ?></div>

        <label>
            <span class="field icon-leanpub">Código da loja:</span>
            <input class="radius box-shadow" type="text" name="code"
                   placeholder="Ex: 1234 ou N123" required/>
        </label>

        <label>
            <span class="field icon-leanpub">Nome:</span>
            <input class="radius box-shadow" type="text" name="nome_loja" placeholder="Ex: Bentão" required/>
        </label>


        <label>
            <span class="field icon-leanpub">Valor Saldo:</span>
            <input class="radius mask-money-negative box-shadow" type="text" name="valor_saldo" required/>
        </label>

        <label>
            <span class="field icon-leanpub">Comissão:</span>
            <input class="radius mask-money-negative box-shadow" type="text" name="comissao" required/>
        </label>

        <div class="label_group">
            <label>
                <span class="field icon-money">Valor Aluguel:</span>
                <input class="radius mask-money-negative" type="text" name="valor_aluguel" />
            </label>

            <label>
                <span class="field icon-filter">Aluguel Dia:</span>
                <input class="radius" type="text" name="aluguel_dia" />
            </label>
        </div>

        <div class="label_group">
            <label>
                <span class="field icon-money">Valor Gratificação:</span>
                <input class="radius mask-money-negative" type="text" name="valor_gratificacao" />
            </label>

            <label>
                <span class="field icon-filter">Gratificação Dia:</span>
                <input class="radius" type="text" name="gratificacao_dia" />
            </label>
        </div>

        <button class="btn radius transition icon-check-square-o">Cadastrar Loja</button>
    </form>
</div>