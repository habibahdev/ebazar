<h1 class="fs-1">Bienvenue sur e-bazar</h1>

<hr>

<h2 class="fs-2 my-4">Catégories</h2>

<div class="row">
    <?php foreach ($categories as $category): ?>
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <a href="/category?id=<?= $category->id ?>" class="text-decoration-none">
                        <?= $this->echap($category->name) ?>
                    </a> <br>
                    (<?= $category->ad_count ?> annonce<?= $category->ad_count > 1 ? 's' : '' ?>)
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<h2 class="fs-2 my-4">Dernières annonces</h2>

<div class="row">
    <?php foreach ($fourLastAd as $ad): ?>
        <div class="col-md-3 mb-3">
            <div class="card">
                <?php if (!empty($ad->first_photo)): ?>
                    <a href="/ad/show?id=<?= $ad->id ?>">
                        <img src="/uploads/<?= $ad->first_photo ?>" alt="<?= $this->echap($ad->title) ?>" class="card-img-top">
                    </a>
                <?php else: ?>
                    <a href="/ad/show?id=<?= $ad->id ?>">
                        <img src="/img/no_image.jpg" alt="<?= $this->echap($ad->title) ?>" class="card-img-top">
                    </a>
                <?php endif; ?>
                <div class="card-body">
                    <p class="card-text">
                        <a href="/ad/show?id=<?= $ad->id ?>">
                            <?= $this->echap($ad->title) ?>
                        </a>
                        - <?= $this->formatPrice($ad->price) ?>
                    </p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>