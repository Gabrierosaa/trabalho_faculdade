@echo off
set PORT=%1
if "%PORT%"=="" set PORT=8000

set "PHP_EXE="
for /f "delims=" %%i in ('where php 2^>nul') do (
	set "PHP_EXE=%%i"
	goto :php_found
)

echo PHP nao encontrado no PATH. Instale o PHP 8.1+ e tente novamente.
exit /b 1

:php_found
for %%i in ("%PHP_EXE%") do set "PHP_DIR=%%~dpi"
set "PHP_EXT_DIR=%PHP_DIR%ext"

if not exist "%PHP_EXT_DIR%" (
	echo Diretorio de extensoes nao encontrado: %PHP_EXT_DIR%
	exit /b 1
)

set "SCRIPT_DIR=%~dp0"
pushd "%SCRIPT_DIR%.."

echo Iniciando servidor em http://localhost:%PORT%/
echo Pressione Ctrl+C para parar.
php -c . -d extension_dir="%PHP_EXT_DIR%" -S localhost:%PORT% -t .
popd
