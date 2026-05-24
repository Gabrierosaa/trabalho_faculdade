param(
    [switch]$StartServer,
    [int]$Port = 8000
)

$ErrorActionPreference = "Stop"

$scriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$projectRoot = Resolve-Path (Join-Path $scriptDir "..")
$projectPhpIni = Join-Path $projectRoot "php.ini"

function Get-PhpExecutable {
    $phpCmd = Get-Command php -ErrorAction SilentlyContinue
    if ($phpCmd) {
        return $phpCmd.Source
    }

    $wingetPhpRoot = Join-Path $env:LOCALAPPDATA "Microsoft\WinGet\Packages"
    $candidate = Get-ChildItem -Path $wingetPhpRoot -Directory -Filter "PHP.PHP.8.3*" -ErrorAction SilentlyContinue |
        Sort-Object LastWriteTime -Descending |
        Select-Object -First 1

    if ($candidate) {
        $phpExe = Join-Path $candidate.FullName "php.exe"
        if (Test-Path $phpExe) {
            $env:PATH = "$($candidate.FullName);$env:PATH"
            return $phpExe
        }
    }

    return $null
}

Write-Host "Verificando instalacao do PHP..."
$phpExe = Get-PhpExecutable

if (-not $phpExe) {
    $wingetCmd = Get-Command winget -ErrorAction SilentlyContinue
    if (-not $wingetCmd) {
        Write-Error "Winget nao encontrado. Instale o winget e rode novamente este script."
        exit 1
    }

    Write-Host "PHP nao encontrado. Instalando PHP 8.3 via winget..."
    winget install --id PHP.PHP.8.3 --source winget --accept-package-agreements --accept-source-agreements

    $phpExe = Get-PhpExecutable
    if (-not $phpExe) {
        Write-Error "Nao foi possivel localizar o php.exe apos a instalacao. Feche e abra o terminal, depois rode novamente."
        exit 1
    }
}

$phpDir = Split-Path -Parent $phpExe
$phpExtDir = Join-Path $phpDir "ext"

if (-not (Test-Path $phpExtDir)) {
    Write-Error "Diretorio de extensoes nao encontrado: $phpExtDir"
    exit 1
}

if (-not (Test-Path $projectPhpIni)) {
    Write-Error "php.ini do projeto nao encontrado em: $projectPhpIni"
    exit 1
}

Write-Host "Validando extensoes SQLite (pdo_sqlite e sqlite3)..."
$moduleList = & $phpExe -c $projectRoot -d "extension_dir=$phpExtDir" -m |
    ForEach-Object { $_.ToString().Trim() } |
    Where-Object { $_ -and $_ -notmatch "^\[.*\]$" }

if ($moduleList -notcontains "pdo_sqlite") {
    Write-Error "Extensao pdo_sqlite nao carregou."
    exit 1
}

if ($moduleList -notcontains "sqlite3") {
    Write-Error "Extensao sqlite3 nao carregou."
    exit 1
}

Write-Host "Bootstrap do banco de dados..."
& $phpExe -c $projectRoot -d "extension_dir=$phpExtDir" -r "require '$((Join-Path $projectRoot 'db.php').Replace('\\', '/'))'; echo 'OK';" | Out-Null

Write-Host "Instalacao concluida com sucesso."
Write-Host "Para iniciar o projeto:"
Write-Host "  .\scripts\start.ps1 -UseLocalPhpIni"

if ($StartServer) {
    Write-Host "Iniciando servidor local em http://localhost:$Port/ ..."
    & (Join-Path $scriptDir "start.ps1") -Port $Port -UseLocalPhpIni
}
