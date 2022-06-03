$(function () {
    /*
     * jQuery MASK
     */
    $(".mask-money-negative").mask('N0N0N0N.N0N0N0N.N0N0N0N.N0N0N0N.N0N0N0N,N0N0N', {
        translation: {
            'N': {
                pattern: /[-]/, optional: true
            }
        }, reverse: true, placeholder: '0,00'
    });
    $(".mask-money").mask('000.000.000.000.000,00', {reverse: true, placeholder: "0,00"});
    $(".mask-date").mask('00/00/0000', {reverse: true});
    $(".mask-month").mask('00/0000', {reverse: true});
    $(".mask-doc").mask('000.000.000-00', {reverse: true});
    $(".mask-day").mask('00', {reverse: true});

    /* Select with search*/
    $("select.select2Input").select2({
        width: '100%'
    });
});