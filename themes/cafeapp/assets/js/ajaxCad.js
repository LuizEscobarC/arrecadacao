$(function () {
    //ajax form
    $("form:not('.ajax_off')").submit(function (e) {
        e.preventDefault();
        const form = $(this);
        const load = $(".ajax_load");
        const flashClass = "ajax_response";
        const flash = $("." + flashClass);

        form.ajaxSubmit({
            url: form.attr("action"),
            type: "POST",
            dataType: "json",
            beforeSend: function () {
                load.fadeIn(200).css("display", "flex");
            },
            success: function (response) {
                if (response.scroll) {
                    $(window).scrollTop(response.scroll);
                }

                //redirect
                if (response.redirect) {
                    setTimeout(function (){
                        window.location.href = response.redirect;
                    }, (response.timeout ?? 300));
                } else {
                    load.fadeOut(200);
                }

                //reload
                if (response.reload) {
                        window.location.reload();
                } else {
                    load.fadeOut(200);
                }

                //message
                if (response.message) {
                    if (flash.length) {
                        flash.html(response.message).fadeIn(100).effect("bounce", 300);
                    } else {
                        form.prepend("<div class='" + flashClass + "'>" + response.message + "</div>")
                            .find("." + flashClass).effect("bounce", 300);
                    }
                } else {
                    flash.fadeOut(100);
                }
            },
            complete: function () {

                if (form.data("reset") === true) {
                    form.trigger("reset");
                }
            }
        });
    })
});