<h1 class="fs-1 mb-4">Mes ventes</h1>

<?php if (empty($sales)): ?>
    <div class="alert alert-info">VOus n'avez effectué aucune vente pour le moment.</div>
<?php else: ?>
    <?php foreach ($sales as $sale): ?>
    <div class="row mb-3 pb-3 border-bottom">
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
                <?php if (!empty($sale->photo)): ?>
                    <img src="/uploads/<?= $sale->photo ?>" alt="Photo" class="img-list-ad">
                <?php else: ?>
                    <img src="/img/no_image.jpg" alt="no photo" class="img-list-ad">
                <?php endif; ?> 
            </div>
            <div class="flex-grow-1 ms-3">
                <?= $this->echap($sale->title) ?> <br>
                <strong>Passé le</strong> : <?= $sale->created_at ?> <br>
                <strong>Mode de livraison</strong> : <?= $sale->delivery_mode ?> <br>
                <?php if (!$sale->received): ?>
                    <span class="class badge bg-warning">En attente de paiement</span>
                <?php else: ?>
                    <span class="class badge bg-success">Transaction effectuée</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>