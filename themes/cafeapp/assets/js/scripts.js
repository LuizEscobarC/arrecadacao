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

    const $body = $("body");
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

    /*
     *  APP HOUR REMOVE
     */
    $("[data-hourremove]").click(function(e) {
        var remove = confirm("ATENÇÃO: Essa ação não pode ser desfeita! Tem certeza que deseja excluir esse horário?");

        if (remove === true) {
            $.post($(this).data("hourremove"), function (response){
                //redirect
                if (response.redirect) {
                    window.location.href = response.redirect;
                }
            }, "json");
        }
    })

    /*
     *  APP USER REMOVE
     */
    $("[data-userremove]").click(function(e) {
        var remove = confirm("ATENÇÃO: Essa ação não pode ser desfeita! Tem certeza que deseja excluir esse usuário?");

        if (remove === true) {
            $.post($(this).data("userremove"), function (response){
                //redirect
                if (response.redirect) {
                    window.location.href = response.redirect;
                }
            }, "json");
        }
    })

    /*
     *  APP USER REMOVE
     */
    $("[data-centerremove]").click(function(e) {
        var remove = confirm("ATENÇÃO: Essa ação não pode ser desfeita! Tem certeza que deseja excluir esse centro de custo?");

        if (remove === true) {
            $.post($(this).data("centerremove"), function (response){
                //redirect
                if (response.redirect) {
                    window.location.href = response.redirect;
                }
            }, "json");
        }
    })

    /*
     *  APP STORE REMOVE
     */
    $("[data-storeremove]").click(function(e) {
        var remove = confirm("ATENÇÃO: Essa ação não pode ser desfeita! Tem certeza que deseja excluir essa loja?");

        if (remove === true) {
            $.post($(this).data("storeremove"), function (response){
                //redirect
                if (response.redirect) {
                    window.location.href = response.redirect;
                }
            }, "json");
        }
    })

    /*
     *  APP STORE REMOVE
     */
    $("[data-listremove]").click(function(e) {
        var remove = confirm("ATENÇÃO: Essa ação não pode ser desfeita! Tem certeza que deseja excluir essa lista?");

        if (remove === true) {
            $.post($(this).data("listremove"), function (response){
                //redirect
                if (response.redirect) {
                    window.location.href = response.redirect;
                }
            }, "json");
        }
    })

});