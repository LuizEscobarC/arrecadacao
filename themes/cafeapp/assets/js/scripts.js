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
                $('select#callback').append('<option value="0">Escolha</option>');
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
                if (callback === null) {
                    window.location.reload();
                }
                let totalValue = parseFloat(callback.total_value).toLocaleString('pt-br', {minimumFractionDigits: 2});
                let comissionValue = parseFloat(callback.comission_value).toLocaleString('pt-br', {minimumFractionDigits: 2});
                let netValue = parseFloat(callback.net_value).toLocaleString('pt-br', {minimumFractionDigits: 2});

                $('input[name=id_list]').val(callback.id);
                $('.total_value').html(totalValue)
                $('input[name=total_value]').val(totalValue);
                $('.comission_value').html(`${callback.comission_value}%`);
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

            $('input[name=last_value]').val(parseFloat(last_val.text()).toLocaleString('pt-br', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            $('input[name=expense]').val(expense);
            $('input[name=get_value]').val(getValueBr);
            $('.get_value').html(getValueBr);
            if (last_val.text() && netValue) {
                // É o valor que tem que ser abatido  com o valor recolhido + o valor de despesas
                // VALOR A ACERTAR | VALOR LIQUIDO
                const beatValue = (getValue - parseFloat(netValue));
                const beatValueBrl = beatValue.toLocaleString('pt-br', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                // NOVO VALOR ATUAL | SALDO ANTERIOR
                const newValue = (parseFloat(last_val.text().replaceAll('.', '').replace(',', '.')) + beatValue)
                    .toLocaleString('pt-br', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                if (beatValue && newValue) {
                    $('p.beat_value').html(beatValueBrl);
                    $('.new_value').html(newValue);
                    $('input[name=beat_value]').val(beatValueBrl);
                    $('input[name=new_value]').val(newValue);
                }
            } else {
                alert('Por favor escolha a loja e preencha os campos (valor dinheiro e valor despesas)!');
            }
        }
    }

    $body.on('keyup', 'input[name=expend]', function () {
        calc('paying_now', this);
    });

    $body.on('keyup', 'input[name=paying_now]', function () {
        calc('paying_now', 'input[name=expend]');
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