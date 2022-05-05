<?php $v->layout("_theme"); ?>

<div class="app_invoice app_widget">
    <div class="color_888 app_header">
        <h2 class=" icon-calendar-check-o ">horários de hoje:</h2>
    </div>
    <div class="app_launch_item header">
        <p class="desc">dia da semana</p>
        <p class="desc">Nome do horário</p>
        <p class="date">data de movimento</p>
        <p class="desc_center"></p>
    </div>
    <?php if (isnt_empty($hours, 'self')): ?>
        <?php foreach ($hours as $hour): ?>
            <article class="app_launch_item">
                <p class="desc app_invoice_link transition">
                    <a title="Ver horário" href="<?= url("app/horario/{$hour->id}"); ?>"><?= $hour->week_day; ?></a>
                </p>
                <p class="desc"><?= $hour->description; ?></p>
                <p class="date"><?= date_fmt('now', 'd/m/Y'); ?></p>
                <!--03 de 12-->
                <!--<span class="icon-exchange">Fixa</span>-->
                <p class=" desc_center font_80_percent transition radius">
                    <a class="<?= ($hour->status == 1
                        ? 'btn_red transition radius color_white'
                        : 'btn_green transition radius color_white') ?> change_hour_setting"
                       style="text-decoration: none;"
                       href="<?= url("/configuracoes/fechamento-de-horario/{$hour->id}") ?>"
                       data-status="<?= $hour->status; ?>"
                    >
                        <?= ($hour->status == 1 ? 'Fechar Horário' : 'Abrir Horário') ?></a>
                </p>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="color_888  margin-height-20 app_invoice app_widget">
    <div class="color_888 app_header">
        <h2 class=" icon-calendar-check-o ">Abater Lojas Inadimplentes do Horário:</h2>
    </div>
    <!-- form -->
    <form class="app_form form_calc_store ajax_off" action="<?= url("/configuracoes/abate-de-lojas-inadimplentes"); ?>" method="POST">
        <div class="ajax_response"><?= flash(); ?></div>

        <div class="label_group">
            <label class="three_label">
                <span class="field icon-thumb-tack">DATA DE MOVIMENTO:</span>
                <input class="radius hour" rel="<?= url('/app/get_hour') ?>" type="date" name="date_moviment"
                       required/>
            </label>

            <label class="three_label">
                <p id="label" class="app_widget_title"></p>
            </label>

            <label class="three_label">
                <span class="field icon-briefcase"> HORÁRIO:</span>
                <input type="hidden" class="current_hour" name="current_hour"
                       value="<?= ($currentHour ? $currentHour->id : null); ?>">
                <select name="id_hour" class="callback" rel="<?= url("/app/get_week_day") ?>">
                </select>
            </label>
        </div>

            <div class="margin-height-20">
                <button data-link="<?= url('configuracoes/abate-de-lojas-inadimplentes'); ?>" title="config"
                        class="btn padding_btn app_launch_btn color_888 font_120_percent radius transition"
                        style="text-decoration: none;">Calcular listas de lojas inadimplentes.
                </button>
            </div>
    </form>
</div>
