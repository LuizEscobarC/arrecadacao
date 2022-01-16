<?php $v->layout("_theme"); ?>

    <section class="about_page">
        <div class="about_page_content content">
            <header class="about_header">
                <h1>É simples, fácil e gratuito!</h1>
                <p>de Lorem Ipsum, e mais recentemente quando passou a ser integrado a softwares de editoração eletrônica como Aldus P
                    ageMaker.</p>
            </header>

            <!--FEATURES-->
            <div class="about_page_steps">
                <article class="radius">
                    <header>
                        <span class="icon icon-check-square-o icon-notext"></span>
                        <h3>Cadastre-se para começar</h3>
                        <p>Lorem Ipsum é simplesmente uma simulação de texto da indústria tipográfica e de impressos, e vem sendo utilizado d
                            esde</p>
                    </header>
                </article>

                <article class="radius">
                    <header>
                        <span class="icon icon-leanpub icon-notext"></span>
                        <h3>Lance suas contas</h3>
                        <p>Lorem Ipsum é simplesmente uma simulação de texto da indústria tipográfica e de impressos, e ve</p>
                    </header>
                </article>

                <article class="radius">
                    <header>
                        <span class="icon icon-coffee icon-notext"></span>
                        <h3>Obtenha o controle</h3>
                        <p>m sendo utilizado desde o século XVI, quando um impressor desconhecido pegou uma bandeja
                            de tipos e os embaralhou para
                        </p>
                    </header>
                </article>
            </div>
        </div>

        <div class="about_page_media">
            <div class="about_media_video">
                <div class="embed">
                    <iframe width="560" height="315"
                            src="https://www.youtube.com/embed/<?= $video; ?>?rel=0&amp;showinfo=0" frameborder="0"
                            allow="autoplay; encrypted-media" allowfullscreen></iframe>
                </div>
            </div>
        </div>

        <aside class="about_page_cta">
            <div class="about_page_cta_content container content">
                <h2>Ainda não está usando o CaféControl?</h2>
                <p>m sendo utilizado desde o século XVI, quando um impressor desconhecido peg...</p>
                <a href="<?= url("/cadastrar"); ?>" title="Cadastre-se"
                   class="about_page_cta_btn transition radius icon-check-square-o">Botão</a>
            </div>
        </aside>
    </section>

<?php if (!empty($faq)): ?>
    <section class="faq">
        <div class="faq_content content container">
            <header class="faq_header">
                <img class="title_image" title="Perguntas frequentes" alt="Perguntas frequentes"
                     src="<?= theme("/assets/images/faq-title.jpg"); ?>"/>
                <h3>Perguntas frequentes:</h3>
                <p>Confira as principais dúvidas e repostas sobre o site.</p>
            </header>
            <div class="faq_asks">
                <?php foreach ($faq as $question): ?>
                    <article class="faq_ask j_collapse">
                        <h4 class="j_collapse_icon icon-plus"><?= $question->question; ?></h4>
                        <div class="faq_ask_coll j_collapse_box"><?= $question->response; ?></div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>