<?php

declare(strict_types=1);

$testimonials = [
    ['texto' => 'Ficamos impressionados com o atendimento e o cuidado em cada detalhe. O resultado superou nossas expectativas.', 'autor' => 'Juliana e Ricardo', 'projeto' => 'Projeto: Cozinha'],
    ['texto' => 'Nosso quarto virou outro ambiente. Tudo pensado com muito bom gosto e aproveitamento de espaco.', 'autor' => 'Mariana Lima', 'projeto' => 'Projeto: Quarto'],
    ['texto' => 'Profissionais incriveis. Cumpriram o prazo e o acabamento e impecavel. Recomendo demais.', 'autor' => 'Carlos Eduardo', 'projeto' => 'Projeto: Escritorio'],
    ['texto' => 'O acompanhamento do inicio ao fim foi excelente. Recebemos orientacoes claras e um resultado impecavel.', 'autor' => 'Renata Souza', 'projeto' => 'Projeto: Home Office'],
    ['texto' => 'A marcenaria ficou linda e muito funcional. Cada centimetro do apartamento foi bem aproveitado.', 'autor' => 'Thiago e Paula', 'projeto' => 'Projeto: Sala Integrada'],
    ['texto' => 'Qualidade acima da media e equipe extremamente pontual. Fariamos tudo novamente com eles.', 'autor' => 'Fernanda Costa', 'projeto' => 'Projeto: Lavanderia'],
];

$pageTitle = 'Depoimentos | Meu Lar Planejados';
$currentPage = 'depoimentos';
include __DIR__ . '/includes/header.php';
?>
<main>
    <section class="testimonials-section container-fluid px-3 px-lg-5 py-5">
        <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
            <div>
                <p class="kicker">Depoimentos</p>
                <h1 class="section-title text-light">Historias reais, <span>resultados que falam.</span></h1>
            </div>
        </div>

        <div id="testimonialsPageCarousel" class="carousel slide testimonials-carousel" data-bs-ride="carousel" data-bs-interval="5200">
            <div class="carousel-indicators">
                <?php foreach (array_chunk($testimonials, 3) as $slideIndex => $slide): ?>
                    <button type="button" data-bs-target="#testimonialsPageCarousel" data-bs-slide-to="<?= $slideIndex ?>" class="<?= $slideIndex === 0 ? 'active' : '' ?>" <?= $slideIndex === 0 ? 'aria-current="true"' : '' ?> aria-label="Depoimento <?= $slideIndex + 1 ?>"></button>
                <?php endforeach; ?>
            </div>

            <div class="carousel-inner">
                <?php foreach (array_chunk($testimonials, 3) as $slideIndex => $slide): ?>
                    <div class="carousel-item <?= $slideIndex === 0 ? 'active' : '' ?>">
                        <div class="row g-3">
                            <?php foreach ($slide as $item): ?>
                                <div class="col-12 col-md-6 col-lg-4">
                                    <article class="testimonial-card">
                                        <p><?= htmlspecialchars($item['texto']) ?></p>
                                        <h3><?= htmlspecialchars($item['autor']) ?></h3>
                                        <small><?= htmlspecialchars($item['projeto']) ?></small>
                                    </article>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#testimonialsPageCarousel" data-bs-slide="prev" aria-label="Depoimento anterior">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#testimonialsPageCarousel" data-bs-slide="next" aria-label="Proximo depoimento">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </button>
        </div>

        <section class="cta-strip container-fluid px-3 px-lg-5 py-3 mt-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <h2>Quer viver esse resultado no seu lar?</h2>
                <a href="contato.php" class="btn btn-light">Falar com especialista</a>
            </div>
        </section>
    </section>
</main>
<?php include __DIR__ . '/includes/footer.php';
