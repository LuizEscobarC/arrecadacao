<?php $v->layout("_theme"); ?>

<div class="app_invoice app_widget">
    <form class="app_form" action="<?= url("/app/cadastrar"); ?>" method="post">
        <div class="ajax_response"><?= flash(); ?></div>
        <input class="radius" type="hidden" name="id" value="<?= $user->id ?>" required/>
        <div class="label_group">
            <label>
                <span class="field icon-user">Nome:</span>
                <input class="radius" type="text" name="first_name" value="<?= $user->first_name ?>" placeholder="Primeiro nome" required/>
            </label>

            <label>
                <span class="field icon-user-plus">Sobrenome:</span>
                <input class="radius" type="text" name="last_name" value="<?= $user->last_name ?>" placeholder="Último nome" required/>
            </label>
        </div>

        <!--<label>
            <span class="field icon-briefcase">Genero:</span>
            <select name="wallet">
                <option value="m">Masculino</option>
                <option value="f">Feminino</option>
                <option value="o">Não definir</option>
            </select>
        </label> -->

       <!-- <div class="label_group">
            <label>
                <span class="field icon-calendar">Nascimento:</span>
                <input class="radius mask-date" type="text" name="datebirth" placeholder="dd/mm/yyyy" required/>
            </label>

            <label>
                <span class="field icon-briefcase">CPF:</span>
                <input class="radius mask-doc" type="text" name="document" placeholder="Apenas números" required/>
            </label>
        </div> -->

        <label>
            <span class="field icon-envelope">E-mail:</span>
            <input class="radius" type="email" name="email" value="<?= $user->email ?>" placeholder="Seu e-mail de acesso" required/>
        </label>

        <div class="label_group">
            <label>
                <span class="field icon-unlock-alt">Senha:</span>
                <input class="radius" type="password" name="password"  placeholder="Sua senha de acesso" required/>
            </label>

            <label>
                <span class="field icon-unlock-alt">Repetir Senha:</span>
                <input class="radius" type="password" name="password_re" placeholder="Sua senha de acesso" required/>
            </label>
        </div>

        <div class="al-center">
            <div>
                <span data-userremove="<?= url("/app/remove-user/{$user->id}") ?>" class="btn_remove transition icon-error">Excluir</span>
                <button class="btn btn_inline radius transition icon-pencil-square-o">Atualizar</button>
            </div>
        </div>
    </form>
</div>