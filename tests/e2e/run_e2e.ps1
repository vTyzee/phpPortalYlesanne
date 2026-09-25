$ErrorActionPreference = "Stop"

Write-Host "ÕpiEestis Selenium E2E" -ForegroundColor Green
Write-Host "1) Kontrollin Pythonit..."

$python = $null
if (Get-Command py -ErrorAction SilentlyContinue) {
    $python = "py"
} elseif (Get-Command python -ErrorAction SilentlyContinue) {
    $python = "python"
} else {
    throw "Pythonit ei leitud. Paigalda Python 3 ja lisa see PATH-i."
}

Write-Host "2) Paigaldan/uuendan Seleniumi..."
& $python -m pip install -r "$PSScriptRoot\requirements.txt"

if (-not $env:E2E_BASE_URL) { $env:E2E_BASE_URL = "http://localhost/opi_eestis" }
if (-not $env:E2E_BROWSER) { $env:E2E_BROWSER = "edge" }

Write-Host "3) Käivitan UI E2E-testid: $env:E2E_BASE_URL ($env:E2E_BROWSER)"
& $python -m unittest discover -s "$PSScriptRoot" -p "test_*.py" -v
