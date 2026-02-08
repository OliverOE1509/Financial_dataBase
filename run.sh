#!/usr/bin/env bash
set -e

echo "=== FinansDB demo launcher ==="

# 1) Check Docker
if ! command -v docker >/dev/null 2>&1; then
  echo "ERROR: Docker is not installed."
  exit 1
fi

if ! docker compose version >/dev/null 2>&1; then
  echo "ERROR: docker compose is not available."
  exit 1
fi

# 2) Ensure .env exists
if [ ! -f .env ]; then
  if [ -f .env.example ]; then
    echo "No .env found. Creating one from .env.example"
    cp .env.example .env
  else
    echo "ERROR: .env or .env.example missing."
    exit 1
  fi
fi

# 3) Start containers
echo "Starting containers..."
docker compose up -d --build

# 4) Wait for DB health
echo "Waiting for database to become healthy..."
for i in {1..30}; do
  DB_STATUS=$(docker inspect --format='{{.State.Health.Status}}' financial_database-db-1 2>/dev/null || true)
  if [ "$DB_STATUS" = "healthy" ]; then
    echo "Database is healthy."
    break
  fi
  sleep 1
done

if [ "$DB_STATUS" != "healthy" ]; then
  echo "ERROR: Database did not become healthy."
  exit 1
fi

# 5) Check web container
if ! docker compose ps web | grep -q "Up"; then
  echo "ERROR: Web container is not running."
  exit 1
fi

# 6) Final instructions
echo ""
echo "=== Demo is running ==="
echo ""
echo "Open the following links in your browser:"
echo ""
echo "• Web UI (Chart.js):"
echo "  http://localhost:8080"
echo ""
echo "• API – list of tickers:"
echo "  http://localhost:8080/api.php?action=tickers"
echo ""
echo "• API – close prices example:"
echo "  http://localhost:8080/api.php?action=close&ticker=EQNR"
echo ""
echo "To stop everything:"
echo "  docker compose down"
echo ""
echo "======================="
