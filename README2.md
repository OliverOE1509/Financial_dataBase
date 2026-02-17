# FinansDB – Read-only Financial Database Demo

- The database is loaded from a SQL snapshot at startup.
- A dedicated **read-only database user** is used.
- No inserts, updates, or deletes are possible from the demo.
- Everything runs locally using Docker.

This repository provides a minimal GUI for a MariaDB/MySQL financial database snapshot. The purpose is to present **proof of work** (schema design, time-series storage, querying, and visualisation) without exposing execution authority or production credentials.

The snapshot in this repo is static. My production database is separate and continuously updated.

Target audience: potential employers of Oliver Ekeberg.

---

## Database structure (high level)

The database contains multiple logical domains:

- **Companies & equities**
  - `Selskaper`
  - `Kurser` (daily OHLC prices)

- **Indices**
  - `Indekser`
  - `Indeks_kurser`

- **Foreign exchange**
  - `ValutaKurser`

- **Commodities**
  - `Raavare_kurser`

- **Portfolios**
  - `Portfolio_values` (currently empty; will be populated later)

The demo UI visualises **close prices for equities** using the `Kurser` table, selected by ticker.

---

## Requirements

- Docker Desktop (Windows/macOS) or Docker Engine (Linux)
- Docker Compose (included with Docker Desktop; also available on Linux)

No local PHP, MySQL, or Node installation is required.

---

## Running the demo

### 1) Clone the repository

```bash
git clone https://github.com/OliverOE1509/Financial_dataBase.git
cd Financial_dataBase

### 2) Choose your OS

#### Unix based (macOS, Linux)
```bash
chmod +x run.sh
./run.sh

#### Windows (powershell)
Open powershell on your windows machine and type
```bash
powershell -ExecutionPolicy Bypass -File .\run.ps1



## Stopping the container
When you are done checking my awesome work I have done, you can do a clean exit with the following command

```bash
docker compose down -v


