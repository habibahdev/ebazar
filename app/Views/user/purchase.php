<h1 class="fs-1 mb-4">Mes achats</h1>

<?php if (empty($purchases)): ?>
    <div class="alert alert-info" role="alert">Vous n'avez effectué aucun achat pour le moment.</div>
<?php else: ?>
    <?php foreach ($purchases as $purchase): ?>
    <div class="row mb-3 pb-3 border-bottom">
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
                <?php if (!empty($purchase->photo)): ?>
                    <img src="/uploads/<?= $purchase->photo ?>" alt="" class="img-list-ad">
                <?php else: ?>
                    <img src="/img/no_image.jpg" alt="no photo" class="img-list-ad">
                <?php endif; ?> 
            </div>
            <div class="flex-grow-1 ms-3">
                <?= $purchase->title ?> <br>
                <strong>Passé le</strong> : <?= $purchase->created_at ?> <br>
                <strong>Mode de livraison</strong> : <?= $purchase->delivery_mode ?> <br>
                <?php if (!$purchase->received && $purchase->buyer_id === $_SESSION['user_id'] && isset($_SESSION['user_id'])): ?>
                    <form action="/purchase/received" method="post">
                        <input type="hidden" name="csrf" value="<?= $this->generateToken() ?>">
                        <input type="hidden" name="id" value="<?= $purchase->id ?>">
                        <button type="submit" class="btn btn-primary my-3">Colis réceptionné</button>
                    </form>
                <?php else: ?>
                    <span class="class badge bg-success">reçc</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>