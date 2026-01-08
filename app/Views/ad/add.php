<h1 class="fs-1 mb-4">Déposer une annonce</h1>

<form action="/ad/add" method="post" enctype="multipart/form-data" class="row g-3">
    <input type="hidden" name="csrf" value="<?= $this->generateToken() ?>">
    <div class="col-12">
        <label for="title" class="form-label">Titre</label>
        <input type="text" name="title" id="title" minlength="5" maxlength="30" class="form-control" required>
    </div>
    <div class="col-12">
        <label for="description" class="form-label">Description</label>
        <textarea name="description" id="description" rows="5" cols="50" minlength="5" maxlength="200" class="form-control" required></textarea>
    </div>
    <div class="col-md-4">
        <label for="price" class="form-label">Prix (en €)</label>
        <input type="text" name="price" id="price" class="form-control" placeholder="Un prix à virgule : 2,9 ou 3,89" required>
    </div>
    <div class="col-md-4">
        <label for="category_id" class="form-label">Catégorie</label>
        <select class="form-select" name="category_id" id="category_id" required>
            <option value="" selected>Choisir une catégorie</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category->id ?>"><?= $this->echap($category->name) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-4">
        <label for="delivery_mode_id" class="form-label">Mode de livraison</label>
        <select class="form-select" name="delivery_mode_id" id="delivery_mode_id" required>
            <option value="" selected>Choisir un mode de livraison</option>
            <?php foreach ($deliveryModes as $delivery): ?>
                <option value="<?= $delivery->id ?>"><?= $this->echap($delivery->name) ?></option>
            <?php endforeach; ?>
        </select>
    </div>    
    <div class="col-12">
        <label for="photos_id" class="form-label">Photos (Optionnel)</label>
        <input type="file" class="form-control" name="photos[]" accept="image/jpeg,image/jpg" id="photos_id" multiple>
        <div id="photoHelper" class="form-text">
            Télécharger au plus 5 photos au format jpeg ou jpg, de taille 200 kio chacune
        </div>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary w-100">Publier</button>
    </div>
</form>