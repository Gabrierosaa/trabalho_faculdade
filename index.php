<?php

declare(strict_types=1);

$pdo = require __DIR__ . '/db.php';
require __DIR__ . '/includes/storefront_data.php';

$data = loadStorefrontData($pdo);
$ambienteSlides = $data['ambienteSlides'];
$projetos = $data['projetos'];
$materiais = $data['materiaisPreview'];
$testimonials = [
    ['texto' => 'Ficamos impressionados com o atendimento e o cuidado em cada detalhe. O resultado superou nossas expectativas.', 'autor' => 'Juliana e Ricardo', 'projeto' => 'Projeto: Cozinha'],
    ['texto' => 'Nosso quarto virou outro ambiente. Tudo pensado com muito bom gosto e aproveitamento de espaco.', 'autor' => 'Mariana Lima', 'projeto' => 'Projeto: Quarto'],
    ['texto' => 'Profissionais incriveis. Cumpriram o prazo e o acabamento e impecavel. Recomendo demais.', 'autor' => 'Carlos Eduardo', 'projeto' => 'Projeto: Escritorio'],
    ['texto' => 'O acompanhamento do inicio ao fim foi excelente. Recebemos orientacoes claras e um resultado impecavel.', 'autor' => 'Renata Souza', 'projeto' => 'Projeto: Home Office'],
    ['texto' => 'A marcenaria ficou linda e muito funcional. Cada centimetro do apartamento foi bem aproveitado.', 'autor' => 'Thiago e Paula', 'projeto' => 'Projeto: Sala Integrada'],
    ['texto' => 'Qualidade acima da media e equipe extremamente pontual. Fariamos tudo novamente com eles.', 'autor' => 'Fernanda Costa', 'projeto' => 'Projeto: Lavanderia'],
];

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

$pageTitle = 'Meu Lar Planejados';
$currentPage = 'home';
include __DIR__ . '/includes/header.php';
?>
<main>
    <section id="sobre" class="hero-premium container-fluid px-0">
        <div class="hero-stage row g-0 align-items-stretch">
            <div class="col-12 col-lg-5">
                <div class="hero-left">
                    <h1>SEU ESTILO.<br>SEU ESPACO.<br><span>MEU LAR.</span></h1>
                    <p>Moveis planejados que unem design, funcionalidade e qualidade para transformar ambientes em experiencias unicas.</p>
                    <a class="btn btn-brand btn-lg" href="#contato">Fale com um especialista</a>
                </div>
            </div>
            <div class="col-12 col-lg-7">
                <figure class="hero-right m-0">
                    <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1800&q=80" class="img-fluid w-100" alt="Cozinha planejada em showroom premium com iluminacao aconchegante">
                </figure>
            </div>
        </div>

        <div class="hero-features row g-3 g-xl-4 mx-0">
            <div class="col-6 col-xl-3"><article><strong>Projeto 100% personalizado</strong><p>Do conceito ao encaixe final.</p></article></div>
            <div class="col-6 col-xl-3"><article><strong>Tecnologia e acabamento premium</strong><p>Materia-prima de alta qualidade.</p></article></div>
            <div class="col-6 col-xl-3"><article><strong>Entrega no prazo</strong><p>Montagem com equipe especializada.</p></article></div>
            <div class="col-6 col-xl-3"><article><strong>Garantia de qualidade</strong><p>Atendimento pos-venda dedicado.</p></article></div>
        </div>
    </section>

    <section id="ambientes" class="ambientes-section container-fluid px-3 px-lg-5 py-5">
        <div class="row g-4 align-items-start">
            <div class="col-12 col-lg-4">
                <p class="kicker">Ambientes</p>
                <h2 class="section-title">Solucoes planejadas para cada espaco da <span> sua vida.</span></h2>
                <p class="ambientes-copy">Cada ambiente merece ser unico. Criamos moveis planejados sob medida para tornar seu dia a dia mais pratico, bonito e funcional.</p>
                <a href="ambientes.php" class="btn btn-outline-amber">Ver todos ambientes</a>
            </div>
            <div class="col-12 col-lg-8">
                <div id="ambientesCarousel" class="carousel slide ambientes-carousel" data-bs-ride="carousel" data-bs-interval="5000">
                    <div class="carousel-inner">
                        <?php foreach ($ambienteSlides as $slideIndex => $slide): ?>
                            <div class="carousel-item <?= $slideIndex === 0 ? 'active' : '' ?>">
                                <div class="row g-3">
                                    <?php foreach ($slide as $item): ?>
                                        <div class="col-12 col-sm-6 col-xl-3">
                                            <article class="ambient-card">
                                                <img src="<?= htmlspecialchars($item['imagem']) ?>" alt="Ambiente planejado <?= htmlspecialchars($item['nome']) ?>">
                                                <div class="ambient-overlay">
                                                    <h3><?= htmlspecialchars($item['nome']) ?></h3>
                                                    <a href="contato.php">Orcar</a>
                                                </div>
                                            </article>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#ambientesCarousel" data-bs-slide="prev" aria-label="Slide anterior">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#ambientesCarousel" data-bs-slide="next" aria-label="Proximo slide">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section id="materiais" class="workflow-materials container-fluid px-3 px-lg-5 py-4 py-lg-5">
        <div class="row g-4">
            <div class="col-12 col-lg-6">
                <p class="kicker">Como funciona</p>
                <h2 class="section-title text-light">Do projeto ao seu novo lar, sem complicacao.</h2>
                <div class="steps-grid mt-4">
                    <article><span>01</span><h3>Atendimento</h3><p>Entendemos suas necessidades e estilo.</p></article>
                    <article><span>02</span><h3>Projeto 3D</h3><p>Desenhamos seu projeto personalizado.</p></article>
                    <article><span>03</span><h3>Producao</h3><p>Fabricacao com tecnologia e alta qualidade.</p></article>
                    <article><span>04</span><h3>Entrega e Montagem</h3><p>Instalacao com prazo, cuidado e excelencia.</p></article>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <p class="kicker">Materiais que encantam</p>
                <h2 class="section-title text-light">Acabamentos que fazem a diferenca.</h2>
                <div class="swatches mt-4">
                    <?php foreach ($materiais as $material): ?>
                        <article>
                            <div style="background-color: <?= htmlspecialchars($material['hex']) ?>;" aria-hidden="true"></div>
                            <h3><?= htmlspecialchars($material['nome']) ?></h3>
                            <p><?= htmlspecialchars($material['hex']) ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
                <a href="materiais.php" class="btn btn-outline-amber mt-3">Conhecer materiais</a>
            </div>
        </div>
    </section>

    <section id="projetos" class="projects-section container-fluid px-3 px-lg-5 py-5">
        <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
            <div>
                <p class="kicker">Projetos</p>
                <h2 class="section-title">Inspiracao que transforma.</h2>
            </div>
            <a href="projetos.php" class="projects-link">Ver todos projetos</a>
        </div>
        <div class="row g-3">
            <?php foreach ($projetos as $projeto): ?>
                <div class="col-6 col-md-4 col-xl">
                    <article class="project-thumb">
                        <img src="<?= htmlspecialchars($projeto['imagem']) ?>" alt="Projeto de movel planejado <?= htmlspecialchars($projeto['nome']) ?>">
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="depoimentos" class="testimonials-section container-fluid px-3 px-lg-5 py-5">
        <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
            <div>
                <p class="kicker">Depoimentos</p>
                <h2 class="section-title text-light">Historias reais, <span>resultados que falam.</span></h2>
            </div>
        </div>
        <div id="testimonialsHomeCarousel" class="carousel slide testimonials-carousel" data-bs-ride="carousel" data-bs-interval="5200">
            <div class="carousel-indicators">
                <?php foreach (array_chunk($testimonials, 3) as $slideIndex => $slide): ?>
                    <button type="button" data-bs-target="#testimonialsHomeCarousel" data-bs-slide-to="<?= $slideIndex ?>" class="<?= $slideIndex === 0 ? 'active' : '' ?>" <?= $slideIndex === 0 ? 'aria-current="true"' : '' ?> aria-label="Depoimento <?= $slideIndex + 1 ?>"></button>
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

            <button class="carousel-control-prev" type="button" data-bs-target="#testimonialsHomeCarousel" data-bs-slide="prev" aria-label="Depoimento anterior">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#testimonialsHomeCarousel" data-bs-slide="next" aria-label="Proximo depoimento">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </button>
        </div>
    </section>

    <section class="cta-strip container-fluid px-3 px-lg-5 py-3">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <h2>Vamos planejar seu novo lar?</h2>
            <a target="_blank" rel="noopener noreferrer" class="btn btn-light" href="https://wa.me/5500000000000?text=<?= htmlspecialchars(rawurlencode('Ola! Quero montar meu projeto planejado.')) ?>">Fale pelo WhatsApp</a>
        </div>
    </section>

    <section id="contato" class="contact-section container-fluid px-3 px-lg-5 py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="panel p-4 p-lg-5" <?= $successMessage !== '' ? 'data-contact-success="true"' : '' ?>>
                    <p class="kicker">Atendimento</p>
                    <h2 class="section-title mb-3">Solicite seu projeto e receba um atendimento exclusivo</h2>

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

                    <form id="contact-form" method="post" action="index.php" novalidate>
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
