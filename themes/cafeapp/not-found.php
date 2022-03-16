<?php $v->layout("_theme"); ?>

<article class="not_found">
    <div class="container content">
        <header class="not_found_header">
            <p class="error">&bull;<?= (empty($error->code) ? '404': ''); ?>&bull;</p>
            <h1><?= (empty($error->title) ? $error->title: 'Oppps! Houve um Problema.') ?></h1>
            <p><?= $error->entity . 'foi apagada ou não existe.'; ?></p>

            <?php if ($error->link): ?>
                <a class="not_found_btn gradient gradient-green gradient-hover transition radius"
                   title="<?= $error->linkTitle; ?>" href="<?= $error->link; ?>"><?= $error->linkTitle; ?></a>
            <?php endif; ?>
        </header>
    </div>
</article>