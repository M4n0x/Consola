
<ul class="pagination">
    <?php if ($pages->backPage()): ?>
        <li>
            <a href="<?= $pages->backPage(); ?>">Précédent</a>
        </li>
    <?php endif; ?>

    <?php foreach ($pages->prevPages() as $page): ?>
        <li>
            <a href="<?= $page['url'] ?>"><?= $page['num'] ?></a>
        </li>
    <?php endforeach; ?>
        
    <li class="active">
        <span><?= $pages->getCurrentPage(); ?></span>
    </li>

    <?php foreach (($pages->afterPages()) as $page): ?>
        <li>
            <a href="<?= $page['url'] ?>"><?= $page['num'] ?></a>
        </li>
    <?php endforeach; ?>

    <?php if ($pages->nextPage()): ?>
        <li>
            <a href="<?= $pages->nextPage(); ?>">Suivant</a>
        </li>
    <?php endif; ?>
</ul>