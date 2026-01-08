<h1 class="fs-1 my-4">Liste des catégories</h1>

<div class="d-flex justify-content-between small">
    <nav aria-label="breadcrumb" class="small">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/admin">Retour</a></li>
            <li class="breadcrumb-item active" aria-current="page">Liste des catégories</li>
        </ol>
    </nav>
    <div>
        <a href="/admin/category/add">Ajouter une catégorie</a>
    </div>
</div>

<?php if (empty($categories)): ?>
    <div class="alert alert-info" role="alert">
        Il n'y a pas encore de catégorie d'ajoutée.
    </div>
<?php else: ?>
    <div class="row mt-4">
        <?php foreach ($categories as $category): ?>
            <div class="col-md-3 mb-3">
                <div class="card">
                    <div class="card-body">
                        <p class="card-text text-center"><?= $this->echap($category->name) ?></p>
                        <div class="d-flex justify-content-center">
                            <a href="/admin/category/rename?id=<?= $category->id ?>">
                                Renommer
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>