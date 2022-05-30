/*ANIMATED HOME WITH TOGGLE GRADIENT, you've acessed your article and then your class, so you've created a function 
within your ''if?'' named as trade color that probably do the color trasition  in a screen and below yo uset its config...
*/


if (document.querySelector('article.app_flex')) {
    setInterval(function () {
        tradeColor()
    }, 1000);

    function tradeColor() {
        const color = document.querySelector('article.app_flex');
        color.classList.add('transition-lower', 'gradient', 'radius');
            color.classList.toggle('gradient-hover-self')
    }
}
//END ANIMATED HOME

