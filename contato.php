<?php

declare(strict_types=1);

$pdo = require __DIR__ . '/db.php';

$errors = [];
$successMessage = '';
$old = [
    'nome' => '',
    'email' => '',
    'mensagem' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_type'] ?? '') === 'contato') {
    $old['nome'] = trim($_POST['nome'] ?? '');
    $old['email'] = trim($_POST['email'] ?? '');
    $old['mensagem'] = trim($_POST['mensagem'] ?? '');

    if ($old['nome'] === '' || $old['email'] === '' || $old['mensagem'] === '') {
        $errors[] = 'Preencha todos os campos do formulario.';
    }

    if ($old['email'] !== '' && !filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Informe um e-mail valido.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare('INSERT INTO contatos (nome, email, mensagem) VALUES (:nome, :email, :mensagem)');
        $stmt->execute([
            ':nome' => $old['nome'],
            ':email' => $old['email'],
            ':mensagem' => $old['mensagem'],
        ]);

        $successMessage = 'Contato enviado com sucesso.';
        $old = ['nome' => '', 'email' => '', 'mensagem' => ''];
    }
}

$pageTitle = 'Contato | Meu Lar Planejados';
$currentPage = 'contato';
include __DIR__ . '/includes/header.php';
?>
<main>
    <section class="contact-section container-fluid px-3 px-lg-5 py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="panel p-4 p-lg-5" <?= $successMessage !== '' ? 'data-contact-success="true"' : '' ?>>
                    <p class="kicker">Contato</p>
                    <h1 class="section-title mb-3">Solicite seu projeto e fale com um especialista</h1>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger" role="alert">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if ($successMessage !== ''): ?>
                        <div class="alert alert-success" role="alert"><?= htmlspecialchars($successMessage) ?></div>
                    <?php endif; ?>

                    <form id="contact-form" method="post" action="contato.php" novalidate>
                        <input type="hidden" name="form_type" value="contato">

                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" id="nome" name="nome" class="form-control" value="<?= htmlspecialchars($old['nome']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($old['email']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="mensagem" class="form-label">Mensagem</label>
                            <textarea id="mensagem" name="mensagem" rows="4" class="form-control" required><?= htmlspecialchars($old['mensagem']) ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-brand">Enviar mensagem</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>
<?php include __DIR__ . '/includes/footer.php';
