
- The database is loaded from a SQL snapshot at startup.
- A dedicated **read-only database user** is used.
- No inserts, updates, or deletes are possible.
- Everything runs locally using Docker.

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

The demo UI currently visualises **close prices for equities** using the `Kurser` table, selected by ticker.

---

## Requirements

- Docker
- Docker Compose

No local PHP, MySQL, or Node installation is required.

---

## Running the demo

From the repository root, execute the following commands, and follow instructions from terminal:

```bash
chmod +x run.sh
./run.sh```
