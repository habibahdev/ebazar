<h1 class="fs-1 mb-4">Liste des utilisateurs</h1>

<nav aria-label="breadcrumb" class="small">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin">Retour</a></li>
        <li class="breadcrumb-item active" aria-current="page">Liste des utilisateurs</li>
    </ol>
</nav>

<?php if (empty($users)): ?>
    <div class="alert alert-info" role="alert">
        Aucun utilisateur inscrit pour le moment
    </div>
<?php else: ?>
    <div class="row">
        <?php foreach ($users as $user): ?>
            <div class="col-md-3 mb-3">
                <div class="card">
                    <div class="card-body">
                        <p class="card-text"><?= $this->echap($user->email) ?></p>
                        <form action="/admin/user/delete" method="post">
                            <input type="hidden" name="csrf" value="<?= $this->generateToken() ?>">
                            <input type="hidden" name="id" value="<?= $user->id ?>">
                            <button type="submit" class="btn btn-danger">Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>