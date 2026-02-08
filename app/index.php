<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>FinansDB – Close Price Viewer</title>
  <style>
    body { font-family: system-ui, Arial; margin: 24px; }
    .row { display: flex; gap: 12px; align-items: center; flex-wrap: wrap; }
    #chartWrap { max-width: 1100px; }
    canvas { width: 100%; height: 420px; }
    select, button { padding: 8px 10px; }
    .muted { color: #666; font-size: 14px; }
  </style>
</head>
<body>
  <h1>FinansDB – Close Prices</h1>
  <p class="muted">Read-only demo (snapshot DB). Select a symbol to plot close prices.</p>

  <div class="row">
    <label for="symbol">Ticker:</label>
    <select id="symbol"></select>
    <button id="reload">Reload</button>
    <span class="muted" id="status"></span>
  </div>

  <div id="chartWrap">
    <canvas id="closeChart"></canvas>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const tickerSel = document.getElementById('symbol');
    const statusEl = document.getElementById('status');
    const reloadBtn = document.getElementById('reload');
    let chart;

    async function fetchJSON(url) {
        const res = await fetch(url);
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        return res.json();
    }

    async function loadTickers() {
        statusEl.textContent = 'Loading tickers...';
        const tickers = await fetchJSON('/api.php?action=tickers');

        tickerSel.innerHTML = '';
        for (const t of tickers) {
        const opt = document.createElement('option');
        opt.value = t;
        opt.textContent = t;
        tickerSel.appendChild(opt);
        }
        statusEl.textContent = '';
    }

    async function loadClose(ticker) {
        statusEl.textContent = `Loading ${ticker}...`;
        const rows = await fetchJSON(`/api.php?action=close&ticker=${encodeURIComponent(ticker)}`);

        const labels = rows.map(r => r.date);
        const data = rows.map(r => Number(r.close));

        if (!chart) {
        chart = new Chart(document.getElementById('closeChart'), {
            type: 'line',
            data: {
            labels,
            datasets: [{
                label: `${ticker} close`,
                data,
                borderWidth: 2
            }]
            },
            options: {
            responsive: true,
            plugins: { legend: { display: true } },
            scales: {
                x: { display: true },
                y: { display: true }
            }
            }
        });
        } else {
        chart.data.labels = labels;
        chart.data.datasets[0].label = `${ticker} close`;
        chart.data.datasets[0].data = data;
        chart.update();
        }

        statusEl.textContent = '';
    }

    async function main() {
        try {
        await loadTickers();
        if (tickerSel.value) await loadClose(tickerSel.value);

        tickerSel.addEventListener('change', () => loadClose(tickerSel.value));
        reloadBtn.addEventListener('click', () => loadClose(tickerSel.value));
        } catch (e) {
        statusEl.textContent = `Error: ${e.message}`;
        }
    }

    main();
  </script>


</body>
</html>
