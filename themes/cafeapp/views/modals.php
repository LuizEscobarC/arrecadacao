<div class="app_modal modal_calc_parent" data-modalclose="true">
    <!--Calc-->
    <div class="app_modal_box app_modal_calc">
        <p class="title icon-calendar-check-o">Somar:</p>
        <form class="app_form ajax_off" action="" method="post">
            <!-- A qui eu faço o input dinamico do calculo -->
            <label>
                <span class="field icon-money">Coloque o valor e click enter:</span>
                <input class="radius input_calc mask-money" type="text" name="calc" autocomplete="off" required/>
            </label>

            <!-- Aqui eu retorno o resultado via JS -->
            <label>
                <span class="field icon-plus">Resultado:</span>
                <p class="app_widget_title current_result"></p>
            </label>

            <button class="btn radius transition icon-check-square-o">Somar</button>
        </form>
    </div>
</div>



