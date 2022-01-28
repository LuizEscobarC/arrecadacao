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
            <input class="radius mask-money" type="text" name="valor_saldo" value="<?= $store->valor_saldo; ?>"
                   placeholder="Ex: Bentão" required/>
        </label>

        <label>
            <span class="field icon-leanpub">Comissão:</span>
            <input class="radius" type="text" name="comissao" value="<?= $store->comissao; ?>" placeholder="Ex: Bentão"
                   required/>
        </label>

        <div class="label_group">
            <label>
                <span class="field icon-money">Valor Aluguel:</span>
                <input class="radius mask-money" type="text" name="valor_aluguel" value="<?= $store->valor_aluguel; ?>"
                       required/>
            </label>

            <label>
                <span class="field icon-filter">Aluguel Dia:</span>
                <input class="radius" type="text" name="aluguel dia" value="<?= $store->comissao; ?>" required/>
            </label>
        </div>

        <div class="label_group">
            <label>
                <span class="field icon-money">Valor Gratificação:</span>
                <input class="radius mask-money" type="text" name="valor_gratificacao"
                       value="<?= $store->valor_gratificacao; ?>" required/>
            </label>

            <label>
                <span class="field icon-filter">Gratificação Dia:</span>
                <input class="radius" type="text" name="gratificacao_dia"
                       value="<?= $store->gratificacao_dia; ?>" required/>
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