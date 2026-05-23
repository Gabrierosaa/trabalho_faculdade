<?php

declare(strict_types=1);

session_start();

if (!empty($_SESSION['auth'])) {
    header('Location: admin.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['usuario'] ?? '');
    $pass = trim($_POST['senha'] ?? '');

    $validUser = 'admin';
    $validPass = 'startup123';

    if ($user === $validUser && hash_equals($validPass, $pass)) {
        $_SESSION['auth'] = true;
        $_SESSION['usuario'] = $user;
        header('Location: admin.php');
        exit;
    }

    $error = 'Usuario ou senha invalidos.';
}

$pageTitle = 'Login | Meu Lar Planejados';
$currentPage = 'login';
include __DIR__ . '/includes/header.php';
?>
<main class="container py-5">
    <section class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-5">
            <div class="panel p-4">
                <h1 class="h4 mb-3">Acesso administrativo</h1>
                <?php if ($error !== ''): ?>
                    <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="post" action="login.php" novalidate>
                    <div class="mb-3">
                        <label for="usuario" class="form-label">Usuario</label>
                        <input type="text" id="usuario" name="usuario" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" id="senha" name="senha" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-brand w-100">Entrar</button>
                </form>
                <p class="mt-3 small text-secondary">Credenciais demo: admin / startup123</p>
            </div>
        </div>
    </section>
</main>
<?php include __DIR__ . '/includes/footer.php';
