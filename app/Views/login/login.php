<h1 class="fs-1">Connexion</h1>

<hr>

<form action="/login" method="post">
    <input type="hidden" name="csrf" value="<?= $this->generateToken() ?>">
    <div class="mb-3">
        <label for="email" class="form-label">Adresse email</label>
        <input type="email" name="email" id="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Mot de passe</label>
        <input type="password" name="password" id="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Connexion</button>
</form>