<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$pageTitle = $pageTitle ?? 'Meu Lar Planejados';
$currentPage = $currentPage ?? '';
$isLogged = !empty($_SESSION['auth']);
$isAdminArea = in_array($currentPage, ['admin', 'login'], true);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Sora:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
<header class="site-header">
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-3 px-lg-4">
            <a class="navbar-brand brand-inline" href="index.php">
                <span class="brand-mark" aria-hidden="true">M</span>
                <span class="brand-copy">
                    <strong>MEU LAR</strong>
                    <small>PLANEJADOS</small>
                </span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Abrir menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <?php if ($isAdminArea): ?>
                        <li class="nav-item"><a class="nav-link" href="index.php">Loja</a></li>
                        <?php if ($isLogged): ?>
                            <li class="nav-item"><a class="nav-link <?= $currentPage === 'admin' ? 'active' : '' ?>" href="admin.php">Painel</a></li>
                            <li class="nav-item mt-2 mt-lg-0"><a class="btn btn-sm btn-outline-light" href="logout.php">Sair</a></li>
                        <?php else: ?>
                            <li class="nav-item mt-2 mt-lg-0"><a class="btn btn-sm btn-brand" href="login.php">Entrar no Painel</a></li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link <?= $currentPage === 'home' ? 'active' : '' ?>" href="index.php">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link <?= $currentPage === 'sobre' ? 'active' : '' ?>" href="sobre.php">Sobre Nos</a></li>
                        <li class="nav-item"><a class="nav-link <?= $currentPage === 'ambientes' ? 'active' : '' ?>" href="ambientes.php">Ambientes</a></li>
                        <li class="nav-item"><a class="nav-link <?= $currentPage === 'materiais' ? 'active' : '' ?>" href="materiais.php">Materiais</a></li>
                        <li class="nav-item"><a class="nav-link <?= $currentPage === 'projetos' ? 'active' : '' ?>" href="projetos.php">Projetos</a></li>
                        <li class="nav-item"><a class="nav-link <?= $currentPage === 'depoimentos' ? 'active' : '' ?>" href="depoimentos.php">Depoimentos</a></li>
                        <li class="nav-item"><a class="nav-link <?= $currentPage === 'contato' ? 'active' : '' ?>" href="contato.php">Contato</a></li>
                        <li class="nav-item mt-2 mt-lg-0"><a class="btn btn-sm btn-outline-brand" href="contato.php">Orcamento</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>
