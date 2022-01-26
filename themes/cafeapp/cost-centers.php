<?php $v->layout("_theme"); ?>

<div class="app_launch_header">
    <div class="app_flex_title">
        <h2><a class=" font_80_percent icon-user padding_btn transition gradient gradient-green gradient-hover radius box-shadow"
               title="usuários">Registros de centro de custos</a></h2>
    </div>
    <!-- FILTROS <form class="app_launch_form_filter app_form" action="" method="post">
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

        <input list="datelist" type="text" class="radius mask-month" name="date" placeholder="< date("m/Y"); ?>">
        <datalist id="datelist">
            < for ($range = -2; $range <= 3; $range++): $date = date("m/Y", strtotime("{$range}month")); ?>
                <option value="< $date; ?>"/>
            < endfor; ?>
        </datalist>
        <button class="filter radius transition icon-filter icon-notext"></button>
    </form>
    -->

    <!--<div class="app_launch_btn expense radius transition icon-plus-circle" data-modalopen=".app_modal_expense">
        Botão sem função
    </div>-->
    <div class="app_launch_btn income radius transition icon-plus-circle" data-modalopen=".app_modal_cost">
        Novo centro de custo
    </div>
</div>
<div class="ajax_response"><?= flash(); ?></div>

<section class="app_launch_box">
    <div class="app_launch_item header">
        <p class="desc">descrição</p>
        <p class="emit">Emitir</p>
        <p class="price">Data de Criação</p>
    </div>
    <?php foreach ($costCenters as $center):?>
        <article class="app_launch_item">
            <p class="desc app_invoice_link transition">
                <a title="Ver fatura" href="<?= url(); ?>"><?= $center->description; ?></a>
            </p>
            <p class="emit"><?= $center->emit; ?></p>
            <p class="date"><?= date_fmt_br($center->created_at) ?></p>
                <!--03 de 12-->
                <!--<span class="icon-exchange">Fixa</span>-->
            </p>
            <p class="price app_invoice_link transition">
                <a class="icon-user" style="text-decoration: none;" href="<?= url("/app/centro-de-custo/{$center->id}")?>">Editar</a>
            </p>
        </article>
    <?php endforeach; ?>
    <div class="app_launch_item footer">
        <p class="desc"></p>
        <p></p>
        <p></p>
    </div>
    <?= $paginator; ?>
</section>
