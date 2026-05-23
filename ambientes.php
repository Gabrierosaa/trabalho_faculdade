<?php

declare(strict_types=1);

$pdo = require __DIR__ . '/db.php';
require __DIR__ . '/includes/storefront_data.php';

$data = loadStorefrontData($pdo);
$ambienteSlides = $data['ambienteSlides'];

$pageTitle = 'Ambientes | Meu Lar Planejados';
$currentPage = 'ambientes';
include __DIR__ . '/includes/header.php';
?>
<main>
    <section class="ambientes-section container-fluid px-3 px-lg-5 py-5">
        <div class="row g-4 align-items-start">
            <div class="col-12 col-lg-4">
                <p class="kicker">Ambientes</p>
                <h1 class="section-title">Explore opcoes para cada espaco da sua casa.</h1>
                <p class="ambientes-copy">Veja ideias para sala, quarto, cozinha, escritorio e outros ambientes planejados.</p>
                <a href="contato.php" class="btn btn-outline-amber">Solicitar projeto</a>
            </div>
            <div class="col-12 col-lg-8">
                <div id="ambientesPageCarousel" class="carousel slide ambientes-carousel" data-bs-ride="carousel" data-bs-interval="4800">
                    <div class="carousel-inner">
                        <?php foreach ($ambienteSlides as $slideIndex => $slide): ?>
                            <div class="carousel-item <?= $slideIndex === 0 ? 'active' : '' ?>">
                                <div class="row g-3">
                                    <?php foreach ($slide as $item): ?>
                                        <div class="col-12 col-sm-6 col-xl-3">
                                            <article class="ambient-card">
                                                <img src="<?= htmlspecialchars($item['imagem']) ?>" alt="Ambiente <?= htmlspecialchars($item['nome']) ?>">
                                                <div class="ambient-overlay">
                                                    <h3><?= htmlspecialchars($item['nome']) ?></h3>
                                                    <a href="contato.php">Saiba mais</a>
                                                </div>
                                            </article>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#ambientesPageCarousel" data-bs-slide="prev" aria-label="Slide anterior">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#ambientesPageCarousel" data-bs-slide="next" aria-label="Proximo slide">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </section>
</main>
<?php include __DIR__ . '/includes/footer.php';
