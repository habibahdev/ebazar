<h1 class="fs-1 mb-4">Mes annonces</h1>

<?php if (empty($ads)): ?>
    <div class="alert alert-info">Vous n'avez posté aucune annonce pour le moment.</div>
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Déposée le</th>
                <th scope="col">Titre de l'annonce</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ads as $ad): ?>
                <tr>
                    <th scope="row"><?= $ad->id ?></th>
                    <td><?= $ad->created_at ?></td>
                    <td><?= $ad->title ?></td>
                    <td>
                        <?php if (!$ad->sold): ?>
                            <form action="/user/delete" method="post">
                                <input type="hidden" name="csrf" value="<?= $this->generateToken() ?>">
                                <input type="hidden" name="id" value="<?= $ad->id ?>">
                                <button type="submit" class="btn btn-danger">Supprimer</button>
                            </form>
                        <?php else: ?>
                            <span class="badge text-bg-success">Vendue</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>