<div class="app_modal" data-modalclose="true">
    <!--INCOME-->
    <div class="app_modal_box app_modal_income">
        <p class="title icon-calendar-check-o">Nova Receita:</p>
        <form class="app_form" action="" method="post">
            <input type="hidden" value="BRL" name="currency"/>
            <input type="hidden" value="type" name="income"/>

            <label>
                <span class="field icon-leanpub">Descrição:</span>
                <input class="radius" type="text" name="description" placeholder="Ex: Aluguel" required/>
            </label>

            <div class="label_group">
                <label>
                    <span class="field icon-money">Valor:</span>
                    <input class="radius mask-money" type="text" name="description" required/>
                </label>

                <label>
                    <span class="field icon-filter">Data:</span>
                    <input class="radius masc-date" type="date" name="due_at" required/>
                </label>
            </div>

            <div class="label_group">
                <label>
                    <span class="field icon-briefcase">Carteira:</span>
                    <select name="wallet">
                        <option value="1">&ofcir; Casa</option>
                    </select>
                </label>

                <label>
                    <span class="field icon-filter">Categoria:</span>
                    <select name="category">
                        <option value="1">&ofcir; Salário</option>
                    </select>
                </label>
            </div>

            <div class="label_check">
                <p class="field icon-exchange">Repetição:</p>
                <label class="check"
                       data-checkbox="true"
                       data-slideup=".repeate_item_expense, .repeate_item_income">
                    <input type="radio" name="repeat" value="" checked> Única
                </label>

                <label data-checkbox="true"
                       data-slideup=".repeate_item_expense"
                       data-slidedown=".repeate_item_income">
                    <input type="radio" name="repeat" value="income"> Fixa
                </label>

                <label data-checkbox="true"
                       data-slideup=".repeate_item_income"
                       data-slidedown=".repeate_item_expense">
                    <input type="radio" name="repeat" value="expense"> Parcelada
                </label>
            </div>

            <label class="repeate_item repeate_item_income" style="display: none">
                <select name="period">
                    <option value="month">&ofcir; Mensal</option>
                    <option value="year">&ofcir; Anual</option>
                </select>
            </label>

            <label class="repeate_item repeate_item_expense" style="display: none">
                <input class="radius" type="number" min="1" placeholder="1 parcela" name="enrollments"/>
            </label>

            <button class="btn radius transition icon-check-square-o">Lançar Receita</button>
        </form>
    </div>

    <!--EXPENSE-->
    <div class="app_modal_box app_modal_expense">
        <p class="title icon-calendar-minus-o">Nova Despesa:</p>
        <form class="app_form" action="" method="post">
            <input type="hidden" value="BRL" name="currency"/>
            <input type="hidden" value="type" name="expense"/>

            <label>
                <span class="field icon-leanpub">Descrição:</span>
                <input class="radius" type="text" name="description" placeholder="Ex: Aluguel" required/>
            </label>

            <div class="label_group">
                <label>
                    <span class="field icon-money">Valor:</span>
                    <input class="radius mask-money" type="text" name="description" required/>
                </label>

                <label>
                    <span class="field icon-filter">Data:</span>
                    <input class="radius masc-date" type="date" name="due_at" required/>
                </label>
            </div>

            <div class="label_group">
                <label>
                    <span class="field icon-briefcase">Carteira:</span>
                    <select name="wallet">
                        <option value="1">&ofcir; Casa</option>
                    </select>
                </label>

                <label>
                    <span class="field icon-filter">Categoria:</span>
                    <select name="category">
                        <option value="1">&ofcir; Salário</option>
                    </select>
                </label>
            </div>

            <div class="label_check">
                <p class="field icon-exchange">Repetição:</p>
                <label class="check"
                       data-checkbox="true"
                       data-slideup=".repeate_item_expense, .repeate_item_income">
                    <input type="radio" name="repeat" value="" checked> Única
                </label>

                <label data-checkbox="true"
                       data-slideup=".repeate_item_expense"
                       data-slidedown=".repeate_item_income">
                    <input type="radio" name="repeat" value="income"> Fixa
                </label>

                <label data-checkbox="true"
                       data-slideup=".repeate_item_income"
                       data-slidedown=".repeate_item_expense">
                    <input type="radio" name="repeat" value="expense"> Parcelada
                </label>
            </div>

            <label class="repeate_item repeate_item_income" style="display: none">
                <select name="period">
                    <option value="month">&ofcir; Mensal</option>
                    <option value="year">&ofcir; Anual</option>
                </select>
            </label>

            <label class="repeate_item repeate_item_expense" style="display: none">
                <input class="radius" type="number" min="1" placeholder="1 parcela" name="enrollments"/>
            </label>

            <button class="btn radius transition icon-check-square-o">Lançar Despesa</button>
        </form>
    </div>

    <!--SUPPORT-->
    <div class="app_modal_box app_modal_contact">
        <p class="title icon-calendar-minus-o">Fale conosco:</p>
        <form class="app_form" action="" method="post">
            <label>
                <span class="field icon-life-ring">O que precisa?</span>
                <select name="type">
                    <option value="support">&ofcir; Preciso de suporte</option>
                    <option value="suggestion">&ofcir; É uma sugestão</option>
                    <option value="complaint">&ofcir; É uma reclamação</option>
                </select>
            </label>

            <label>
                <span class="field icon-thumb-tack">Assunto:</span>
                <input class="radius" type="text" name="subject" required/>
            </label>

            <label>
                <span class="field icon-comments-o">Mensagem:</span>
                <textarea class="radius" name="body" rows="4" required></textarea>
            </label>

            <button class="btn radius transition icon-paper-plane-o">Enviar Agora</button>
        </form>
    </div>

    <!--USER-->
    <div class="app_modal_box app_modal_user">
        <p class="title icon-calendar-minus-o">Novo usuário:</p>
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

    <!--STORE-->
    <div class="app_modal_box app_modal_store">
        <p class="title icon-calendar-check-o">Nova Loja:</p>
        <form class="app_form" action="<?= url("/app/loja-salvar"); ?>" method="post">
            <div class="ajax_response"><?= flash(); ?></div>

            <label>
                <span class="field icon-leanpub">Código da loja:</span>
                <input class="radius" type="text" name="code"
                       placeholder="Ex: 1234 ou N123" required/>
            </label>

            <label>
                <span class="field icon-leanpub">Nome:</span>
                <input class="radius" type="text" name="nome_loja" placeholder="Ex: Bentão" required/>
            </label>


            <label>
                <span class="field icon-leanpub">Valor:</span>
                <input class="radius mask-money" type="text" name="valor_saldo" required/>
            </label>

            <label>
                <span class="field icon-leanpub">Comissão:</span>
                <input class="radius mask-money" type="text" name="comissao" required/>
            </label>

            <div class="label_group">
                <label>
                    <span class="field icon-money">Valor Aluguel:</span>
                    <input class="radius mask-money" type="text" name="valor_aluguel" required/>
                </label>

                <label>
                    <span class="field icon-filter">Aluguel Dia:</span>
                    <input class="radius" type="text" name="aluguel_dia" required/>
                </label>
            </div>

            <div class="label_group">
                <label>
                    <span class="field icon-money">Valor Gratificação:</span>
                    <input class="radius mask-money" type="text" name="valor_gratificacao" required/>
                </label>

                <label>
                    <span class="field icon-filter">Gratificação Dia:</span>
                    <input class="radius" type="text" name="gratificacao_dia" required/>
                </label>
            </div>

            <button class="btn radius transition icon-check-square-o">Cadastrar Loja</button>
        </form>
    </div>

    <!-- COST -->
    <div class="app_modal_box app_modal_cost">
        <p class="title icon-calendar-check-o">Novo centro de custo:</p>
        <form class="app_form" action="<?= url("/app/centro-salvar"); ?>" method="post">
            <div class="ajax_response"><?= flash(); ?></div>

            <label>
                <span class="field icon-leanpub">Descrição:</span>
                <input class="radius" type="text" name="description" placeholder="Ex: Recibos" required/>
            </label>


            <div class="label_check">
                <p class="field icon-exchange">Emitir Recibos:</p>

                <label data-checkbox="true"
                       data-slideup=".repeate_item_expense"
                       data-slidedown=".repeate_item_income">
                    <input type="radio" name="emit" value="1"> Sim
                </label>

                <label data-checkbox="true"
                       data-slideup=".repeate_item_income"
                       data-slidedown=".repeate_item_expense">
                    <input type="radio" name="emit" value="2"> Não
                </label>
            </div>

            <button class="btn radius transition icon-check-square-o">Cadastrar Centro de Custo</button>
        </form>
    </div>

    <!-- HOUR -->
    <div class="app_modal_box app_modal_hour">
        <p class="title icon-calendar-check-o">Novo horário:</p>
        <form class="app_form" action="<?= url("/app/horario"); ?>" method="post">
            <div class="ajax_response"><?= flash(); ?></div>

            <label>
                <span class="field icon-leanpub">Nome do Horário:</span>
                <input class="radius" type="text" name="description" placeholder="Ex: Primeiro turno" required/>
            </label>


            <div class="select2-container">
                <p class="field check icon-exchange">Escolha o dia:</p>
                <label>
                    <select name="number_day">
                        <option value="0">Domingo</option>
                        <option value="1">Segunda</option>
                        <option value="2">Terça</option>
                        <option value="3">Quarta</option>
                        <option value="4">Quinta</option>
                        <option value="5">Sexta</option>
                        <option value="6">Sábado</option>
                    </select>
                </label>

            </div>

            <div class="al-center">
                <div>
                    <button class="btn btn_inline radius transition icon-pencil-square-o">Cadastrar Horário</button>
                </div>
            </div>
        </form>
    </div>

    <!-- LIST -->
    <div class="app_modal_box app_modal_list">
        <p class="title icon-calendar-check-o">Nova Lista:</p>
        <form class="app_form" action="<?= url("/app/lista"); ?>" method="post">
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
                    <select name="id_hour" id="callback" rel="<?= url("/app/get_week_day") ?>">
                    </select>
                </label>
            </div>


            <label class="">
                <span class="field icon-briefcase">Loja:</span>
                <select name="id_store" class="select2Input">
                    <?php foreach ((new \Source\Models\Store())->find()->fetch(true) as $store): ?>
                        <option value="<?= $store->id; ?>">&ofcir; <?= $store->nome_loja; ?></option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label>
                <span class="field icon-leanpub">Valor Bruto:</span>
                <input class="radius mask-money" type="text" name="total_value" placeholder="Ex: 999"
                       required/>
            </label>


            <div class="al-center">
                <div>
                    <button class="btn btn_inline radius transition icon-pencil-square-o">Atualizar</button>
                </div>
            </div>
        </form>
    </div>

    <!-- FINANCE -->
    <div class="app_modal_box app_modal_cash">
        <p class="title icon-calendar-check-o">Novo lançamento:</p>
        <form class="app_form" action="<?= url("/app/fluxo-de-caixa"); ?>" method="post">
            <div class="ajax_response"><?= flash(); ?></div>

            <div class="label_group">
                <label class="three_label">
                    <span class="field icon-thumb-tack">DATA DE MOVIMENTO:</span>
                    <input class="radius hour" rel="<?= url('/app/get_hour') ?>" type="date"
                           name="date_moviment"
                           required/>
                </label>

                <label class="three_label">
                    <p id="label" class="app_widget_title"></p>
                </label>

                <label class="three_label">
                    <span class="field icon-briefcase"> HORÁRIO:</span>
                    <select name="id_hour" id="callback" rel="<?= url("/app/get_week_day") ?>">
                    </select>
                </label>
            </div>

            <label>
                <span class="field icon-briefcase">Loja:</span>
                <select name="id_store" class="select2Input">
                    <option value="">Escolha</option>
                    <?php foreach ((new \Source\Models\Store())->find()->fetch(true) as $store): ?>
                        <option value="<?= $store->id; ?>">&ofcir; <?= $store->nome_loja; ?></option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label>
                <span class="field icon-briefcase">Centro de custo:<small
                            class="font_80_percent">Opcional</small></span>
                <select name="id_cost" class="select2Input">
                    <option value="">Escolha</option>
                    <?php foreach ((new \Source\Models\Center())->find()->fetch(true) as $center): ?>
                        <option value="<?= $center->id; ?>">&ofcir; <?= $center->description; ?></option>
                    <?php endforeach; ?>
                </select>
            </label>

            <div class="label_group">

                <label class="three_label">
                    <span class="field icon-leanpub">Valor do lançamento:</span>
                    <input class="radius mask-money" type="text" name="value" placeholder="Ex: 999"
                           required/>
                </label>

                <label class="three_label">
                    <span class="field">Entrada:</span>
                    <input type="radio" name="type" value="1">
                    <span class="field">Saída: </span>
                    <input type="radio" name="type" value="2">
                </label>

                <label class="three_label ">
                    <span class="field">Descrição:</span>
                    <textarea class="radius" name="description"></textarea>
                </label>
            </div>


            <div class="al-center">
                <div>
                    <button class="btn btn_inline radius transition icon-pencil-square-o">Lançar</button>
                </div>
            </div>
        </form>
    </div>



