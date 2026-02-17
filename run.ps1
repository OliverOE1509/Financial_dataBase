$ErrorActionPreference = "Stop"

Write-Host "=== FinansDB demo launcher (Windows) ==="

if (-not (Get-Command docker -ErrorAction SilentlyContinue)) {
  Write-Host "ERROR: Docker is not installed."
  exit 1
}

docker compose version | Out-Null

if (-not (Test-Path ".env")) {
  if (Test-Path ".env.example") {
    Write-Host "No .env found. Creating one from .env.example"
    Copy-Item ".env.example" ".env"
  } else {
    Write-Host "ERROR: .env or .env.example missing."
    exit 1
  }
}

Write-Host "Starting containers..."
docker compose up -d --build | Out-Null

Write-Host "Waiting for database to become healthy..."
$dbId = (docker compose ps -q db).Trim()
if ([string]::IsNullOrWhiteSpace($dbId)) {
  Write-Host "ERROR: db service not found."
  exit 1
}

$dbStatus = ""
for ($i=0; $i -lt 60; $i++) {
  try {
    $dbStatus = (docker inspect --format='{{.State.Health.Status}}' $dbId).Trim()
  } catch {
    $dbStatus = ""
  }
  if ($dbStatus -eq "healthy") {
    Write-Host "Database is healthy."
    break
  }
  Start-Sleep -Seconds 1
}

if ($dbStatus -ne "healthy") {
  Write-Host "ERROR: Database did not become healthy. Recent logs:"
  docker compose logs --tail=200 db
  exit 1
}

Write-Host ""
Write-Host "=== Demo is running ==="
Write-Host ""
Write-Host "Web UI:"
Write-Host "  http://localhost:8080"
Write-Host ""
Write-Host "API (tickers):"
Write-Host "  http://localhost:8080/api.php?action=tickers"
Write-Host ""
Write-Host "API (close example):"
Write-Host "  http://localhost:8080/api.php?action=close&ticker=EQNR"
Write-Host ""
Write-Host "Stop:"
Write-Host "  docker compose down"
