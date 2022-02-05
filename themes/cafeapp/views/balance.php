<div class="balance <?= $status; ?>">
    <p class="desc">
        <b class="app_invoice_link transition"><a href="<?= url("app/fluxo-de-caixa/{$id}"); ?>">
                <?= isnt_empty($description, 'self', 'sem descrição')?></a></b>
        <span class="date"><?= date_fmt($month, 'd/m'); ?></span>
    </p>
    <p class="price">
        <?= money_fmt_br(isnt_empty($value, 'self', '0.00'), true); ?>
        <span title="Receber" class="check icon-thumbs-o-down transition"
              data-toggleclass="active icon-thumbs-o-down icon-thumbs-o-up"></span>
    </p>
</div>