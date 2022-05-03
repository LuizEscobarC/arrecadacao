//ANIMATED HOME WITH TOGGLE GRADIENT
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

