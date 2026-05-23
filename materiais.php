<?php

declare(strict_types=1);

$pdo = require __DIR__ . '/db.php';

$countStmt = $pdo->prepare('SELECT COUNT(*) AS total FROM materiais');
$countStmt->execute();
$totalMateriais = (int) ($countStmt->fetch()['total'] ?? 0);

if ($totalMateriais === 0) {
    $seedStmt = $pdo->prepare('INSERT INTO materiais (nome, descricao, cor_hex, imagem) VALUES (:nome, :descricao, :cor_hex, :imagem)');
    $seedData = [
        ['Cinza Grafite', 'Acabamento sofisticado para projetos contemporaneos.', '#1E1F1E', 'https://images.unsplash.com/photo-1617806118233-18e1de247200?auto=format&fit=crop&w=900&q=80'],
        ['Carvalho Natural', 'Textura amadeirada quente para ambientes acolhedores.', '#D9B17C', 'https://images.unsplash.com/photo-1617098474202-0d0d7f60cccf?auto=format&fit=crop&w=900&q=80'],
        ['Off White', 'Visual clean e elegante para ampliar o espaco.', '#F2F0EC', 'https://images.unsplash.com/photo-1616627547584-bf28cee262db?auto=format&fit=crop&w=900&q=80'],
        ['Freijo Puro', 'Padrao nobre com presenca e autenticidade.', '#8A5A3B', 'https://images.unsplash.com/photo-1617098591651-dd331f3f5c76?auto=format&fit=crop&w=900&q=80'],
    ];

    foreach ($seedData as $row) {
        $seedStmt->execute([
            ':nome' => $row[0],
            ':descricao' => $row[1],
            ':cor_hex' => $row[2],
            ':imagem' => $row[3],
        ]);
    }
}

$listStmt = $pdo->prepare('SELECT id, nome, descricao, cor_hex, imagem FROM materiais ORDER BY id DESC');
$listStmt->execute();
$materiais = $listStmt->fetchAll();

$pageTitle = 'Materiais | Meu Lar Planejados';
$currentPage = 'materiais';
include __DIR__ . '/includes/header.php';
?>
<main>
    <section class="materials-hero container-fluid px-3 px-lg-5 py-5">
        <p class="kicker">Materiais</p>
        <h1 class="materials-title">Acabamentos que elevam cada projeto.</h1>
        <p class="materials-subtitle">Conheca os materiais disponiveis na loja. Esta pagina e alimentada diretamente pelo painel administrativo.</p>
    </section>

    <section class="materials-grid-section container-fluid px-3 px-lg-5 pb-5">
        <div class="row g-4">
            <?php foreach ($materiais as $material): ?>
                <div class="col-12 col-md-6 col-xl-4">
                    <article class="material-card h-100">
                        <img src="<?= htmlspecialchars($material['imagem']) ?>" alt="Material <?= htmlspecialchars($material['nome']) ?>">
                        <div class="material-card-body">
                            <div class="material-swatch" style="background-color: <?= htmlspecialchars($material['cor_hex']) ?>;" aria-hidden="true"></div>
                            <h2><?= htmlspecialchars($material['nome']) ?></h2>
                            <p><?= htmlspecialchars($material['descricao']) ?></p>
                            <small><?= htmlspecialchars($material['cor_hex']) ?></small>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>
<?php include __DIR__ . '/includes/footer.php';
