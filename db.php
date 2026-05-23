<?php

declare(strict_types=1);

$availableDrivers = PDO::getAvailableDrivers();

if (!in_array('sqlite', $availableDrivers, true)) {
    throw new RuntimeException(
        'Driver SQLite nao encontrado no PHP. Habilite as extensoes pdo_sqlite e sqlite3 no php.ini antes de executar o projeto.'
    );
}

$pdo = new PDO('sqlite:' . __DIR__ . '/database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$pdo->exec(
    'CREATE TABLE IF NOT EXISTS itens (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT NOT NULL,
        descricao TEXT NOT NULL,
        preco REAL NOT NULL,
        imagem TEXT NOT NULL,
        created_at TEXT DEFAULT CURRENT_TIMESTAMP
    )'
);

$pdo->exec(
    'CREATE TABLE IF NOT EXISTS contatos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT NOT NULL,
        email TEXT NOT NULL,
        mensagem TEXT NOT NULL,
        created_at TEXT DEFAULT CURRENT_TIMESTAMP
    )'
);

$pdo->exec(
    'CREATE TABLE IF NOT EXISTS materiais (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT NOT NULL,
        descricao TEXT NOT NULL,
        cor_hex TEXT NOT NULL,
        imagem TEXT NOT NULL,
        created_at TEXT DEFAULT CURRENT_TIMESTAMP
    )'
);

return $pdo;
