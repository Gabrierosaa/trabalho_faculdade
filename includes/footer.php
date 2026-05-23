<?php $currentPage = $currentPage ?? ''; ?>
<?php if (!in_array($currentPage, ['admin', 'login'], true)): ?>
    <footer class="site-footer storefront-footer pt-4 pb-3">
        <div class="container-fluid px-3 px-lg-5">
            <div class="row g-4">
                <div class="col-12 col-lg-4">
                    <div class="brand-inline mb-3">
                        <span class="brand-mark" aria-hidden="true">M</span>
                        <span class="brand-copy">
                            <strong>MEU LAR</strong>
                            <small>PLANEJADOS</small>
                        </span>
                    </div>
                    <p class="footer-muted mb-2">Transformamos ambientes em experiencias com marcenaria premium, design e funcionalidade.</p>
                </div>
                <div class="col-6 col-lg-2">
                    <h3 class="footer-title">Navegacao</h3>
                    <ul class="footer-list">
                        <li><a href="index.php">Inicio</a></li>
                        <li><a href="ambientes.php">Ambientes</a></li>
                        <li><a href="materiais.php">Materiais</a></li>
                        <li><a href="projetos.php">Projetos</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-3">
                    <h3 class="footer-title">Contato</h3>
                    <ul class="footer-list">
                        <li>(11) 99999-9999</li>
                        <li>contato@meular.com.br</li>
                        <li>Rua Exemplo, 123 - Sao Paulo</li>
                    </ul>
                </div>
                <div class="col-12 col-lg-3">
                    <h3 class="footer-title">Painel</h3>
                    <a href="login.php" class="btn btn-sm btn-outline-brand">Entrar no admin</a>
                </div>
            </div>
            <p class="footer-copy mb-0 mt-4">Meu Lar Planejados - Portifolio academico de startup.</p>
        </div>
    </footer>
<?php else: ?>
    <footer class="site-footer py-4">
        <div class="container-fluid px-3 px-lg-4">
            <p class="m-0">Meu Lar Planejados - Portifolio academico de startup.</p>
        </div>
    </footer>
<?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="scripts/app.js"></script>
</body>
</html>
