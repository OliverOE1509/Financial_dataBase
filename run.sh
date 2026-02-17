#!/usr/bin/env bash
set -euo pipefail

echo "=== FinansDB demo launcher ==="

command -v docker >/dev/null 2>&1 || { echo "ERROR: Docker is not installed."; exit 1; }
docker compose version >/dev/null 2>&1 || { echo "ERROR: docker compose is not available."; exit 1; }

if [ ! -f .env ]; then
  if [ -f .env.example ]; then
    echo "No .env found. Creating one from .env.example"
    cp .env.example .env
  else
    echo "ERROR: .env or .env.example missing."
    exit 1
  fi
fi

echo "Starting containers..."
docker compose up -d --build

echo "Waiting for database to become healthy..."
DB_ID="$(docker compose ps -q db)"
if [ -z "$DB_ID" ]; then
  echo "ERROR: db service not found."
  exit 1
fi

for i in $(seq 1 60); do
  DB_STATUS="$(docker inspect --format='{{.State.Health.Status}}' "$DB_ID" 2>/dev/null || true)"
  if [ "$DB_STATUS" = "healthy" ]; then
    echo "Database is healthy."
    break
  fi
  sleep 1
done

if [ "${DB_STATUS:-}" != "healthy" ]; then
  echo "ERROR: Database did not become healthy. See logs:"
  docker compose logs --tail=200 db
  exit 1
fi

echo ""
echo "=== Demo is running ==="
echo ""
echo "Web UI:"
echo "  http://localhost:8080"
echo ""
echo "API (tickers):"
echo "  http://localhost:8080/api.php?action=tickers"
echo ""
echo "API (close example):"
echo "  http://localhost:8080/api.php?action=close&ticker=EQNR"
echo ""
echo "Stop:"
echo "  docker compose down"
