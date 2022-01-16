<?php $v->layout("_theme"); ?>

<div class="app_launch_header">
    <form class="app_launch_form_filter app_form" action="" method="post">
        <select name="status">
            <option value="">Todos</option>
            <option value="paid">filtro</option>
            <option value="unpaid">filtro</option>
        </select>

        <select name="category">
            <option value="">filtro</option>
            <option value="1">Alimentação</option>
            <option value="3">Alugueis</option>
            <option value="2">Compras</option>
            <option value="4">Educação</option>
            <option value="5">Entretenimento</option>
            <option value="6">Impostos e taxas</option>
            <option value="7">Saúde</option>
            <option value="8">Viagens</option>
            <option value="9">Outras despesas</option>
        </select>

        <input list="datelist" type="text" class="radius mask-month" name="date" placeholder="<?= date("m/Y"); ?>">
        <datalist id="datelist">
            <?php for ($range = -2; $range <= 3; $range++): $date = date("m/Y", strtotime("{$range}month")); ?>
                <option value="<?= $date; ?>"/>
            <?php endfor; ?>
        </datalist>
        <button class="filter radius transition icon-filter icon-notext"></button>
    </form>

    <div class="app_launch_btn expense radius transition icon-plus-circle" data-modalopen=".app_modal_expense">
        Botão sem função
    </div>
    <div class="app_launch_btn expense radius transition icon-plus-circle" data-modalopen=".app_modal_user">
        Novo usuário
    </div>
</div>
<div class="ajax_response"><?= flash(); ?></div>

<section class="app_launch_box">
    <div class="app_launch_item header">
        <p class="desc">Nome</p>
        <p class="date">Nascimento</p>
        <p class="E-mail">Função</p>
        <p class="enrollment">algo</p>
        <p class="price">Editar</p>
    </div>
    <?php foreach ($users as $user):?>
        <article class="app_launch_item">
            <p class="desc app_invoice_link transition">
                <a title="Ver fatura" href="<?= url("/app/fatura/1"); ?>"><?= $user->first_name ?></a>
            </p>
            <p class="date"><?= date_fmt_br($user->created_at) ?></p>
            <p class="E-mail"><?= $user->email; ?></p>
            <p class="enrollment">
                <span class="icon-calendar-check-o">algo</span>
                <!--03 de 12-->
                <!--<span class="icon-exchange">Fixa</span>-->
            </p>
            <p class="price">
                <a class="btn transition " href="<?= url("/app/perfil/{$user->id}")?>">Editar</a>
            </p>
        </article>
    <?php endforeach; ?>
    <div class="app_launch_item footer">
        <p class="desc"></p>
        <p></p>
        <p></p>
        <p class="icon-calendar-check-o">R$ 36,000.00</p>
        <p class="icon-thumbs-o-up">R$ 28,000.00</p>
    </div>
    <?= $paginator; ?>
</section>
