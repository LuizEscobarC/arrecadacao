<?php $v->layout("_theme"); ?>
    <div class="ajax_response"><?= flash(); ?></div>
    <div class="app_main_box">
        <section class="app_main_left">
            <article class="app_widget">
                <header class="app_widget_title">
                    <h2 class="icon-bar-chart">Controle:(<small>útimos </small> 30 dias)</h2>
                </header>
                <div id="control"></div>
            </article>

            <div class="app_main_left_fature">
                <article class="app_widget app_widget_balance">
                    <header class="app_widget_title">
                        <h2 class="icon-calendar-minus-o">Entrou:</h2>
                    </header>
                    <div class="app_widget_content">
                        <?php if (!empty($incomes)): ?>
                            <?php foreach ($incomes as $income): ?>
                                <?= $v->insert("views/balance", [
                                    "id" => $income->id,
                                    "month" => $income->date_moviment,
                                    "status" => "positive",
                                    'description' => $income->description,
                                    'value' => $income->value
                                ]); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <p title="Receitas"
                           class="app_widget_more app_invoice_link transition icon-plus"><a
                                    href="<?= url('app/fluxos-de-caixa') ?>">Mais lançamentos</a></p>
                    </div>
                </article>

                <article class="app_widget app_widget_balance">
                    <header class="app_widget_title">
                        <h2 class="icon-calendar-check-o">Saíu:</h2>
                    </header>
                    <div class="app_widget_content">
                        <?php if (!empty($expenses)): ?>
                            <?php foreach ($expenses as $expense): ?>
                                <?= $v->insert("views/balance",
                                    [
                                        "id" => $expense->id,
                                        "month" => $expense->date_moviment,
                                        "status" => "negative",
                                        'description' => $expense->description,
                                        'value' => $expense->value
                                    ]); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <p title="Despesas"
                           class="app_widget_more app_invoice_link transition icon-plus"><a
                                    href="<?= url('app/fluxos-de-caixa') ?>">Mais lançamentos</a></p>
                    </div>
                </article>
            </div>
        </section>

        <section class="app_main_right">
            <!-- <ul class="app_widget_shortcuts">
                <li class="income radius transition" data-modalopen=".app_modal_income">
                    <p class="icon-plus-circle">Receita</p>
                </li>
                <li class="expense radius transition" data-modalopen=".app_modal_expense">
                    <p class="icon-plus-circle">Despesa</p>
                </li>
            </ul> -->

            <article class="app_flex gradient-green">
                <header class="app_flex_title">
                    <h2 class="icon-briefcase">Faturamento (<small>mês</small>)</h2>
                </header>

                <p class="app_flex_amount"><?= money_fmt_br(isnt_empty($totalMonth, 'self', '0.00'), true); ?></p>
                <p class="app_flex_balance">
                    <span class="income">Receitas: <?= money_fmt_br(isnt_empty($bothValues->total_incomes, 'self',
                            '0.00'), true); ?></span>
                    <span class="expense">Despesas: <?= money_fmt_br(isnt_empty($bothValues->total_expenses, 'self',
                            '0.00'), true); ?></span>
                </p>
            </article>

            <section class="app_widget app_widget_blog">
                <header class="app_widget_title">
                    <h2 class="icon-graduation-cap">Configurações:</h2>
                </header>
                <div class="app_widget_content">
                    <div class="margin-height-20">
                        <a href="<?= url('configuracoes/horario'); ?>" title="config"
                           class="btn padding_btn app_launch_btn color_888 font_110_percent radius transition"
                           style="text-decoration: none;">Fechamento de horário.</a>
                    </div>
                    <div class="margin-height-20">
                        <a href="<?= url('configuracoes/horario'); ?>" title="config"
                           class="btn padding_btn app_launch_btn color_888 radius transition"
                           style="text-decoration: none;">Calcular listas de lojas inadimplentes.</a>
                    </div>
                    <a target="_blank" href="#" title="Blog" class="app_widget_more transition ">
                        ...</a>
                </div>
            </section>
        </section>
    </div>

<?php $v->start("scripts"); ?>
    <script type="text/javascript">
        $(function () {
            Highcharts.setOptions({
                lang: {
                    decimalPoint: ',',
                    thousandsSep: '.'
                }
            });

            const chart = Highcharts.chart('control', {
                chart: {
                    type: 'areaspline',
                    spacingBottom: 0,
                    spacingTop: 5,
                    spacingLeft: 0,
                    spacingRight: 0,
                    height: (9 / 16 * 100) + '%'
                },
                title: null,
                xAxis: {
                    categories: [<?= $chart->date_moviment;?>],
                    minTickInterval: 1
                },
                yAxis: {
                    allowDecimals: true,
                    title: null,
                },
                tooltip: {
                    shared: true,
                    valueDecimals: 2,
                    valuePrefix: 'R$ '
                },
                credits: {
                    enabled: false
                },
                plotOptions: {
                    areaspline: {
                        fillOpacity: 0.5
                    }
                },
                series: [{
                    name: 'entrada',
                    data: [<?= $chart->income;?>],
                    color: '#61DDBC',
                    lineColor: '#36BA9B'
                }, {
                    name: 'saida',
                    data: [<?= $chart->expense;?>],
                    color: '#F76C82',
                    lineColor: '#D94352'
                }]
            });

            function test() {
                $.post('<?= url("/app/ajax_grap");?>', function (callback) {
                    if (callback.chart) {
                        chart.update({
                            xAxis: {
                                categories: callback.chart.date_moviment
                            },
                            series: [{
                                data: callback.chart.income
                            }, {
                                data: callback.chart.expense
                            }]
                        });
                    }
                }, "json");

            }

            setInterval(function () {
                test();
            }, 2000);
        });

    </script>
<?php $v->end(); ?>