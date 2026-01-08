<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/custom.css">
        <title>e-bazar</title>
    </head>
    <body>
        <header>
            <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <div class="container-fluid">
                    <a class="navbar-brand" href="/">e-bazar</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <?php if ($this->isAdmin()): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="/admin">Administration</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/logout">Déconnexion</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link disabled" href="#"><?= $this->echap($_SESSION['email']) ?></a>
                                </li>
                            <?php elseif ($this->isLogged()): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="/ad/add">Déposer une annonce</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/user/ad">Mes annonces</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/user/purchase">Mes achats</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/user/sale">Mes ventes</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/logout">Déconnexion</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link disabled" href="#"><?= $this->echap($_SESSION['email']) ?></a>
                                </li>
                            <?php else: ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="/login">Connexion</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/register">Inscription</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <?php $flash = $this->getMessage(); ?>
        <main>
            <div class="container mt-5">
                <?php if ($flash): ?>
                    <?php if ($flash['type'] == 'error'): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $this->echap($flash['message']) ?>
                        </div>
                    <?php elseif ($flash['type'] == 'success'): ?>
                        <div class="alert alert-success" role="alert">
                            <?= $this->echap($flash['message']) ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <div class="container my-5">
                <?= $content ?>
            </div>
        </main>

        <footer class="container">
            <p class="text-center">&copy; 2025 e-bazar. Joshua Bitho - Ouattara Umm-Habibah</p>
        </footer>
        <script src="/js/bootstrap.bundle.min.js"></script>
    </body>
</html>