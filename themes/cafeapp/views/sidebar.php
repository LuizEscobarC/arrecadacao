<div class="app_sidebar_nav">
    <a class="pointer icon-home radius transition open" id="sidebar" title="Dashboard">MOVIMENTAÇÃO DIÁRIA</a>
    <div class="app_drop">
        <a class="pointer radius transition open" id="sidebar_children" title="Dashboard">Lançamentos</a>
        <div class="app_drop_children">
            <a class="pointer radius transition" title="Dashboard" href="<?= url("/app/listas"); ?>">Importação de Listas</a>
            <a class="pointer radius transition" title="Dashboard" href="<?= url("/app"); ?>">Pagar e Receber</a>
        </div>
        <a class="pointer radius transition open" id="sidebar_children2" title="Dashboard">Relatórios e
            Listagens</a>
        <div class="app_drop_children2">
            <a class="pointer radius transition" title="Dashboard" href="<?= url("/app"); ?>">opção 1</a>
            <a class="pointer radius transition" title="Dashboard" href="<?= url("/app"); ?>">opção 2</a>
        </div>
    </div>
    <a class="pointer icon-home radius transition open" id="sidebar2" title="Dashboard">CONTROLE DE CAIXA</a>
    <div class="app_drop1">
        <a class="pointer icon-home radius transition open" id="sidebar2_children" title="Dashboard">opção</a>
        <div class="app_drop1_children">
            <a class="pointer radius transition" title="Dashboard" href="<?= url("/app"); ?>">opção</a>
            <a class="pointer radius transition" title="Dashboard" href="<?= url("/app"); ?>">opção</a>
            <a class="pointer radius transition" title="Dashboard" href="<?= url("/app"); ?>">opção</a>
            <a class="pointer radius transition" title="Dashboard" href="<?= url("/app"); ?>">opção</a>
        </div>
    </div>
    <a class="pointer icon-home radius transition open" id="sidebar3" title="Dashboard">CONTROLE GERENCIAL</a>
    <div class="app_drop2">
        <a class="pointer icon-home radius transition open" id="sidebar3_children" title="Dashboard">Cadastros</a>
        <div class="app_drop2_children">
            <a class="pointer radius transition" title="Meu Perfil" href="<?= url("/app/usuarios"); ?>">Usuário</a>
            <a class="pointer radius transition" title="Meu Perfil" href="<?= url("/app/lojas"); ?>">Lojas</a>
            <a class="pointer radius transition" title="Meu Perfil" href="<?= url("/app/centros-de-custo"); ?>">Centro de
                Custo</a>
            <a class="pointer radius transition" title="Meu Perfil" href="<?= url("/app/horarios"); ?>">Horários</a>
        </div>
    </div>


    <a class="icon-sign-out radius transition" title="Sair" href="<?= url("/app/sair"); ?>">Sair</a>
</div>