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

The current GUI visualizes **equity close prices** from the `Kurser` table, selected by ticker.

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

No local PHP, MySQL, Node,
