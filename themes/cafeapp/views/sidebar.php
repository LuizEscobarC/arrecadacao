<div class="app_sidebar_nav">
    <a class="pointer icon-home radius transition open" id="sidebar" title="Dashboard">MOVIMENTAÇÃO DIÁRIA</a>
    <div class="app_drop">
        <a class="pointer radius transition" title="Dashboard" href="<?= url("/app/cadastrar-lista"); ?>">Cadastrar Listas do horário</a>
        <a class="pointer radius transition" title="Dashboard" href="">Cadastrar Acerto de Loja</a>
        <a class="pointer radius transition" title="Dashboard" href="<?= url("/app/listas"); ?>">Listas do Horário</a>
        <a class="pointer radius transition open" id="sidebar_children2" title="Dashboard">Relatórios</a>
        <div class="app_drop_children2">
            <a class="pointer radius transition" title="Dashboard" href="<?= url("/app"); ?>">opção 1</a>
            <a class="pointer radius transition" title="Dashboard" href="<?= url("/app"); ?>">opção 2</a>
        </div>
    </div>
    <a class="pointer icon-home radius transition open" id="sidebar2" title="Dashboard">CONTROLE DE CAIXA</a>
    <div class="app_drop1">
        <a class="pointer radius transition" title="Dashboard" href="<?= url("/app/cadastrar-fluxo-de-caixa"); ?>">Cadastrar Receita/Despesa</a>
        <a class="pointer radius transition" title="Dashboard" href="<?= url("/app/fluxos-de-caixa"); ?>">Receitas e Despesas</a>
        <a class="pointer icon-home radius transition open" id="sidebar2_children" title="Dashboard">Relatórios</a>
        <div class="app_drop1_children">
            <a class="pointer radius transition" title="Dashboard" href="#">opção</a>
        </div>
    </div>
    <a class="pointer icon-home radius transition open" id="sidebar3" title="Dashboard">CONTROLE GERENCIAL</a>
    <div class="app_drop2">
        <a class="pointer radius transition" title="Dashboard" href="<?= url("/app/cadastrar-usuario"); ?>">Cadastrar Usuário</a>
        <a class="pointer radius transition" title="Dashboard" href="<?= url("/app/cadastrar-centro-de-custo"); ?>">Cadastrar Centro de Custo</a>
        <a class="pointer radius transition" title="Dashboard" href="<?= url("/app/cadastrar-loja"); ?>">Cadastrar Loja</a>
        <a class="pointer radius transition" title="Dashboard" href="<?= url("/app/cadastrar-horario"); ?>">Cadastrar Horário</a>
        <a class="pointer radius transition" title="Dashboard" href="<?= url("/app/usuarios"); ?>">Usuários</a>
        <a class="pointer radius transition" title="Dashboard" href="<?= url("/app/centros-de-custo"); ?>">Centros de Custo</a>
        <a class="pointer radius transition" title="Dashboard" href="<?= url("/app/lojas"); ?>">Lojas</a>
        <a class="pointer radius transition" title="Dashboard" href="<?= url("/app/horarios"); ?>">Horarios</a>
    </div>


    <a class="icon-sign-out radius transition" title="Sair" href="<?= url("/app/sair"); ?>">Sair</a>
</div>