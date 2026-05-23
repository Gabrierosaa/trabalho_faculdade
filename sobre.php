<?php

declare(strict_types=1);

$pdo = require __DIR__ . '/db.php';
require __DIR__ . '/includes/storefront_data.php';

$data = loadStorefrontData($pdo);
$projetos = $data['projetos'];

$pageTitle = 'Sobre Nos | Meu Lar Planejados';
$currentPage = 'sobre';
include __DIR__ . '/includes/header.php';
?>
<main>
    <section class="materials-hero container-fluid px-3 px-lg-5 py-5">
        <p class="kicker">Sobre Nos</p>
        <h1 class="materials-title">Design, funcionalidade e qualidade em cada detalhe.</h1>
        <p class="materials-subtitle">Somos uma loja de planejados focada em transformar ambientes com projetos inteligentes, atendimento humano e acabamento premium.</p>
    </section>

    <section class="workflow-materials container-fluid px-3 px-lg-5 py-5">
        <div class="row g-4 align-items-start">
            <div class="col-12 col-lg-6">
                <h2 class="section-title text-light">Nossa forma de trabalhar</h2>
                <div class="steps-grid mt-4">
                    <article><span>01</span><h3>Briefing</h3><p>Entendemos seu estilo e objetivos.</p></article>
                    <article><span>02</span><h3>Projeto</h3><p>Apresentamos proposta visual e tecnica.</p></article>
                    <article><span>03</span><h3>Producao</h3><p>Fabricacao com padrao de qualidade.</p></article>
                    <article><span>04</span><h3>Entrega</h3><p>Instalacao com equipe especializada.</p></article>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <article class="testimonial-card h-100">
                    <p>Na Meu Lar, cada projeto nasce de um processo colaborativo. Combinamos curadoria de materiais, ergonomia e execucao tecnica para entregar ambientes bonitos e funcionais.</p>
                    <h3>Equipe Meu Lar</h3>
                    <small>Planejados sob medida</small>
                </article>
            </div>
        </div>
    </section>

    <section class="projects-section container-fluid px-3 px-lg-5 py-5">
        <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
            <div>
                <p class="kicker">Portifolio</p>
                <h2 class="section-title">Projetos recentes</h2>
            </div>
            <a href="projetos.php" class="projects-link">Ver pagina de projetos</a>
        </div>
        <div class="row g-3">
            <?php foreach ($projetos as $projeto): ?>
                <div class="col-6 col-md-4 col-xl">
                    <article class="project-thumb">
                        <img src="<?= htmlspecialchars($projeto['imagem']) ?>" alt="Projeto <?= htmlspecialchars($projeto['nome']) ?>">
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>
<?php include __DIR__ . '/includes/footer.php';
