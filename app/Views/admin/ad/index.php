<h1 class="fs-1 mb-4">Liste des annonces</h1>

<nav aria-label="breadcrumb" class="small">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin">Retour</a></li>
        <li class="breadcrumb-item active" aria-current="page">Liste des annonces</li>
    </ol>
</nav>

<?php if (empty($ads)): ?>
    <div class="alert alert-info" role="alert">
        Aucune annonnce publiée pour le moment
    </div>
<?php else: ?>
    <div class="row">
        <?php foreach ($ads as $ad): ?>
            <div class="col-md-3 mb-3">
                <div class="card">
                    <div class="card-body">
                        <p class="card-text"><?= $this->echap($ad->title) ?></p>
                        <form action="/admin/ad/delete" method="post">
                            <input type="hidden" name="csrf" value="<?= $this->generateToken() ?>">
                            <input type="hidden" name="id" value="<?= $ad->id ?>">
                            <button type="submit" class="btn btn-danger">Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>