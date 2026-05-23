<?php

declare(strict_types=1);

session_start();

if (empty($_SESSION['auth'])) {
    header('Location: login.php');
    exit;
}

$pdo = require __DIR__ . '/db.php';

$errors = [];
$successMessage = '';
$editItem = null;
$editMaterial = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entity = $_POST['entity'] ?? '';

    if ($entity === 'produto') {
        $action = $_POST['prod_action'] ?? '';
        $id = (int) ($_POST['prod_id'] ?? 0);
        $nome = trim($_POST['prod_nome'] ?? '');
        $descricao = trim($_POST['prod_descricao'] ?? '');
        $preco = trim($_POST['prod_preco'] ?? '');
        $imagem = trim($_POST['prod_imagem'] ?? '');

        if ($nome === '' || $descricao === '' || $preco === '' || $imagem === '') {
            $errors[] = 'Todos os campos de produto sao obrigatorios.';
        }

        if ($preco !== '' && !is_numeric($preco)) {
            $errors[] = 'Preco do produto deve ser numerico.';
        }

        if (empty($errors) && $action === 'create') {
            $stmt = $pdo->prepare('INSERT INTO itens (nome, descricao, preco, imagem) VALUES (:nome, :descricao, :preco, :imagem)');
            $stmt->execute([
                ':nome' => $nome,
                ':descricao' => $descricao,
                ':preco' => (float) $preco,
                ':imagem' => $imagem,
            ]);
            $successMessage = 'Produto criado com sucesso.';
        }

        if (empty($errors) && $action === 'update' && $id > 0) {
            $stmt = $pdo->prepare('UPDATE itens SET nome = :nome, descricao = :descricao, preco = :preco, imagem = :imagem WHERE id = :id');
            $stmt->execute([
                ':nome' => $nome,
                ':descricao' => $descricao,
                ':preco' => (float) $preco,
                ':imagem' => $imagem,
                ':id' => $id,
            ]);
            $successMessage = 'Produto atualizado com sucesso.';
        }
    }

    if ($entity === 'material') {
        $action = $_POST['mat_action'] ?? '';
        $id = (int) ($_POST['mat_id'] ?? 0);
        $nome = trim($_POST['mat_nome'] ?? '');
        $descricao = trim($_POST['mat_descricao'] ?? '');
        $corHex = strtoupper(trim($_POST['mat_cor_hex'] ?? ''));
        $imagem = trim($_POST['mat_imagem'] ?? '');

        if ($nome === '' || $descricao === '' || $corHex === '' || $imagem === '') {
            $errors[] = 'Todos os campos de material sao obrigatorios.';
        }

        if ($corHex !== '' && !preg_match('/^#[A-F0-9]{6}$/', $corHex)) {
            $errors[] = 'Cor HEX do material deve estar no formato #RRGGBB.';
        }

        if (empty($errors) && $action === 'create') {
            $stmt = $pdo->prepare('INSERT INTO materiais (nome, descricao, cor_hex, imagem) VALUES (:nome, :descricao, :cor_hex, :imagem)');
            $stmt->execute([
                ':nome' => $nome,
                ':descricao' => $descricao,
                ':cor_hex' => $corHex,
                ':imagem' => $imagem,
            ]);
            $successMessage = 'Material criado com sucesso.';
        }

        if (empty($errors) && $action === 'update' && $id > 0) {
            $stmt = $pdo->prepare('UPDATE materiais SET nome = :nome, descricao = :descricao, cor_hex = :cor_hex, imagem = :imagem WHERE id = :id');
            $stmt->execute([
                ':nome' => $nome,
                ':descricao' => $descricao,
                ':cor_hex' => $corHex,
                ':imagem' => $imagem,
                ':id' => $id,
            ]);
            $successMessage = 'Material atualizado com sucesso.';
        }
    }
}

if (isset($_GET['prod_delete'])) {
    $idDelete = (int) $_GET['prod_delete'];
    if ($idDelete > 0) {
        $stmt = $pdo->prepare('DELETE FROM itens WHERE id = :id');
        $stmt->execute([':id' => $idDelete]);
        $successMessage = 'Produto excluido com sucesso.';
    }
}

if (isset($_GET['prod_edit'])) {
    $idEdit = (int) $_GET['prod_edit'];
    if ($idEdit > 0) {
        $stmt = $pdo->prepare('SELECT id, nome, descricao, preco, imagem FROM itens WHERE id = :id');
        $stmt->execute([':id' => $idEdit]);
        $editItem = $stmt->fetch();
    }
}

if (isset($_GET['mat_delete'])) {
    $idDelete = (int) $_GET['mat_delete'];
    if ($idDelete > 0) {
        $stmt = $pdo->prepare('DELETE FROM materiais WHERE id = :id');
        $stmt->execute([':id' => $idDelete]);
        $successMessage = 'Material excluido com sucesso.';
    }
}

if (isset($_GET['mat_edit'])) {
    $idEdit = (int) $_GET['mat_edit'];
    if ($idEdit > 0) {
        $stmt = $pdo->prepare('SELECT id, nome, descricao, cor_hex, imagem FROM materiais WHERE id = :id');
        $stmt->execute([':id' => $idEdit]);
        $editMaterial = $stmt->fetch();
    }
}

$itensStmt = $pdo->prepare('SELECT id, nome, descricao, preco, imagem FROM itens ORDER BY id DESC');
$itensStmt->execute();
$itens = $itensStmt->fetchAll();

$materiaisStmt = $pdo->prepare('SELECT id, nome, descricao, cor_hex, imagem FROM materiais ORDER BY id DESC');
$materiaisStmt->execute();
$materiais = $materiaisStmt->fetchAll();

$contatosStmt = $pdo->prepare('SELECT id, nome, email, mensagem, created_at FROM contatos ORDER BY id DESC LIMIT 10');
$contatosStmt->execute();
$contatos = $contatosStmt->fetchAll();

$totalItens = count($itens);
$totalMateriais = count($materiais);
$totalContatos = count($contatos);
$latestItemName = $itens[0]['nome'] ?? 'Nenhum produto cadastrado';
$latestMaterialName = $materiais[0]['nome'] ?? 'Nenhum material cadastrado';

$pageTitle = 'Admin | Meu Lar Planejados';
$currentPage = 'admin';
include __DIR__ . '/includes/header.php';
?>
<main class="admin-page container-fluid px-3 px-lg-4 py-4 py-lg-5">
    <section class="admin-page-header mb-4">
        <h1 class="h3 mb-3">Painel administrativo</h1>
        <p class="admin-page-subtitle">Gerencie produtos, materiais da pagina Materiais e acompanhe contatos enviados.</p>
    </section>

    <section class="admin-stats mb-4">
        <article class="admin-stat-card">
            <small>Produtos</small>
            <strong><?= htmlspecialchars((string) $totalItens) ?></strong>
            <p class="mb-0">Ultimo: <?= htmlspecialchars($latestItemName) ?></p>
        </article>
        <article class="admin-stat-card">
            <small>Materiais</small>
            <strong><?= htmlspecialchars((string) $totalMateriais) ?></strong>
            <p class="mb-0">Ultimo: <?= htmlspecialchars($latestMaterialName) ?></p>
        </article>
        <article class="admin-stat-card">
            <small>Contatos recentes</small>
            <strong><?= htmlspecialchars((string) $totalContatos) ?></strong>
            <p class="mb-0">Ultimos 10 envios</p>
        </article>
    </section>

    <section class="row g-4">
        <div class="col-12 col-xl-5">
            <div class="panel p-4 mb-4">
                <h2 class="h5 mb-3"><?= $editItem ? 'Editar produto' : 'Novo produto' ?></h2>

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

                <form method="post" action="admin.php" novalidate>
                    <input type="hidden" name="entity" value="produto">
                    <input type="hidden" name="prod_action" value="<?= $editItem ? 'update' : 'create' ?>">
                    <input type="hidden" name="prod_id" value="<?= htmlspecialchars((string) ($editItem['id'] ?? 0)) ?>">

                    <div class="mb-3">
                        <label for="prod_nome" class="form-label">Nome do produto</label>
                        <input type="text" id="prod_nome" name="prod_nome" class="form-control" value="<?= htmlspecialchars($editItem['nome'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="prod_descricao" class="form-label">Descricao</label>
                        <textarea id="prod_descricao" name="prod_descricao" rows="3" class="form-control" required><?= htmlspecialchars($editItem['descricao'] ?? '') ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="prod_preco" class="form-label">Preco</label>
                        <input type="text" id="prod_preco" name="prod_preco" class="form-control" value="<?= htmlspecialchars(isset($editItem['preco']) ? (string) $editItem['preco'] : '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="prod_imagem" class="form-label">URL da imagem</label>
                        <input type="url" id="prod_imagem" name="prod_imagem" class="form-control" value="<?= htmlspecialchars($editItem['imagem'] ?? '') ?>" data-image-preview-target="#prod_preview" required>
                    </div>

                    <figure class="admin-image-preview mb-3">
                        <img id="prod_preview" src="<?= htmlspecialchars($editItem['imagem'] ?? '') ?>" alt="Preview da imagem do produto">
                    </figure>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-brand"><?= $editItem ? 'Salvar alteracoes' : 'Cadastrar produto' ?></button>
                        <?php if ($editItem): ?>
                            <a href="admin.php" class="btn btn-outline-secondary">Cancelar</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <div class="panel p-4">
                <h2 class="h5 mb-3"><?= $editMaterial ? 'Editar material' : 'Novo material' ?></h2>

                <form method="post" action="admin.php" novalidate>
                    <input type="hidden" name="entity" value="material">
                    <input type="hidden" name="mat_action" value="<?= $editMaterial ? 'update' : 'create' ?>">
                    <input type="hidden" name="mat_id" value="<?= htmlspecialchars((string) ($editMaterial['id'] ?? 0)) ?>">

                    <div class="mb-3">
                        <label for="mat_nome" class="form-label">Nome do material</label>
                        <input type="text" id="mat_nome" name="mat_nome" class="form-control" value="<?= htmlspecialchars($editMaterial['nome'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="mat_descricao" class="form-label">Descricao</label>
                        <textarea id="mat_descricao" name="mat_descricao" rows="3" class="form-control" required><?= htmlspecialchars($editMaterial['descricao'] ?? '') ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="mat_cor_hex" class="form-label">Cor HEX</label>
                        <input type="text" id="mat_cor_hex" name="mat_cor_hex" class="form-control" placeholder="#AABBCC" value="<?= htmlspecialchars($editMaterial['cor_hex'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="mat_imagem" class="form-label">URL da imagem</label>
                        <input type="url" id="mat_imagem" name="mat_imagem" class="form-control" value="<?= htmlspecialchars($editMaterial['imagem'] ?? '') ?>" data-image-preview-target="#mat_preview" required>
                    </div>

                    <figure class="admin-image-preview mb-3">
                        <img id="mat_preview" src="<?= htmlspecialchars($editMaterial['imagem'] ?? '') ?>" alt="Preview da imagem do material">
                    </figure>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-brand"><?= $editMaterial ? 'Salvar alteracoes' : 'Cadastrar material' ?></button>
                        <?php if ($editMaterial): ?>
                            <a href="admin.php" class="btn btn-outline-secondary">Cancelar</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-12 col-xl-7">
            <div class="panel p-4 mb-4">
                <h2 class="h5 mb-3">Produtos cadastrados</h2>
                <div class="mb-3">
                    <input type="search" class="form-control admin-search-input" placeholder="Buscar produto por nome, descricao ou preco" data-table-filter data-table-target="#productsTable tbody tr">
                </div>
                <div class="table-responsive">
                    <table id="productsTable" class="table align-middle">
                        <thead>
                            <tr>
                                <th>Imagem</th>
                                <th>Nome</th>
                                <th>Preco</th>
                                <th class="text-end">Acoes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($itens as $item): ?>
                                <tr>
                                    <td>
                                        <img class="admin-table-thumb" src="<?= htmlspecialchars($item['imagem']) ?>" alt="Imagem do produto <?= htmlspecialchars($item['nome']) ?>">
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars($item['nome']) ?></strong>
                                        <small class="d-block text-secondary"><?= htmlspecialchars($item['descricao']) ?></small>
                                    </td>
                                    <td>R$ <?= htmlspecialchars(number_format((float) $item['preco'], 2, ',', '.')) ?></td>
                                    <td class="text-end">
                                        <a href="admin.php?prod_edit=<?= htmlspecialchars((string) $item['id']) ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                                        <a href="admin.php?prod_delete=<?= htmlspecialchars((string) $item['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Deseja excluir este produto?')">Excluir</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="panel p-4 mb-4">
                <h2 class="h5 mb-3">Materiais cadastrados</h2>
                <div class="mb-3">
                    <input type="search" class="form-control admin-search-input" placeholder="Buscar material por nome, descricao ou cor" data-table-filter data-table-target="#materialsTable tbody tr">
                </div>
                <div class="table-responsive">
                    <table id="materialsTable" class="table align-middle">
                        <thead>
                            <tr>
                                <th>Imagem</th>
                                <th>Nome</th>
                                <th>Cor</th>
                                <th class="text-end">Acoes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($materiais as $material): ?>
                                <tr>
                                    <td>
                                        <img class="admin-table-thumb" src="<?= htmlspecialchars($material['imagem']) ?>" alt="Imagem do material <?= htmlspecialchars($material['nome']) ?>">
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars($material['nome']) ?></strong>
                                        <small class="d-block text-secondary"><?= htmlspecialchars($material['descricao']) ?></small>
                                    </td>
                                    <td>
                                        <span class="hex-pill" style="background-color: <?= htmlspecialchars($material['cor_hex']) ?>;"></span>
                                        <?= htmlspecialchars($material['cor_hex']) ?>
                                    </td>
                                    <td class="text-end">
                                        <a href="admin.php?mat_edit=<?= htmlspecialchars((string) $material['id']) ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                                        <a href="admin.php?mat_delete=<?= htmlspecialchars((string) $material['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Deseja excluir este material?')">Excluir</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <a href="materiais.php" class="btn btn-outline-brand btn-sm mt-2">Abrir pagina Materiais</a>
            </div>

            <div class="panel p-4">
                <h2 class="h5 mb-3">Ultimos contatos</h2>
                <div class="mb-3">
                    <input type="search" class="form-control admin-search-input" placeholder="Buscar contato por nome, e-mail ou mensagem" data-table-filter data-table-target="#contactsTable tbody tr">
                </div>
                <div class="table-responsive">
                    <table id="contactsTable" class="table align-middle">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Mensagem</th>
                                <th>Enviado em</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($contatos as $contato): ?>
                                <tr>
                                    <td><?= htmlspecialchars($contato['nome']) ?></td>
                                    <td><?= htmlspecialchars($contato['email']) ?></td>
                                    <td><?= htmlspecialchars($contato['mensagem']) ?></td>
                                    <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime((string) $contato['created_at']))) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>
<?php include __DIR__ . '/includes/footer.php';
