<#
.SYNOPSIS
    Script de inicio para GestorEmpleados (sin XAMPP)
.DESCRIPTION
    Inicia el servidor de desarrollo de Laravel usando SQLite.
    No requiere XAMPP, MySQL ni Apache.
#>

Write-Host ""
Write-Host "╔════════════════════════════════════════════════════════════╗" -ForegroundColor Cyan
Write-Host "║           GESTOR DE EMPLEADOS - INICIO RÁPIDO              ║" -ForegroundColor Cyan
Write-Host "╚════════════════════════════════════════════════════════════╝" -ForegroundColor Cyan
Write-Host ""

$ProjectRoot = $PSScriptRoot
Set-Location $ProjectRoot

# 1. Verificar PHP
Write-Host "[1/5] Verificando PHP..." -ForegroundColor Yellow
try {
    $phpVersion = & php -r "echo PHP_VERSION;" 2>$null
    if (-not $phpVersion) { throw "PHP no encontrado" }
    Write-Host "      ✓ PHP $phpVersion detectado" -ForegroundColor Green
} catch {
    Write-Host "      ✗ PHP no está instalado o no está en el PATH." -ForegroundColor Red
    Write-Host ""
    Write-Host "      Por favor instala PHP 8.2+ desde:" -ForegroundColor Yellow
    Write-Host "      https://windows.php.net/download/" -ForegroundColor White
    Write-Host "      o usa: winget install --id PHP.PHP.8.2" -ForegroundColor White
    Write-Host ""
    Read-Host "Presiona Enter para salir"
    exit 1
}

# 2. Verificar Composer
Write-Host "[2/5] Verificando Composer..." -ForegroundColor Yellow
try {
    $composerVersion = & composer --version 2>$null
    Write-Host "      ✓ $composerVersion" -ForegroundColor Green
} catch {
    Write-Host "      ✗ Composer no está instalado." -ForegroundColor Red
    Write-Host "      Descárgalo desde: https://getcomposer.org/download/" -ForegroundColor White
    Read-Host "Presiona Enter para salir"
    exit 1
}

# 3. Instalar dependencias si es necesario
if (-not (Test-Path "vendor")) {
    Write-Host "[3/5] Instalando dependencias de PHP (composer install)..." -ForegroundColor Yellow
    & composer install --no-interaction --prefer-dist
    if ($LASTEXITCODE -ne 0) {
        Write-Host "      ✗ Error durante composer install" -ForegroundColor Red
        Read-Host "Presiona Enter para salir"
        exit 1
    }
    Write-Host "      ✓ Dependencias instaladas" -ForegroundColor Green
} else {
    Write-Host "[3/5] Dependencias PHP ya instaladas (vendor existe)" -ForegroundColor Green
}

# 4. Ejecutar migraciones
Write-Host "[4/5] Ejecutando migraciones de base de datos..." -ForegroundColor Yellow
& php artisan migrate --force
if ($LASTEXITCODE -ne 0) {
    Write-Host "      ✗ Error en las migraciones" -ForegroundColor Red
    Read-Host "Presiona Enter para salir"
    exit 1
}
Write-Host "      ✓ Base de datos lista (SQLite)" -ForegroundColor Green

# 5. Preguntar por datos de prueba
Write-Host ""
$seed = Read-Host "[5/5] ¿Deseas cargar datos de prueba? (S/n)"
if ($seed -match '^[Ss]') {
    Write-Host "      Cargando datos de prueba..." -ForegroundColor Yellow
    & php artisan db:seed --force
    Write-Host "      ✓ Datos de prueba insertados" -ForegroundColor Green
}

Write-Host ""
Write-Host "╔════════════════════════════════════════════════════════════╗" -ForegroundColor Green
Write-Host "║  ¡Servidor iniciado!                                       ║" -ForegroundColor Green
Write-Host "║  Abre en tu navegador: http://localhost:8000               ║" -ForegroundColor Green
Write-Host "╚════════════════════════════════════════════════════════════╝" -ForegroundColor Green
Write-Host ""
Write-Host "Presiona Ctrl+C para detener el servidor." -ForegroundColor Yellow
Write-Host ""

# Iniciar el servidor de Laravel
& php artisan serve --host=127.0.0.1 --port=8000
