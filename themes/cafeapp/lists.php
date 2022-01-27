<?php $v->layout("_theme");
/** @var \Source\Models\Lists $list  */
?>

<div class="app_launch_header">
    <div class="app_flex_title">
        <h2><a class="color_white font_80_percent icon-user padding_btn transition gradient gradient-green gradient-hover radius box-shadow"
               title="usuários">Registros de Listas</a></h2>
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
             <option value="8">Viagens</option>'
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
    <div class="app_launch_btn income radius transition icon-plus-circle" data-modalopen=".app_modal_list">
        Nova Lista
    </div>
</div>

<section class="app_launch_box">
    <div class="app_launch_item header">
        <p class="desc">ID</p>
        <p class="enrollment">dia da semana</p>
        <p class="desc">Horário desejado</p>
        <p class="date">Horário de Movimentação</p>
        <p class="desc">Loja</p>
        <p class="enrollment">Valor Bruto</p>
        <p class="price">Valor Líquido</p>
        <p class="enrollment">Valor de Comissão</p>
        <p></p>
    </div>
    <?php foreach ($lists as $list):?>
        <article class="app_launch_item">
            <p class="desc"><?= $list->id; ?></p>
            <p class="enrollment app_invoice_link transition">
                <a title="Ver Lista" href="<?= url("/app/horario/{$list->id}"); ?>"><?= $list->week_day; ?></a>
            </p>
            <!--03 de 12-->
            <!--<span class="icon-exchange">Fixa</span>-->
            <p class="desc"><?= $list->description; ?></p>
            <p class="date"><?= date_fmt($list->date_moviment, 'd/m/Y') . ' ' . $list->week_day;  ?></p>
            <p class="desc"><?= $list->nome_loja; ?></p>
            <p class="enrollment"><?= money_fmt_br($list->total_value,true); ?></p>
            <p class="price"><?= money_fmt_br($list->net_value, true); ?></p>
            <p class="enrollment "><?= money_fmt_br($list->comission_value, true); ?></p>
            <p class=" app_invoice_link transition">
                <a class="icon-thumb-tack" style="text-decoration: none;" href="<?= url("/app/lista/{$list->id}")?>">Editar</a>
            </p>
        </article>
    <?php endforeach; ?>
    <div class="app_launch_item footer">
        <p class="desc"></p>
        <p></p>
        <p>Valor total:</p>
        <p class="icon-thumbs-o-up">R$ <?= $allMoney->value;?></p>
    </div>
    <?= $paginator; ?>
</section>
