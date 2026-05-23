@echo off
set PORT=%1
if "%PORT%"=="" set PORT=8000

echo Iniciando servidor em http://localhost:%PORT%/index.php
echo Pressione Ctrl+C para parar.
php -S localhost:%PORT%
