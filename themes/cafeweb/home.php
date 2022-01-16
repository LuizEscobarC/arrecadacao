<?php $v->layout("_theme"); ?>

    <!--FEATURED-->
    <article class="home_featured">
        <div class="home_featured_content container content">
            <header class="home_featured_header">
                <h1>Contas a pagar e receber? Comece a controlar!</h1>
                <p>lto para a editoração eletrônica, per
                manecendo essencialmente inalterado. Se popularizou na década
                de 60, quando a Letraset lançou dec</p>
                <p><span data-go=".home_optin"
                         class="home_featured_btn gradient gradient-green gradient-hover radius transition icon-check-square-o">Criar
                    minha conta e começar a controlar</span></p>
                <p class="features">Rápido | Simples | Gratuito</p>
            </header>
        </div>

        <div class="home_featured_app">
            <img src="<?= theme("/assets/images/home-app.jpg"); ?>" alt="CafeControl" title="CafeControl"/>
        </div>
    </article>

    <!--FEATURES-->
    <div class="home_features">
        <section class="container content">
            <header class="home_features_header">
                <h2>titulo</h2>
                <p>lto para a editoração eletrônica, per
                manecendo essencialmente inalterado. Se popularizou na década
                de 60, quando a Letraset lançou dec</p>
            </header>

            <div class="home_features_content">
                <article class="radius">
                    <header>
                        <img alt="Contas a receber" title="Contas a receber"
                             src="<?= theme("/assets/images/home_receive.jpg"); ?>"/>
                        <h3>Contas a receber</h3>
                        <p>lto para a editoração eletrônica, per
                manecendo essencialmente inalterado. Se popularizou na década
                de 60, quando a Letraset lançou dec</p>
                    </header>
                </article>

                <article class="radius">
                    <header>
                        <img alt="Contas a pagar" title="Contas a pagar"
                             src="<?= theme("/assets/images/home_pay.jpg"); ?>"/>
                        <h3>Contas a pagar</h3>
                        <p>lto para a editoração eletrônica, per
                manecendo essencialmente inalterado. Se popularizou na década
                de 60, quando a Letraset lançou dec</p>
                    </header>
                </article>

                <article class="radius">
                    <header>
                        <img alt="Controle e relatórios" title="Controle e relatórios"
                             src="<?= theme("/assets/images/home_control.jpg"); ?>"/>
                        <h3>Controle e relatórios</h3>
                        <p>lto para a editoração eletrônica, per
                manecendo essencialmente inalterado. Se popularizou na década
                de 60, quando a Letraset lançou dec</p>
                    </header>
                </article>
            </div>
        </section>
    </div>

    <!-- --OPTIN--
    <article class="home_optin">
        <div class="home_optin_content container content">
            <header class="home_optin_content_flex">
                <h2>do utilizado desde o século XVI, quando um impressor desconhecido pegou u</h2>
                <p>lto para a editoração eletrônica, per
                manecendo essencialmente inalterado. Se popularizou na década
                de 60, quando a Letraset lançou dec</p>
                <p>m sendo utilizado desde o século XVI, quando um impressor desconhecido pegou uma bandeja de tipos e o
                s embaralhou para fazer um livro de modelos de tipos. Lorem Ipsum sobreviveu não só a cinco séculos, como
                também ao salto para a editoração eletrônica, per
                manecendo essencialmente inalterado. Se popularizou na década
                de 60, quando a Letraset lançou decalques contendo passagens d</p>
                <p>Pronto para começar a controlar?</p>
            </header>

            <div class="home_optin_content_flex">
                <span class="icon icon-check-square-o icon-notext"></span>
                <h4>Crie sua conta gratuitamente:</h4>
                <form action="< url("/cadastrar"); ?>" method="post" enctype="multipart/form-data">
                    <div class="ajax_response">< flash(); ?></div>
                    < csrf_input(); ?>
                    <input type="text" name="first_name" placeholder="Primeiro nome:"/>
                    <input type="text" name="last_name" placeholder="Último nome:"/>
                    <input type="email" name="email" placeholder="Melhor e-mail:"/>
                    <input type="password" name="password" placeholder="Senha de acesso:"/>
                    <button class="radius transition gradient gradient-green gradient-hover">Criar minha conta</button>
                </form>
            </div>
        </div>
    </article> -->

    <!--VIDEO-->
    <article class="home_video">
        <div class="home_video_content container content">
            <header>
                <h2>Descubra o CafeControl</h2>
                <span data-modal=".home_video_modal" class="icon-play-circle-o icon-notext transition"></span>
            </header>
        </div>

        <div class="home_video_modal j_modal_close">
            <div class="home_video_modal_box">
                <div class="embed">
                    <iframe width="560" height="315"
                            src="https://www.youtube.com/embed/<?= $video; ?>?rel=0&amp;showinfo=0"
                            frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </article>