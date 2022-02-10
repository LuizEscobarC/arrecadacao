const $body = $("body");
const $inputHour = $('input#hour');
$(function () {
    var effecttime = 200;

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
    $ajaxGetHour = $body.on('change', 'input#hour', function () {
        if ($('select#callback')) {
            $('select#callback').html('');
        }
        if ($('#label')) {
            $('#label').html('');
        }
        $.ajax({
            url: $inputHour.attr('rel'),
            type: 'POST',
            data: $(this).serialize(),
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
    });

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
    $("select#select_page").select2({
        width: '100%',
        placeholder: 'Escolha uma loja',

    });

    $("select#select_page2").select2({
        width: '100%',
        placeholder: 'Escolha uma loja',
    });

    $("select#select_page_hour").select2({
        width: '150',
        placeholder: 'Escolha um horário',
    });

    $("select#select_page_store").select2({
        width: '150',
        placeholder: 'Escolha uma loja',
    });

    $("select#select_page_center").select2({
        width: '100%',
        placeholder: 'Escolha uma loja',
    });

    // BEGIN COMO DEFAULT ELE SETA OS INPUTS DE DATA DOS CADASTROS COM A DATA ATUAL
    $body.on('click', '#date_now', function() {
        const data = new Date();
        let dia = String(data.getDate()).padStart(2, '0');
        let mes = String(data.getMonth() + 1).padStart(2, '0');
        let ano = data.getFullYear();
        dataAtual = ano + '-' + mes + '-' + dia;
        $('#hour').attr('value', dataAtual);
        setTimeout(
            function () {
                $.ajax({
                    url: $inputHour.attr('rel'),
                    type: 'POST',
                    data: $inputHour.serialize(),
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
            }, 1000);
    })
    // END DEFAULT HOUR



});