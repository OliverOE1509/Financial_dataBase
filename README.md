
- The database is loaded from a SQL snapshot at startup.
- A dedicated **read-only database user** is used.
- No inserts, updates, or deletes are possible.
- Everything runs locally using Docker.

---

The intended purpose of this repo, is to give a minimal, and easy to use GUI for my MySQL database that collects data on financial markets. This is made to give a proof of work, without putting execution authority for my database at risk. This is only a snapshot of the database, whereas the database stored on phpmyadmin is secure and updating itself every day, with the newest data available.

The target audience for this repo is potential employers of Oliver Ekeberg. 

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
 
As well as 
- **Portfolios**
  - `Portfolio_values`

But this is empty as of 8th of February, will come later.

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
git clone https://github.com/OliverOE1509/Financial_dataBase.git
cd Financial_dataBase
chmod +x run.sh
./run.sh

