<?php $v->layout("_theme"); ?>
<div class="app_invoice app_widget">
    <div class="color_888 app_header">
        <h2 class=" icon-calendar-check-o ">Atualizar Loja:</h2>
    </div>
    <form class="app_form" action="<?= url("/app/loja-salvar"); ?>" method="post">
        <div class="ajax_response"><?= flash(); ?></div>
        <input class="radius" type="hidden" name="id" value="<?= $store->id; ?>" required/>

        <label>
            <span class="field icon-leanpub">Código da loja:</span>
            <input class="radius" type="text" name="code" value="<?= $store->code; ?>"
                   placeholder="Ex: 1234 ou N123" required/>
        </label>

        <label>
            <span class="field icon-leanpub">Nome:</span>
            <input class="radius" type="text" name="nome_loja" value="<?= $store->nome_loja; ?>"
                   placeholder="Ex: Bentão" required/>
        </label>

        <label>
            <span class="field icon-leanpub">Valor:</span>
            <input class="radius mask-money-negative" type="text" name="valor_saldo" value="<?= money_fmt_br($store->valor_saldo); ?>"
                   placeholder="Ex: Bentão" required/>
        </label>

        <label>
            <span class="field icon-leanpub">Comissão:</span>
            <input class="radius mask-money-negative" type="text" name="comissao" value="<?= money_fmt_br($store->comissao); ?>" placeholder="Ex: Bentão"/>
        </label>

        <div class="label_group">
            <label>
                <span class="field icon-money">Valor Aluguel:</span>
                <input class="radius mask-money" type="text" name="valor_aluguel" value="<?= money_fmt_br($store->valor_aluguel); ?>"/>
            </label>

            <label>
                <span class="field icon-filter">Aluguel Dia:</span>
                <input class="radius filter_day mask-day" type="text" name="aluguel dia" value="<?= money_fmt_br($store->comissao); ?>"/>
            </label>
        </div>

        <div class="label_group">
            <label>
                <span class="field icon-money">Valor Gratificação:</span>
                <input class="radius mask-money" type="text" name="valor_gratificacao"
                       value="<?= money_fmt_br($store->valor_gratificacao); ?>"/>
            </label>

            <label>
                <span class="field icon-filter">Gratificação Dia:</span>
                <input class="radius filter_day mask-day" type="text" name="gratificacao_dia"
                       value="<?= $store->gratificacao_dia; ?>"/>
            </label>
        </div>

        <div class="al-center">
            <div>
                <span data-storeremove="<?= url("/app/remove-store/{$store->id}") ?>"
                      class="btn_remove transition icon-error">Excluir</span>
                <button class="btn btn_inline radius transition icon-pencil-square-o">Atualizar</button>
            </div>
        </div>
    </form>
</div>