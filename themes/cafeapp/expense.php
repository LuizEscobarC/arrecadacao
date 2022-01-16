<?php $v->layout("_theme"); ?>

<div class="app_launch_header">
    <form class="app_launch_form_filter app_form" action="" method="post">
        <select name="status">
            <option value="">Todas</option>
            <option value="paid">Receitas recebidas</option>
            <option value="unpaid">Receitas não recebidas</option>
        </select>

        <select name="category">
            <option value="">Todas</option>
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

    <div class="app_launch_btn expense radius transition icon-plus-circle" data-modalopen=".app_modal_expense">Lançar
        Despesa
    </div>
</div>

<section class="app_launch_box">
    <div class="app_launch_item header">
        <p class="desc">Descrição</p>
        <p class="date">Vencimento</p>
        <p class="category">Categoria</p>
        <p class="enrollment">Parcela</p>
        <p class="price">Valor</p>
    </div>
    <?php for ($day = 1; $day <= date('t'); $day++): ?>
        <article class="app_launch_item">
            <p class="desc app_invoice_link transition">
                <a title="Ver fatura" href="<?= url("/app/fatura/1"); ?>">Alguel</a>
            </p>
            <p class="date"><?= str_pad($day, 2, 0, 0); ?>/11/2018</p>
            <p class="category">Outras despesas</p>
            <p class="enrollment">
                <span class="icon-calendar-check-o">Única</span>
                <!--03 de 12-->
                <!--<span class="icon-exchange">Fixa</span>-->
            </p>
            <p class="price">
                <span>R$</span>
                <span>1.200,00</span>
                <span title="Receber" class="check expense icon-thumbs-o-down transition"
                      data-toggleclass="active icon-thumbs-o-down icon-thumbs-o-up"></span>
            </p>
        </article>
    <?php endfor; ?>
    <div class="app_launch_item footer">
        <p class="desc"></p>
        <p></p>
        <p></p>
        <p class="icon-calendar-check-o">R$ 36,000.00</p>
        <p class="icon-thumbs-o-up">R$ 28,000.00</p>
    </div>
</section>
