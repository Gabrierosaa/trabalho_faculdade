param(
    [int]$Port = 8000,
    [switch]$UseLocalPhpIni
)

$phpCheck = Get-Command php -ErrorAction SilentlyContinue
if (-not $phpCheck) {
    Write-Error "PHP nao encontrado no PATH. Instale o PHP 8.1+ e tente novamente."
    exit 1
}

$url = "http://localhost:$Port/index.php"
Write-Host "Iniciando servidor em $url"
Write-Host "Pressione Ctrl+C para parar."

if ($UseLocalPhpIni) {
    php -c . -S "localhost:$Port"
} else {
    php -S "localhost:$Port"
}
