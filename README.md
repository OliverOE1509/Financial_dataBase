Good. Here is a **complete README.md**, including a clear, properly structured section explaining exactly how to use `run.sh` (Unix) and `run.ps1` (Windows). This is one coherent file.

You can copy this as-is.

---

# FinansDB – Read-Only Financial Database Demo

## Overview

This repository contains a fully containerized, read-only demonstration of a financial time-series database built with MariaDB/MySQL and visualized using PHP and Chart.js.

The purpose of this project is to provide verifiable proof of practical experience in:

* Relational database design
* Financial time-series storage
* SQL querying
* Backend API development (PHP + PDO)
* Frontend data visualization (Chart.js)
* Containerized, reproducible environments (Docker)

The database included in this repository is a **static snapshot**.

The production database is separate, secured, and continuously updated.
This demo does **not** expose execution authority or production credentials.

---

## System Architecture

```
Browser
   │
   │  HTTP (JSON)
   ▼
PHP + Apache (Read-only API)
   │
   │  SQL (SELECT only)
   ▼
MariaDB (Snapshot database)
```

* The database is initialized from a SQL snapshot at container startup.
* A dedicated **read-only database user** is used.
* The API allows only SELECT queries.
* All services run locally via Docker.

---

## Database Structure (High-Level)

The database contains multiple financial domains:

### Companies & Equities

* `Selskaper`
* `Kurser` (daily OHLC prices)

### Indices

* `Indekser`
* `Indeks_kurser`

### Foreign Exchange

* `ValutaKurser`

### Commodities

* `Raavare_kurser`

### Portfolios

* `Portfolio_values`
  *(Currently empty — planned for future development.)*

The demo UI visualizes **equity close prices** from the `Kurser` table, selected by ticker.

---

## Features

* Read-only MariaDB snapshot
* Dedicated SELECT-only database user
* PHP backend using PDO
* REST-style API endpoints
* Interactive Chart.js time-series visualization
* Fully reproducible Docker setup
* Cross-platform (Linux, macOS, Windows)

---

## Requirements

* Docker Desktop (Windows/macOS)
  or
* Docker Engine + Docker Compose (Linux)

No local PHP, MySQL, or Node installation is required.

---

# Running the Demo

## 1. Clone the Repository

```bash
git clone https://github.com/OliverOE1509/Financial_dataBase.git
cd Financial_dataBase
```

---

# Start the Application

Choose the instructions that match your operating system.

---

## macOS / Linux / WSL / Git Bash (Unix-based systems)

1. Make the script executable (first time only):

```bash
chmod +x run.sh
```

2. Start the application:

```bash
./run.sh
```

The script will:

* Build Docker images
* Start the database and web server
* Wait until the database is healthy
* Print the URLs to open

Once running, open:

```
http://localhost:8080
```

---

## Windows (PowerShell)

1. Open **PowerShell** inside the project directory.

2. Run:

```powershell
powershell -ExecutionPolicy Bypass -File .\run.ps1
```

The script will:

* Build Docker images
* Start the database and web server
* Wait until the database is healthy
* Print the URLs to open

Once running, open:

```
http://localhost:8080
```

---

# API Endpoints

### List available tickers

```
http://localhost:8080/api.php?action=tickers
```

Returns:

```json
["AAPL", "EQNR", "MSFT", "..."]
```

---

### Retrieve close prices for a ticker

```
http://localhost:8080/api.php?action=close&ticker=EQNR
```

Returns:

```json
[
  { "date": "2021-01-04", "close": 178.32 },
  { "date": "2021-01-05", "close": 181.10 }
]
```

---

# Stopping the Application

To stop the containers:

```bash
docker compose down
```

To completely reset the environment (removes database volume and re-imports the snapshot next time):

```bash
docker compose down -v
```

---

# Notes

* The snapshot database is static and safe to distribute.
* The production database is separate and not included.
* All database access in this demo is read-only.
* The entire system can be reproduced with a single command on any supported OS.

---

If you want, I can now:

* Add a short **Technical Highlights** section tailored to backend roles
* Rewrite the intro to be more finance-focused
* Make a shorter recruiter-facing version
* Or optimize it specifically for trading / quant positions
