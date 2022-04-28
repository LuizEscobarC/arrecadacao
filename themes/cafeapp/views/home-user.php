<?php $v->layout("_theme"); ?>

<article class="not_found">
    <div class="container content">
        <div class="ajax_response"><?= flash(); ?></div>
        <header class="not_found_header">
            <p class="home_user">&bull;<?= (!empty($error->message) ? $error->message : '404'); ?>&bull;</p>
            <h1><?= 'Bem vindo' . !empty($error->name) ? $error->name : ''; ?></h1>
            <p><?= 'Bora trabalhar.'; ?></p>

            <?php if ($error->link): ?>
                <a class="not_found_btn gradient gradient-green gradient-hover transition radius"
                   title="<?= $error->linkTitle; ?>" href="<?= $error->link; ?>"><?= $error->linkTitle; ?></a>
            <?php endif; ?>
        </header>
    </div>
</article>