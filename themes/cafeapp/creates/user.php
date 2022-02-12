<?php $v->layout("_theme"); ?>

<div class="app_invoice app_widget">
    <div class="color_888 app_header">
        <h2 class=" icon-calendar-check-o ">Cadastrar Usuário:</h2>
    </div>
    <form class="app_form" action="<?= url("/app/cadastrar"); ?>" method="post">
        <div class="ajax_response"><?= flash(); ?></div>
        <?= csrf_input(); ?>
        <label>
            <span class="field icon-life-ring">Nível?</span>
            <select name="type">
                <option value="1">&ofcir; admin</option>
                <option value="2">&ofcir; User</option>
            </select>
        </label>

        <label>
            <span class="field icon-thumb-tack">Nome:</span>
            <input class="radius" type="text" name="first_name" placeholder="Primeiro nome:" required/>
        </label>

        <label>
            <span class="field icon-user">Sobrenome:</span>
            <input class="radius" type="text" name="last_name" placeholder="Último nome:" required/>
        </label>

        <label>
            <span class="field icon-comments">E-mail:</span>
            <input class="radius" type="email" name="email" placeholder="E-mail:" required/>
        </label>

        <label>
            <span class="field icon-unlock-alt">Senha:</span>
            <input class="radius" type="password" name="password" placeholder="Senha de acesso:" required/>
        </label>

        <button class="btn radius transition icon-paper-plane-o">Cadastrar</button>
    </form>
</div>