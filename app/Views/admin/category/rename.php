<h1 class="fs-1 my-4">Renommer : <?= $this->echap($category->name) ?></h1>

<nav aria-label="breadcrumb" class="small">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/category">Retour</a></li>
        <li class="breadcrumb-item active" aria-current="page">Renommer une catégorie</li>
    </ol>
</nav>

<div class="row">
    <form action="/admin/category/rename" method="post">
        <input type="hidden" name="csrf" value="<?= $this->generateToken() ?>">
        <input type="hidden" name="id" value="<?= $category->id ?>">
        <div class="mb-3">
            <label for="name">Nouveau nom</label>
            <input type="text" name="name" id="name" class="form-control" minlength="5" maxlength="100" value="<?= $this->echap($category->name) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Valider</button>
    </form>
</div>