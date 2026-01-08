<h1 class="fs-1 my-4">Ajouter une catégorie</h1>

<nav aria-label="breadcrumb" class="small">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin">Retour</a></li>
        <li class="breadcrumb-item"><a href="/admin/category">Liste des catégories</a></li>
        <li class="breadcrumb-item active" aria-current="page">Ajouter une catégorie</li>
    </ol>
</nav>

<div class="row">
    <form action="/admin/category/add" method="post">
        <input type="hidden" name="csrf" value="<?= $this->generateToken() ?>">
        <div class="mb-3">
            <label for="name">Nom</label>
            <input type="text" name="name" id="name" class="form-control" minlength="5" maxlength="100" required>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>