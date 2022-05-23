<div class="app_sidebar_nav">

    <a class="pointer icon-home radius transition" id="sidebar" title="Dashboard">MOVIMENTAÇÃO DIÁRIA</a>
    <div class="app_drop">
        <a class="pointer radius transition" title="Dashboard" id="open_moviment_create">CADASTROS</a>
        <div class="drop_moviment_create">
            <a class="pointer radius transition" title="Dashboard" href="<?= url("/app/cadastrar-lista"); ?>">Cadastrar Listas do horário</a>
            <a class="pointer radius transition" title="Dashboard" href="<?= url("/app/cadastrar-movimentacao"); ?>">Cadastrar Acerto de Loja</a>
            <a class="pointer radius transition" title="Dashboard" href="<?= url("/app/cadastrar-fluxo-de-caixa"); ?>">Cadastrar Receita/Despesa</a>
        </div>

        <a class="pointer radius transition" title="Dashboard" id="open_moviment_read">CONSULTAS</a>
        <div class="drop_moviment_read">
            <a class="pointer radius transition" title="Dashboard" href="<?= url("/app/listas"); ?>">Listas do Horário</a>
            <a class="pointer radius transition" title="Dashboard" href="<?= url("/app/movimentacoes"); ?>">Acertos de Loja</a>
            <a class="pointer radius transition" title="Dashboard" href="<?= url("/app/fluxos-de-caixa"); ?>">Receitas e Despesas</a>
            <a class="pointer radius transition" title="Dashboard" href="<?= url('/consultas/premios-pagos'); ?>">Prêmios Pagos</a>
            <!--<a class="pointer radius transition" title="Dashboard" href="<?php //url('/consultas/lancamento-de-entradas'); ?>">Lançamento de entradas</a>
            <a class="pointer radius transition" title="Dashboard" href="<?php //url('/consultas/despesas-pagas'); ?>">Consultar Despesas Pagas</a> -->
            <a class="pointer radius transition" title="Dashboard" href="<?= url('/consultas/consultar-saldo-da-loja'); ?>">Consultar Saldo da Loja</a>
        </div>
        <a class="pointer radius transition" id="sidebar_children2" title="Dashboard">Relatórios</a>
    </div>


    <a class="pointer icon-home radius transition" id="sidebar2" title="Dashboard">CONTROLE DE CAIXA</a>
    <div class="app_drop1">
        <a class="pointer radius transition" title="Dashboard" href="<?= url("/configuracoes/horario"); ?>">Fechamento de caixa do horário</a>
        <a class="pointer icon-home radius transition" id="sidebar2_children" title="Dashboard">Relatórios</a>
        <div class="app_drop1_children">
            <a class="pointer radius transition" title="Dashboard" href="#">opção</a>
        </div>
    </div>


    <a class="pointer icon-home radius transition" id="sidebar3" title="Dashboard">CONTROLE GERENCIAL</a>
    <div class="app_drop2">
        <a class="pointer radius transition" title="Dashboard" id="open_manager_create">CADASTROS</a>
            <div class="drop_manager_create">
            <?php if (\Source\Models\Auth::user()->level == 1): ?>
                <a class="pointer radius transition" title="Dashboard" href="<?= url("/app/cadastrar-usuario"); ?>">Cadastrar Usuário</a>
            <?php endif;?>
            <a class="pointer radius transition" title="Dashboard" href="<?= url("/app/cadastrar-centro-de-custo"); ?>">Cadastrar Centro de Custo</a>
            <a class="pointer radius transition" title="Dashboard" href="<?= url("/app/cadastrar-loja"); ?>">Cadastrar Loja</a>
            <a class="pointer radius transition" title="Dashboard" href="<?= url("/app/cadastrar-horario"); ?>">Cadastrar Horário</a>
        </div>
        <a class="pointer radius transition" title="Dashboard" id="open_manager_read">CONSULTAS</a>
            <div class="drop_manager_read">
            <?php if (\Source\Models\Auth::user()->level == 1): ?>
                <a class="pointer radius transition" title="Dashboard" href="<?= url("/app/usuarios"); ?>">Usuários</a>
            <?php endif;?>
            <a class="pointer radius transition" title="Dashboard" href="<?= url("/app/centros-de-custo"); ?>">Centros de Custo</a>
            <a class="pointer radius transition" title="Dashboard" href="<?= url("/app/lojas"); ?>">Lojas</a>
            <a class="pointer radius transition" title="Dashboard" href="<?= url("/app/horarios"); ?>">Horarios</a>
        </div>
    </div>


    <a class="icon-sign-out radius transition" title="Sair" href="<?= url("/app/sair"); ?>">Sair</a>
</div>