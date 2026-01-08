<h1 class="fs-1 mb-4"><?= $this->echap($ad->title) ?></h1>

<nav aria-label="breadcrumb" class="small mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Accueil</a></li>
        <li class="breadcrumb-item"><a href="/category?id=<?= $ad->category_id ?>"><?= $this->echap($ad->category_name) ?></a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= $this->echap($ad->title) ?></li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-6">
        <?php if (!empty($photos)): ?>
            <div id="carouselExampleIndicators" class="carousel slide">
                <div class="carousel-indicators">
                    <?php foreach ($photos as $index => $photo): ?>
                        <button type="button"
                            data-bs-target="#carouselExampleIndicators"
                            data-bs-slide-to="<?= $index ?>"
                            class="<?= $index === 0 ? 'active' : '' ?>"
                            aria-current="<?= $index === 0 ? 'true' : 'false' ?>"
                            aria-label="Slide <?= $index + 1 ?>">
                        </button>
                    <?php endforeach; ?>
                </div>
                <div class="carousel-inner">
                    <?php foreach ($photos as $index => $photo): ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                            <img src="/uploads/<?= $photo->filename ?>" class="d-block w-100" style="max-height: 420px; object-fit:contain;" alt="<?= $photo->filename ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        <?php else: ?>
            <img src="/img/no_image.jpg" alt="no photo" class="img-fluid">
        <?php endif; ?>
    </div>

    <div class="col-md-6">
        <form action="/purchase/buy" method="post">
            <input type="hidden" name="csrf" value="<?= $this->generateToken() ?>">
            <input type="hidden" name="id" value="<?= $ad->id ?>">
            <input type="hidden" name="delivery_mode_id" value="<?= $ad->delivery_mode_id ?>">
            <h3>Prix</h3>
            <p><?= $this->formatPrice($ad->price) ?></p>
            <h3>Description</h3>
            <p class="break-text"><?= nl2br($this->echap($ad->description)) ?></p>
            <h3>Livraison</h3>
            <p><?= $this->echap($ad->delivery_mode) ?></p>
            <hr>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <div class="alert alert-info" role="alert">Vous devez être connecté pour acheter.
                    <a href="/login" class="alert-link">Me connecter</a></div>
            <?php elseif ($_SESSION['is_admin'] === 1): ?>
                <div class="alert alert-danger">Vous n'êtes pas autorisé</div>
            <?php elseif ($ad->user_id == $_SESSION['user_id']): ?>
                <div class="alert alert-warning">Vous êtes celui qui a déposé cette annonce</div>
            <?php elseif ($ad->sold): ?>
                <div class="alert alert-info">Annonce déjà vendue</div>
            <?php else: ?>
                <button type="submit" class="btn btn-primary">Acheter</button>
            <?php endif; ?>
        </form>
    </div>
</div>