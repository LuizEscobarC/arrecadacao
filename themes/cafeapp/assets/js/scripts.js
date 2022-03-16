const $body = $("body");
$(function () {
    var effecttime = 200;

    // BEGIN FUNCTIONS

    /## GET HOUR DRY FUNCTION ##/

    function getHours(inputSelect) {
        $.ajax({
            url: inputSelect.attr('rel'),
            type: 'POST',
            data: inputSelect.serialize(),
            dataType: 'JSON',
            success: function (callback) {
                $('p#label').html(callback[0]);
                callback.shift();
                $('select#callback').append('<option value="">Escolha</option>');
                for (let i = 0, len = callback.length; i < len; ++i) {
                    $('select#callback').append('<option value="' + callback[i].id + '">' + callback[i].description + '</option>');
                }
            }
        });
    }

    function getList(inputSelect, idHour, idStore) {
        $.ajax({
            url: inputSelect.attr('rel'),
            type: 'POST',
            data: '&id_hour=' + idHour + '&id_store=' + idStore,
            dataType: 'JSON',
            success: function (callback) {
                let totalValue = null;
                let comissionValue = null;
                let netValue = null;

                if (callback) {
                    totalValue = parseFloat(callback.total_value).toLocaleString('pt-br', {minimumFractionDigits: 2});
                    comissionValue = parseFloat(callback.comission_value).toLocaleString('pt-br', {minimumFractionDigits: 2});
                    netValue = parseFloat(callback.net_value).toLocaleString('pt-br', {minimumFractionDigits: 2});
                    $('input[name=id_list]').val(callback.id);
                } else {
                    const message = `<div class="message info icon-info">Não existe uma lista para a loja neste horário.</div>`;
                    $('.ajax_response').html(message).fadeIn(300);
                    totalValue = 0;
                    comissionValue = 0;
                    netValue = 0;
                    $('input[name=id_list]').val('');
                }

                $('.total_value').html(totalValue);
                $('input[name=total_value]').val(totalValue);
                $('.comission_value').html(comissionValue);
                $('input[name=comission_value]').val(comissionValue);
                $('.net_value').html(netValue);
                $('input[name=net_value]').val(netValue);
            }
        });
    }

    function getStoreValueNow(inputSelect) {
        $.ajax({
            url: inputSelect.data().url,
            type: 'POST',
            data: inputSelect.serialize(),
            dataType: 'JSON',
            success: function (callback) {
                $('.last_value').html(parseFloat(callback.valor_saldo).toLocaleString('pt-br', {minimumFractionDigits: 2}));
            }
        });
    }

    /## REMOVE ENTITY DRY FUNCTION ##/

    function remove($this, dataAttr, confirmText) {
        var remove = confirm(confirmText);

        if (remove === true) {
            $.post($this.data(dataAttr), function (response) {
                //redirect
                if (response.redirect) {
                    window.location.href = response.redirect;
                }
            }, "json");
        }
    }

    // END FUNCTIONS


    /*
     * MOBILE MENU
     */
    $("[data-mobilemenu]").click(function (e) {
        var clicked = $(this);
        var action = clicked.data("mobilemenu");

        if (action === 'open') {
            $(".app_sidebar").slideDown(effecttime);
        }

        if (action === 'close') {
            $(".app_sidebar").slideUp(effecttime);
        }
    });

    $body.on('click', "#sidebar", function (e) {
        var clicked = $(this);
        const $appDrop = $(".app_drop");

        if (clicked.hasClass('open')) {
            $appDrop.slideDown(effecttime);
            clicked.removeClass('open');
        } else {
            $appDrop.slideUp(effecttime);
            clicked.addClass('open');
        }
    });

    $body.on('click', "#sidebar_children", function (e) {
        var clicked = $(this);
        const $appDrop = $(".app_drop_children");

        if (clicked.hasClass('open')) {
            $appDrop.slideDown(effecttime);
            clicked.removeClass('open');
        } else {
            $appDrop.slideUp(effecttime);
            clicked.addClass('open');
        }
    });

    $body.on('click', "#sidebar_children2", function (e) {
        var clicked = $(this);
        const $appDrop = $(".app_drop_children2");

        if (clicked.hasClass('open')) {
            $appDrop.slideDown(effecttime);
            clicked.removeClass('open');
        } else {
            $appDrop.slideUp(effecttime);
            clicked.addClass('open');
        }
    });

    $body.on('click', "#sidebar2", function (e) {
        var clicked = $(this);
        const $appDrop = $(".app_drop1");

        if (clicked.hasClass('open')) {
            $appDrop.slideDown(effecttime);
            clicked.removeClass('open');
        } else {
            $appDrop.slideUp(effecttime);
            clicked.addClass('open');
        }
    });

    $body.on('click', "#sidebar2_children", function (e) {
        var clicked = $(this);
        const $appDrop = $(".app_drop1_children");

        if (clicked.hasClass('open')) {
            $appDrop.slideDown(effecttime);
            clicked.removeClass('open');
        } else {
            $appDrop.slideUp(effecttime);
            clicked.addClass('open');
        }
    });

    $body.on('click', "#sidebar3", function (e) {
        var clicked = $(this);
        const $appDrop = $(".app_drop2");

        if (clicked.hasClass('open')) {
            $appDrop.slideDown(effecttime);
            clicked.removeClass('open');
        } else {
            $appDrop.slideUp(effecttime);
            clicked.addClass('open');
        }
    });

    $body.on('click', "#sidebar3_children", function (e) {
        var clicked = $(this);
        const $appDrop = $(".app_drop2_children");

        if (clicked.hasClass('open')) {
            $appDrop.slideDown(effecttime);
            clicked.removeClass('open');
        } else {
            $appDrop.slideUp(effecttime);
            clicked.addClass('open');
        }
    });

    /*
     * APP MODAL
     */
    $("[data-modalopen]").click(function (e) {
        var clicked = $(this);
        var modal = clicked.data("modalopen");
        $(".app_modal").fadeIn(effecttime).css("display", "flex");
        $(modal).fadeIn(effecttime);
    });
    $("[data-modalclose]").click(function (e) {
        if (e.target === this) {
            $(this).fadeOut(effecttime);
            $(this).children().fadeOut(effecttime);
        }
    });

    /*
     * FROM CHECKBOX
     */
    $("[data-checkbox]").click(function (e) {
        var checkbox = $(this);
        checkbox.parent().find("label").removeClass("check");
        if (checkbox.find("input").is(':checked')) {
            checkbox.addClass("check");
        } else {
            checkbox.removeClass("check");
        }
    });

    /*
     * FADE
     */
    $("[data-fadeout]").click(function (e) {
        var clicked = $(this);
        var fadeout = clicked.data("fadeout");
        $(fadeout).fadeOut(effecttime, function (e) {
            if (clicked.data("fadein")) {
                $(clicked.data("fadein")).fadeIn(effecttime);
            }
        });
    });

    $("[data-fadein]").click(function (e) {
        var clicked = $(this);
        var fadein = clicked.data("fadein");
        $(fadein).fadeIn(effecttime, function (e) {
            if (clicked.data("fadeout")) {
                $(clicked.data("fadeout")).fadeOut(effecttime);
            }
        });
    });

    /*
     * SLIDE
     */
    $("[data-slidedown]").click(function (e) {
        var clicked = $(this);
        var slidedown = clicked.data("slidedown");
        $(slidedown).slideDown(effecttime);
    });

    $("[data-slideup]").click(function (e) {
        var clicked = $(this);
        var slideup = clicked.data("slideup");
        $(slideup).slideUp(effecttime);
    });

    /*
     * TOOGLE CLASS
     */
    $("[data-toggleclass]").click(function (e) {
        var clicked = $(this);
        var toggle = clicked.data("toggleclass");
        clicked.toggleClass(toggle);
    });

    /*
     * jQuery MASK
     */
    $(".mask-money").mask('000.000.000.000.000,00', {reverse: true, placeholder: "0,00"});
    $(".mask-date").mask('00/00/0000', {reverse: true});
    $(".mask-month").mask('00/0000', {reverse: true});
    $(".mask-doc").mask('000.000.000-00', {reverse: true});
    $(".mask-day").mask('00', {reverse: true});

    // BEGIN REMOVE ENTITIES

    /*
     *  APP HOUR REMOVE
     */
    $("[data-hourremove]").click(function () {
        remove($(this), "hourremove", "ATENÇÃO: Essa ação não pode ser desfeita! Tem certeza que deseja excluir esse horário?");
    })

    /*
     *  APP USER REMOVE
     */
    $("[data-userremove]").click(function () {
        remove($(this), "userremove", "ATENÇÃO: Essa ação não pode ser desfeita! Tem certeza que deseja excluir esse usuário?");
    });

    /*
     *  APP CENTER REMOVE
     */
    $("[data-centerremove]").click(function () {
        remove($(this), "centerremove", "ATENÇÃO: Essa ação não pode ser desfeita! Tem certeza que deseja excluir esse centro de custo?");
    });

    /*
     *  APP STORE REMOVE
     */
    $("[data-storeremove]").click(function () {
        remove($(this), "storeremove", "ATENÇÃO: Essa ação não pode ser desfeita! Tem certeza que deseja excluir essa loja?");
    });

    /*
     *  APP LIST REMOVE
     */
    $("[data-listremove]").click(function () {
        remove($(this), "listremove", "ATENÇÃO: Essa ação não pode ser desfeita! Tem certeza que deseja excluir essa lista?");
    });

    /*
     *  APP CASH-FLOW REMOVE
     */
    $("[data-cashremove]").click(function () {
        remove($(this), "cashremove", "ATENÇÃO: Essa ação não pode ser desfeita! Tem certeza que deseja excluir esse lançamento?");
    });

    // END REMOVE ENTITIES

    /*
    * AJAX GET HOUR
    */
    $body.on('change', 'input.hour', function () {
        if ($('select#callback')) {
            $('select#callback').html('');
        }
        if ($('#label')) {
            $('#label').html('');
        }
        getHours($(this));
    });

    /*
    * AJAX GET LIST
    */

    $body.on('change', 'select.store_select', function () {
        getList($(this), $('#callback').val(), $('.store_select').val());
        getStoreValueNow($(this));
    });

    /*
    * VALOR RECOLHIDO CALCULO
    */

    // BEGIN MOVIMENTS CALCS


    function calc(value, $this) {
        let input = $('input[name=' + value + ']');
        //Case o input esteja vazio, para não retornar NaN
        if (input.val()) {
            // VALOR DESPESAS
            const expense = parseFloat($($this).val().replaceAll('.', '').replace(',', '.'));
            // VALOR DINHEIRO
            const paying = parseFloat(input.val().replaceAll('.', '').replace(',', '.'));
            /* Valor reclohido + despesas*/

            // VALOR RECOLHIDO
            const getValue = (paying + expense);
            // VALOR RECOLHIDO EM BRL
            const getValueBr = getValue.toLocaleString('pt-br', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            /* Valor a acertar é o valor líquido da lista*/
            // VALOR LÍQUIDO | VALOR ACERTAR
            const netValue = $('p.net_value').text();
            /* Ao final o saldo anterior e o saldo atual que é a mesma coisa, recebe o novo saldo do calculo*/
            // VALOR ANTERIOR | SALDO ATUAL
            const last_val = $('p.last_value');


            $('input[name=last_value]').val(last_val.text().toLocaleString('pt-br', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));
            $('input[name=net_value]').val(netValue);
            $('input[name=get_value]').val(getValueBr);
            $('.get_value').html(getValueBr);
            if (last_val.text() && netValue) {
                // É o valor que tem que ser abatido  com o valor recolhido + o valor de despesas
                // VALOR A ACERTAR | VALOR LIQUIDO
                const beatValue = (getValue - parseFloat(netValue.replaceAll('.', '').replace(',', '.')));

                // NOVO VALOR ATUAL | SALDO ANTERIOR
                const newValue = (parseFloat(last_val.text().replaceAll('.', '').replace(',', '.')) + beatValue)
                    .toLocaleString('pt-br', {minimumFractionDigits: 2, maximumFractionDigits: 2});

                const beatValueBrl = beatValue.toLocaleString('pt-br', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                $('p.beat_value').html(beatValueBrl);
                $('.new_value').html(newValue);
                $('input[name=beat_value]').val(beatValueBrl);
                $('input[name=new_value]').val(newValue);

            } else {
                alert('Nome da Loja e Horário são necessários!');
            }
        }
    }

    $body.on('keyup', 'input[name=expend]', function () {
        calc('paying_now', this);
    });

    $body.on('keyup', 'input[name=paying_now]', function () {
        calc('paying_now', 'input[name=expend]');
    });

    /* Não envia o formulário informando se quer ou não adicionar um prémio, após a escolha clicando novamente o
    formulário é enviado*/
    $body.on('click', 'button#moviment_btn', function () {
        event.preventDefault();
        let inputPrize = $('input[name=prize]').val();
        if (inputPrize) {
            inputPrize = parseFloat(inputPrize.replaceAll('.', '').replace(',', '.'));
            // VALOR DESPESAS
            const expense = parseFloat($('input[name=expend]').val().replaceAll('.', '').replace(',', '.'));
            // VALOR DINHEIRO
            const paying = parseFloat($('input[name=paying_now]').val().replaceAll('.', '').replace(',', '.'));
            /* Valor reclohido + despesas*/
            // VALOR RECOLHIDO
            const getValue = (paying + expense);
            /* Valor a acertar é o valor líquido da lista*/
            // VALOR LÍQUIDO | VALOR ACERTAR
            const netValue = $('p.net_value').text();
            /* Ao final o saldo anterior e o saldo atual que é a mesma coisa, recebe o novo saldo do calculo*/
            // VALOR ANTERIOR | SALDO ATUAL
            const last_val = $('p.last_value');
            // VALOR A ACERTAR | VALOR LIQUIDO
            const beatValue = (getValue - parseFloat(netValue.replaceAll('.', '').replace(',', '.')));
            // NOVO VALOR ATUAL | SALDO ANTERIOR
            const storeNewValue = (parseFloat(last_val.text().replaceAll('.', '').replace(',', '.')) + beatValue);

            if (storeNewValue < 0) {
                const negativeValue = window.confirm('Deseja abater o saldo da loja?');
                if (negativeValue) {
                    const storeValuePositive = Math.abs(storeNewValue);
                    let prizeOffice = null;
                    let prizeStore = null;
                    let beatPrize = null;
                    let newStoreValue = null;

                    if (storeValuePositive < inputPrize) {
                        prizeOffice = inputPrize - storeValuePositive;
                        prizeStore = storeValuePositive;
                        beatPrize = storeValuePositive;
                        newStoreValue = 0;
                    } else if (storeValuePositive > inputPrize) {
                        prizeOffice = 0;
                        prizeStore = inputPrize;
                        beatPrize = inputPrize;
                        newStoreValue = -Math.abs((storeValuePositive - inputPrize));
                    } else {
                        prizeOffice = 0;
                        prizeStore = inputPrize;
                        beatPrize = inputPrize;
                        newStoreValue = 0;
                    }
                    const newStoreValueBrl = newStoreValue.toLocaleString('pt-br', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                    const beatPrizeBrl = beatPrize.toLocaleString('pt-br', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                    const prizeOfficeBrl = prizeOffice.toLocaleString('pt-br', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                    const prizeStoreBrl = prizeStore.toLocaleString('pt-br', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                    $('.new_value').text(newStoreValueBrl);
                    $('input[name=new_value]').text(newStoreValueBrl);

                    $('label.prize_output').html(
                        `<span class="field icon-leanpub">Valor de Abate Premio:</span>
                            <p class="app_widget_title beat_prize">${beatPrize}</p>
                            <input type="hidden" name="beat_prize" value="${beatPrizeBrl}">
                            <input type="hidden" name="prize_office" value="${prizeOfficeBrl}">
                            <input type="hidden" name="prize_store" value="${prizeStoreBrl}">`);
                    alert(`A loja pagará: R$${prizeStoreBrl}.
                     O escritório pagará: R$${prizeOfficeBrl}.`);
                }
            } else {
                inputPrize = inputPrize.toLocaleString('pt-br', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                $('label.prize_output').html(
                    `<span class="field icon-leanpub">Sem abate</span>
                            <input type="hidden" name="prize_office" value="${inputPrize}">
                            <input type="hidden" name="prize_store" value="0">`);
            }
            setTimeout(function (){},3000);
        } else {
            alert('Por favor, digite o valor do premio!');
        }

        $('form.app_form.moviment').submit();

    });
    // END MOVIMENT CALCS


    /** $('select#callback').change(function () {
        let url = $(this).attr('rel') + '/' + $(this).find(':selected').attr('value');

        if ($('#label')) {
            $('#label').html('');
        }

        $.getJSON(url, function (callback) {
            $('p#label').html(callback.week_day);
        });
    }); */


    /* Select with search*/
    $("select.select2Input").select2({
        width: '100%'
    });

    // BEGIN COMO DEFAULT ELE SETA OS INPUTS DE DATA DOS CADASTROS COM A DATA ATUAL
    if (window.location.toString() === 'http://www.ihsistemas.com/app/cadastrar-lista' || window.location.toString() === 'http://www.ihsistemas.com/app/cadastrar-fluxo-de-caixa' || window.location.toString() === 'http://www.localhost/arrecadacao/app/cadastrar-movimentacao') {
        const data = new Date();
        const dia = String(data.getDate()).padStart(2, '0');
        const mes = String(data.getMonth() + 1).padStart(2, '0');
        const ano = data.getFullYear();
        dataAtual = ano + '-' + mes + '-' + dia;
        const hourInput = $('input.hour');
        hourInput.attr('value', dataAtual);
        getHours(hourInput)
    }
    // END DEFAULT HOUR


});