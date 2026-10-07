<?php
/**
 * @var \CodeIgniter\Pager\PagerRenderer $pager
 */
$pager->setSurroundCount(2);
?>
<nav aria-label="Page navigation">
    <ul class="pagination pagination-sm mb-0 shadow-sm" style="border-radius: 8px; overflow: hidden;">
        <?php if ($pager->hasPrevious()) : ?>
            <li class="page-item">
                <a class="page-link px-3" href="<?= $pager->getPrevious() ?>" aria-label="<?= lang('Pager.previous') ?>" style="border-color: #eee8f7; color: var(--primary-purple); font-weight: 500;">
                    <i class="fas fa-chevron-left me-1" style="font-size: 0.75rem;"></i> Prev
                </a>
            </li>
        <?php else : ?>
            <li class="page-item disabled">
                <span class="page-link px-3" style="border-color: #eee8f7; color: #a1a5b7; background-color: #f8f9fa;">
                    <i class="fas fa-chevron-left me-1" style="font-size: 0.75rem;"></i> Prev
                </span>
            </li>
        <?php endif ?>

        <?php foreach ($pager->links() as $link) : ?>
            <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                <a class="page-link" href="<?= $link['uri'] ?>" style="
                    border-color: #eee8f7; 
                    <?= $link['active'] ? 'background-color: var(--primary-purple); border-color: var(--primary-purple); color: white;' : 'color: #4b5675;' ?> 
                    font-weight: 600; 
                    min-width: 38px; 
                    text-align: center;
                ">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNext()) : ?>
            <li class="page-item">
                <a class="page-link px-3" href="<?= $pager->getNext() ?>" aria-label="<?= lang('Pager.next') ?>" style="border-color: #eee8f7; color: var(--primary-purple); font-weight: 500;">
                    Next <i class="fas fa-chevron-right ms-1" style="font-size: 0.75rem;"></i>
                </a>
            </li>
        <?php else : ?>
            <li class="page-item disabled">
                <span class="page-link px-3" style="border-color: #eee8f7; color: #a1a5b7; background-color: #f8f9fa;">
                    Next <i class="fas fa-chevron-right ms-1" style="font-size: 0.75rem;"></i>
                </span>
            </li>
        <?php endif ?>
    </ul>
</nav>

