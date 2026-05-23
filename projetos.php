<?php

declare(strict_types=1);

$pdo = require __DIR__ . '/db.php';
require __DIR__ . '/includes/storefront_data.php';

$data = loadStorefrontData($pdo);
$projetos = $data['projetos'];

$pageTitle = 'Projetos | Meu Lar Planejados';
$currentPage = 'projetos';
include __DIR__ . '/includes/header.php';
?>
<main>
    <section class="projects-section container-fluid px-3 px-lg-5 py-5">
        <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
            <div>
                <p class="kicker">Projetos</p>
                <h1 class="section-title">Inspiracoes para transformar seu lar.</h1>
            </div>
            <a href="contato.php" class="projects-link">Solicitar orcamento</a>
        </div>
        <div class="row g-3 mb-4">
            <?php foreach ($projetos as $projeto): ?>
                <div class="col-6 col-md-4 col-xl">
                    <article class="project-thumb">
                        <img src="<?= htmlspecialchars($projeto['imagem']) ?>" alt="Projeto <?= htmlspecialchars($projeto['nome']) ?>">
                    </article>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="panel p-4">
            <h2 class="h4 mb-3">Portfolio em evolucao</h2>
            <p class="mb-0 text-secondary">Esta pagina recebe os destaques dos itens cadastrados no painel e serve como vitrine de referencias para novos clientes.</p>
        </div>
    </section>
</main>
<?php include __DIR__ . '/includes/footer.php';
