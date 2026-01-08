<h1 class="fs-1 mb-4"><?= $this->echap($category->name) ?></h1>

<nav aria-label="breadcrumb" class="small">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Retour</a></li>
    </ol>
</nav>

<?php if (empty($ads)): ?>
    <div class="alert alert-info" role="alert">
        Il n'y a pas encore d'annonce dans cette catégorie.
    </div>
<?php else: ?>
    <?php foreach ($ads as $ad): ?>
        <div class="row mb-3 pb-3 border-bottom">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <?php if (!empty($ad->first_photo)): ?>
                        <a href="/ad/show?id=<?= $ad->id ?>">
                            <img src="/uploads/<?= $ad->first_photo ?>" alt="<?= $this->echap($ad->title) ?>" class="img-list-ad">
                        </a>
                    <?php else: ?>
                        <a href="/ad/show?id=<?= $ad->id ?>">
                            <img src="/img/no_image.jpg" alt="<?= $this->echap($ad->title) ?>" class="img-list-ad">
                        </a>
                    <?php endif; ?>
                </div>
                <div class="flex-grow-1 ms-3">
                    <a href="/ad/show?id=<?= $ad->id ?>">
                        <?= $this->echap($ad->title) ?>
                    </a>
                    - <?= number_format($ad->price, 2) ?>€
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <?php if ($pages > 1): ?>
        <nav aria-label="Page navigation des catégories">
            <ul class="pagination justify-content-center mt-4">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="/category?id=<?= $categoryId ?>&p=<?= $page - 1 ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $pages; $i++): ?>
                    <?php if ($i == $page): ?>
                        <li class="page-item">
                            <a class="page-link"><?= $i ?></a>
                        </li>
                    <?php else: ?>
                        <li class="page-item">
                            <a class="page-link" href="/category?id=<?= $categoryId ?>&p=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endif; ?>
                <?php endfor; ?>
                <?php if ($page < $pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="/category?id=<?= $categoryId ?>&p=<?= $page + 1 ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php endif; ?>
<?php endif; ?>