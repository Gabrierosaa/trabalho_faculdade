const contactForm = document.querySelector('#contact-form');

const imageFallbackSvg = encodeURIComponent(
    '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 800">'
    + '<defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1">'
    + '<stop offset="0%" stop-color="#262a33"/><stop offset="100%" stop-color="#111319"/>'
    + '</linearGradient></defs>'
    + '<rect width="1200" height="800" fill="url(#g)"/>'
    + '<g fill="#d59b63" font-family="Arial, sans-serif" text-anchor="middle">'
    + '<text x="600" y="380" font-size="58" font-weight="700">Imagem indisponivel</text>'
    + '<text x="600" y="440" font-size="28" fill="#c2cad7">Verifique o link no painel administrativo</text>'
    + '</g>'
    + '</svg>'
);

const imageFallbackDataUri = `data:image/svg+xml;charset=UTF-8,${imageFallbackSvg}`;

const applyImageFallback = (img) => {
    if (img.dataset.fallbackApplied === 'true') {
        return;
    }

    img.dataset.fallbackApplied = 'true';
    img.src = imageFallbackDataUri;
};

const ensureImageVisible = (img) => {
    if (!(img instanceof HTMLImageElement)) {
        return;
    }

    const source = (img.getAttribute('src') || '').trim();

    if (source === '' || source.toLowerCase() === 'null' || source.toLowerCase() === 'undefined') {
        applyImageFallback(img);
        return;
    }

    img.addEventListener('error', () => {
        applyImageFallback(img);
    });

    if (img.complete && img.naturalWidth === 0) {
        applyImageFallback(img);
    }
};

document.querySelectorAll('img').forEach(ensureImageVisible);

const bindAdminImagePreviews = () => {
    const previewInputs = document.querySelectorAll('[data-image-preview-target]');

    previewInputs.forEach((input) => {
        if (!(input instanceof HTMLInputElement)) {
            return;
        }

        const targetSelector = input.dataset.imagePreviewTarget;
        if (!targetSelector) {
            return;
        }

        const previewImage = document.querySelector(targetSelector);
        if (!(previewImage instanceof HTMLImageElement)) {
            return;
        }

        const syncPreview = () => {
            const source = input.value.trim();

            if (source === '') {
                applyImageFallback(previewImage);
                return;
            }

            previewImage.dataset.fallbackApplied = 'false';
            previewImage.src = source;
        };

        input.addEventListener('input', syncPreview);
        syncPreview();
    });
};

const bindAdminTableFilters = () => {
    const searchFields = document.querySelectorAll('[data-table-filter][data-table-target]');

    searchFields.forEach((field) => {
        if (!(field instanceof HTMLInputElement)) {
            return;
        }

        const rowsSelector = field.dataset.tableTarget;
        if (!rowsSelector) {
            return;
        }

        const rows = () => Array.from(document.querySelectorAll(rowsSelector));

        const applyFilter = () => {
            const term = field.value.trim().toLowerCase();

            rows().forEach((row) => {
                if (!(row instanceof HTMLTableRowElement)) {
                    return;
                }

                const haystack = (row.textContent || '').toLowerCase();
                row.style.display = haystack.includes(term) ? '' : 'none';
            });
        };

        field.addEventListener('input', applyFilter);
        applyFilter();
    });
};

bindAdminImagePreviews();
bindAdminTableFilters();

if (contactForm) {
    contactForm.addEventListener('submit', (event) => {
        const nameInput = contactForm.querySelector('#nome');
        const emailInput = contactForm.querySelector('#email');
        const messageInput = contactForm.querySelector('#mensagem');

        const fields = [nameInput, emailInput, messageInput];
        const emptyField = fields.find((field) => !field.value.trim());
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        fields.forEach((field) => field.classList.remove('is-invalid'));

        if (emptyField) {
            event.preventDefault();
            emptyField.classList.add('is-invalid');
            emptyField.focus();
            alert('Preencha todos os campos antes de enviar.');
            return;
        }

        if (!emailPattern.test(emailInput.value.trim())) {
            event.preventDefault();
            emailInput.classList.add('is-invalid');
            emailInput.focus();
            alert('Digite um e-mail valido.');
        }
    });
}

const successFlag = document.querySelector('[data-contact-success="true"]');
if (successFlag) {
    alert('Contato enviado com sucesso!');
}
