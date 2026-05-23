# Meu Lar Planejados (PHP + SQLite)

Projeto academico de portfolio para loja de moveis planejados.

## Requisitos

- PHP 8.1+
- Extensoes PHP: `pdo_sqlite` e `sqlite3`

## Como rodar (Windows, macOS, Linux)

1. Clone o repositorio e abra a pasta do projeto.
2. Verifique o PHP:

```bash
php -v
```

3. Inicie o servidor local:

```bash
php -S localhost:8000
```

4. Abra no navegador:

- http://localhost:8000/index.php

## Se SQLite nao estiver habilitado

Voce pode tentar iniciar usando o `php.ini` local do projeto:

```bash
php -c . -S localhost:8000
```

Este `php.ini` ja inclui:

- `extension=pdo_sqlite`
- `extension=sqlite3`

## Scripts de inicializacao (Windows)

- PowerShell:

```powershell
./scripts/start.ps1
```

- CMD:

```bat
scripts\start.bat
```

## Banco de dados

- O arquivo `database.sqlite` fica na raiz do projeto.
- As tabelas sao criadas automaticamente no primeiro acesso.

## Login do painel admin

- Usuario: `admin`
- Senha: `startup123`

## Estrutura principal

- `index.php`: home
- `admin.php`: painel de administracao
- `db.php`: conexao e bootstrap do banco SQLite
- `styles/style.css`: estilos globais
- `scripts/app.js`: comportamento do frontend
