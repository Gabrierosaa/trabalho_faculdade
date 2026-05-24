param(
    [int]$Port = 8000,
    [switch]$UseLocalPhpIni
)

$phpCheck = Get-Command php -ErrorAction SilentlyContinue
if (-not $phpCheck) {
    Write-Error "PHP nao encontrado no PATH. Instale o PHP 8.1+ e tente novamente."
    exit 1
}

$phpDir = Split-Path -Parent $phpCheck.Source
$phpExtDir = Join-Path $phpDir "ext"

if (-not (Test-Path $phpExtDir)) {
    Write-Error "Diretorio de extensoes nao encontrado: $phpExtDir"
    exit 1
}

$scriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$projectRoot = Resolve-Path (Join-Path $scriptDir "..")

$url = "http://localhost:$Port/"
Write-Host "Iniciando servidor em $url"
Write-Host "Pressione Ctrl+C para parar."

Push-Location $projectRoot

if ($UseLocalPhpIni) {
    php -c . -d "extension_dir=$phpExtDir" -S "localhost:$Port" -t .
} else {
    php -S "localhost:$Port" -t .
}

Pop-Location
