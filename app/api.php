<?php
declare(strict_types=1);

/**
 * Read-only API for FinansDB (MariaDB/MySQL) used by Chart.js frontend.
 *
 * Endpoints:
 *   /api.php?action=tickers
 *   /api.php?action=close&ticker=EQNR
 *
 * Uses table: Kurser
 * Columns:
 *   - Ticker   (varchar)
 *   - Dato     (date)
 *   - Close_   (float)
 */

header('Content-Type: application/json; charset=utf-8');

$host = getenv('DB_HOST') ?: 'db';
$db   = getenv('DB_NAME') ?: 'FinansDB';
$user = getenv('DB_USER') ?: 'readonly';
$pass = getenv('DB_PASS') ?: 'readonly';

$action = $_GET['action'] ?? '';

function bad_request(string $msg): void {
  http_response_code(400);
  echo json_encode(['error' => $msg], JSON_UNESCAPED_UNICODE);
  exit;
}

function server_error(string $msg = 'Server error'): void {
  http_response_code(500);
  echo json_encode(['error' => $msg], JSON_UNESCAPED_UNICODE);
  exit;
}

try {
  // MariaDB/MySQL PDO
  $pdo = new PDO(
    "mysql:host={$host};dbname={$db};charset=utf8mb4",
    $user,
    $pass,
    [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
    ]
  );
} catch (Throwable $e) {
  server_error('DB connection failed');
}

$table    = 'Kurser';
$colDate  = 'Dato';
$colTick  = 'Ticker';
$colClose = 'Close_';

if ($action === 'tickers') {
  try {
    $sql = "SELECT DISTINCT `$colTick` AS ticker
            FROM `$table`
            WHERE `$colTick` IS NOT NULL AND `$colTick` <> ''
            ORDER BY `$colTick` ASC";
    $rows = $pdo->query($sql)->fetchAll();
    $out = array_map(static fn(array $r) => $r['ticker'], $rows);
    echo json_encode($out, JSON_UNESCAPED_UNICODE);
    exit;
  } catch (Throwable $e) {
    server_error('Failed to list tickers');
  }
}

if ($action === 'close') {
  $ticker = $_GET['ticker'] ?? '';

  // Allow common ticker characters: letters, numbers, dot, dash, underscore
  if (!preg_match('/^[A-Za-z0-9._-]{1,32}$/', $ticker)) {
    bad_request('Invalid ticker');
  }

  // Optional: limit range (can be extended later)
  $limit = 5000; // safety cap for demo; large enough for years of daily data

  try {
    $sql = "SELECT `$colDate` AS date, `$colClose` AS close
            FROM `$table`
            WHERE `$colTick` = :t
            ORDER BY `$colDate` ASC
            LIMIT {$limit}";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':t' => $ticker]);
    $rows = $stmt->fetchAll();

    // Normalize types for JSON (avoid strings for numbers if possible)
    $out = [];
    foreach ($rows as $r) {
      $out[] = [
        'date'  => $r['date'],                      // YYYY-MM-DD
        'close' => isset($r['close']) ? (float)$r['close'] : null,
      ];
    }

    echo json_encode($out, JSON_UNESCAPED_UNICODE);
    exit;
  } catch (Throwable $e) {
    server_error('Failed to fetch close prices');
  }
}

bad_request('Unknown action');
