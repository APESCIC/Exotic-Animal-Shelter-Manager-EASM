# Prefer Laragon PHP and Composer on the PATH for this shell session.
# Usage (from repo root):
#   . .\scripts\local\use-laragon.ps1

$ErrorActionPreference = 'Stop'

$laragonRoot = if ($env:LARAGON_ROOT) { $env:LARAGON_ROOT } else { 'C:\laragon' }

if (-not (Test-Path -LiteralPath $laragonRoot)) {
    Write-Error "Laragon not found at $laragonRoot. Set LARAGON_ROOT or install Laragon."
}

$phpDirs = @(Get-ChildItem -LiteralPath (Join-Path $laragonRoot 'bin\php') -Directory -ErrorAction SilentlyContinue |
    Sort-Object Name -Descending)

if (-not $phpDirs -or $phpDirs.Count -eq 0) {
    Write-Error "No PHP under $laragonRoot\bin\php"
}

$phpBin = $phpDirs[0].FullName
$composerBin = Join-Path $laragonRoot 'bin\composer'

$prepend = @($phpBin)
if (Test-Path -LiteralPath $composerBin) {
    $prepend += $composerBin
}

$env:Path = ($prepend -join ';') + ';' + $env:Path

$phpExe = Join-Path $phpBin 'php.exe'
Write-Host "Laragon PHP: $(& $phpExe -r 'echo PHP_VERSION;')"
Write-Host "Preview: http://easm.test (start Laragon Apache + MySQL first)"
