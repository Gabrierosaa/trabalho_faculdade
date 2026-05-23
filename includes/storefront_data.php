<?php

declare(strict_types=1);

/**
 * @return array{
 *   itens: array<int, array<string, mixed>>,
 *   ambientes: array<int, array<string, mixed>>,
 *   ambienteSlides: array<int, array<int, array<string, mixed>>>,
 *   projetos: array<int, array<string, mixed>>,
 *   materiaisPreview: array<int, array<string, string>>
 * }
 */
function loadStorefrontData(PDO $pdo): array
{
    $countStmt = $pdo->prepare('SELECT COUNT(*) AS total FROM itens');
    $countStmt->execute();
    $total = (int) ($countStmt->fetch()['total'] ?? 0);

    if ($total === 0) {
        $seed = $pdo->prepare('INSERT INTO itens (nome, descricao, preco, imagem) VALUES (:nome, :descricao, :preco, :imagem)');
        $defaultItems = [
            ['Sala Planejada', 'Painel e marcenaria moderna para sala de estar.', 18990.00, 'https://images.unsplash.com/photo-1616486029423-aaa4789e8c9a?auto=format&fit=crop&w=800&q=80'],
            ['Cozinha Completa', 'Projeto de cozinha com alto aproveitamento de espaco.', 25900.00, 'https://images.unsplash.com/photo-1556911220-bff31c812dba?auto=format&fit=crop&w=800&q=80'],
            ['Dormitorio Premium', 'Guarda-roupa, cabeceira e nichos sob medida.', 14990.00, 'https://images.unsplash.com/photo-1616594039964-3cbe6f4f9cf8?auto=format&fit=crop&w=800&q=80'],
        ];

        foreach ($defaultItems as $item) {
            $seed->execute([
                ':nome' => $item[0],
                ':descricao' => $item[1],
                ':preco' => $item[2],
                ':imagem' => $item[3],
            ]);
        }
    }

    $itensStmt = $pdo->prepare('SELECT id, nome, descricao, preco, imagem FROM itens ORDER BY id DESC');
    $itensStmt->execute();
    $itens = $itensStmt->fetchAll();

    $fallbackGallery = [
        ['id' => 0, 'nome' => 'Sala', 'descricao' => 'Ambiente social com marcenaria sob medida.', 'preco' => 12990.00, 'imagem' => 'https://images.unsplash.com/photo-1616486029423-aaa4789e8c9a?auto=format&fit=crop&w=900&q=80'],
        ['id' => 0, 'nome' => 'Quarto', 'descricao' => 'Dormitorio com painel e guarda-roupa planejado.', 'preco' => 14990.00, 'imagem' => 'https://images.unsplash.com/photo-1616594039964-3cbe6f4f9cf8?auto=format&fit=crop&w=900&q=80'],
        ['id' => 0, 'nome' => 'Cozinha', 'descricao' => 'Cozinha integrada com acabamento premium.', 'preco' => 25900.00, 'imagem' => 'https://images.unsplash.com/photo-1556911220-bff31c812dba?auto=format&fit=crop&w=900&q=80'],
        ['id' => 0, 'nome' => 'Escritorio', 'descricao' => 'Home office com nichos funcionais.', 'preco' => 9990.00, 'imagem' => 'https://images.unsplash.com/photo-1615874959474-d609969a20ed?auto=format&fit=crop&w=900&q=80'],
        ['id' => 0, 'nome' => 'Closet', 'descricao' => 'Closet de alto aproveitamento com portas espelhadas.', 'preco' => 16990.00, 'imagem' => 'https://images.unsplash.com/photo-1616486701534-5f5f4f12f3f6?auto=format&fit=crop&w=900&q=80'],
        ['id' => 0, 'nome' => 'Lavanderia', 'descricao' => 'Lavanderia compacta com organizacao inteligente.', 'preco' => 7890.00, 'imagem' => 'https://images.unsplash.com/photo-1582738412124-ea6f0d3221be?auto=format&fit=crop&w=900&q=80'],
    ];

    $catalog = $itens;
    foreach ($fallbackGallery as $fallback) {
        if (count($catalog) >= 8) {
            break;
        }
        $catalog[] = $fallback;
    }

    $ambientes = array_slice($catalog, 0, 8);
    $ambienteSlides = array_chunk($ambientes, 4);

    $projetos = array_slice($catalog, 0, 5);
    while (count($projetos) < 5) {
        $projetos[] = $fallbackGallery[count($projetos) % count($fallbackGallery)];
    }

    $materiaisPreview = [
        ['nome' => 'Cinza Grafite', 'hex' => '#1E1F1E'],
        ['nome' => 'Carvalho Natural', 'hex' => '#D9B17C'],
        ['nome' => 'Off White', 'hex' => '#F2F0EC'],
        ['nome' => 'Freijo Puro', 'hex' => '#8A5A3B'],
        ['nome' => 'Verde Oliva', 'hex' => '#4A5B43'],
    ];

    return [
        'itens' => $itens,
        'ambientes' => $ambientes,
        'ambienteSlides' => $ambienteSlides,
        'projetos' => $projetos,
        'materiaisPreview' => $materiaisPreview,
    ];
}
