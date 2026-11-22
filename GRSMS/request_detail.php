<?php
require_once __DIR__ . '/partials/auth.php';
requireRole(['Technician', 'Receptionist', 'Manager', 'Admin']);
require_once __DIR__ . '/api/db.php';

$id = trim((string)($_GET['id'] ?? 'REQ-001'));
$staffRole = $_SESSION['staff']['role'] ?? '';
$canViewInvoice = in_array($staffRole, ['Cashier', 'Manager', 'Admin', 'Receptionist'], true);
$services = $pdo->query('SELECT service_ID, service_name, price FROM service ORDER BY service_ID ASC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Repair Request Detail — Gadget Repair Shop</title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    * { box-sizing: border-box; }
    body {
      margin: 0;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
      background: #ffffff;
      color: #0f172a;
    }
    .layout { display: flex; min-height: 100vh; }
    .main {
      flex: 1;
      padding: 28px 36px;
      background: #ffffff;
      min-width: 0;
    }
    .back-link {
      display: inline-block;
      font-size: 13px;
      color: #475569;
      text-decoration: underline;
      margin-bottom: 10px;
    }
    .detail-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      margin-bottom: 20px;
      flex-wrap: wrap;
    }
    .title-group {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .title-group h1 {
      margin: 0;
      font-size: 26px;
      font-weight: 700;
      letter-spacing: -0.02em;
    }
    .status-badge {
      padding: 4px 12px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: 600;
      background: #dbeafe;
      color: #1d4ed8;
    }
    .status-badge.cancelled {
      background: #fee2e2;
      color: #b91c1c;
    }
    .status-badge.completed,
    .status-badge.released {
      background: #d1fae5;
      color: #065f46;
    }
    .status-badge.pending {
      background: #ffedd5;
      color: #c2410c;
    }
    .step-bar {
      display: grid;
      grid-template-columns: repeat(9, 1fr);
      gap: 8px;
      margin-bottom: 24px;
    }
    .step-box {
      padding: 10px 10px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 600;
      background: #e2e8f0;
      color: #475569;
      text-align: left;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .step-box.done {
      background: #d1fae5;
      color: #065f46;
    }
    .step-box.active {
      background: #047857;
      color: #ffffff;
    }
    .step-box.cancelled {
      background: #dc2626;
      color: #ffffff;
    }
    .detail-cols {
      display: grid;
      grid-template-columns: 320px 1fr;
      gap: 20px;
      align-items: start;
    }
    .card {
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      padding: 20px;
      background: #ffffff;
      margin-bottom: 18px;
    }
    .card h2 {
      margin: 0 0 12px;
      font-size: 17px;
      font-weight: 700;
    }
    .info-row {
      font-size: 13.5px;
      color: #334155;
      margin-bottom: 7px;
      line-height: 1.45;
    }
    .info-row strong.lead {
      color: #0f172a;
      font-size: 14.5px;
      display: block;
      margin-bottom: 4px;
    }
    .controls-row {
      display: flex;
      align-items: center;
      gap: 10px;
      flex-wrap: wrap;
      margin-bottom: 8px;
    }
    select, input[type="number"], input[type="text"].svc-search-box {
      padding: 9px 12px;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      font: inherit;
      font-size: 13.5px;
      background: #ffffff;
      color: #0f172a;
    }
    input[type="text"].svc-search-box:focus {
      outline: 2px solid #047857;
      border-color: #047857;
    }
    .btn-green {
      background: #047857;
      color: #ffffff;
      border: none;
      border-radius: 8px;
      padding: 9px 16px;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
    }
    .btn-green:hover { background: #065f46; }
    .btn-outline {
      background: #ffffff;
      color: #047857;
      border: 1px solid #047857;
      border-radius: 8px;
      padding: 9px 16px;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
    }
    .btn-remove-svc {
      background: #fff1f2;
      color: #be123c;
      border: 1px solid #fecdd3;
      border-radius: 6px;
      padding: 5px 10px;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
    }
    .btn-remove-svc:hover {
      background: #ffe4e6;
      border-color: #fda4af;
    }
    .helper-note {
      font-size: 12px;
      color: #64748b;
      margin: 6px 0 14px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13.5px;
    }
    th {
      text-align: left;
      padding: 10px 12px;
      font-size: 12px;
      color: #475569;
      border-bottom: 1px solid #e2e8f0;
    }
    td {
      padding: 11px 12px;
      border-bottom: 1px solid #f1f5f9;
    }
    tfoot td {
      border-top: 1px solid #e2e8f0;
      border-bottom: none;
      font-weight: 700;
    }
    .add-svc-bar {
      display: flex;
      gap: 12px;
      align-items: flex-end;
      flex-wrap: wrap;
      margin-top: 16px;
    }
    .add-svc-bar label {
      display: block;
      font-size: 12px;
      font-weight: 600;
      margin-bottom: 5px;
    }
    .svc-picker-wrap {
      position: relative;
      flex: 2;
      min-width: 240px;
    }
    .svc-suggest-menu {
      display: none;
      position: absolute;
      top: calc(100% + 4px);
      left: 0;
      right: 0;
      max-height: 260px;
      overflow-y: auto;
      background: #ffffff;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      box-shadow: 0 10px 25px rgba(15, 23, 42, 0.12);
      z-index: 50;
    }
    .svc-suggest-menu.open {
      display: block;
    }
    .svc-suggest-group {
      padding: 6px 10px;
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      color: #64748b;
      background: #f8fafc;
      border-bottom: 1px solid #f1f5f9;
    }
    .svc-suggest-item {
      width: 100%;
      border: none;
      background: #ffffff;
      color: #0f172a;
      padding: 9px 12px;
      text-align: left;
      font: inherit;
      font-size: 13px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 10px;
      cursor: pointer;
      border-bottom: 1px solid #f8fafc;
    }
    .svc-suggest-item:last-child {
      border-bottom: none;
    }
    .svc-suggest-item:hover,
    .svc-suggest-item.highlighted {
      background: #ecfdf5;
      color: #065f46;
    }
    .svc-suggest-price {
      font-weight: 700;
      color: #047857;
      white-space: nowrap;
      font-size: 12.5px;
    }
    .rec-services {
      margin-top: 8px;
      padding: 10px 12px;
      border-radius: 8px;
      background: #f0fdf4;
      border: 1px solid #bbf7d0;
      font-size: 12.5px;
      color: #166534;
    }
    .rec-chips {
      display: flex;
      flex-wrap: wrap;
      gap: 6px;
      margin-top: 6px;
    }
    .rec-chip {
      background: #ffffff;
      border: 1px solid #86efac;
      color: #14532d;
      border-radius: 999px;
      padding: 4px 10px;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
    }
    .rec-chip:hover {
      background: #dcfce7;
    }
    .invoice-box {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      flex-wrap: wrap;
    }
    @media (max-width: 1350px) {
      .step-bar { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 960px) {
      .detail-cols { grid-template-columns: 1fr; }
      .step-bar { grid-template-columns: repeat(2, 1fr); }
    }
  </style>
</head>
<body>
  <div class="layout">
    <?php include __DIR__ . '/partials/nav.php'; ?>

    <main class="main">
      <a href="repair_requests.php" class="back-link">Back to Repair Requests</a>

      <div class="detail-header">
        <div class="title-group">
          <h1 id="detailTitle">Repair Request</h1>
          <span id="detailBadge" class="status-badge">Received</span>
        </div>
        <?php if ($canViewInvoice): ?>
          <a id="topInvoiceLink" href="invoices.php" style="font-size:13.5px;color:#0f172a;font-weight:600;text-decoration:underline;">View Invoice</a>
        <?php endif; ?>
      </div>

      <div class="step-bar" id="stepBar"></div>

      <div class="detail-cols">
        <div>
          <section class="card">
            <h2>Customer</h2>
            <div id="customerInfo"></div>
          </section>

          <section class="card">
            <h2>Gadget</h2>
            <div id="gadgetInfo"></div>
          </section>
        </div>

        <div>
          <section class="card">
            <h2>Request Details</h2>
            <div id="requestInfo"></div>
          </section>

          <section class="card">
            <h2>Status History</h2>
            <div class="controls-row">
              <select id="statusSelect">
                <option value="Received">Received</option>
                <option value="Pending">Pending</option>
                <option value="Diagnosing">Diagnosing</option>
                <option value="Repairing">Repairing</option>
                <option value="Ready for pickup">Ready for Pickup</option>
                <option value="Picked up">Picked Up</option>
                <option value="Released">Released</option>
                <option value="Completed">Completed</option>
                <option value="Cancelled">Cancelled</option>
              </select>
              <button type="button" id="saveStatusBtn" class="btn-green">Update Status</button>
            </div>
            <p class="helper-note">Each Update is Saved with your Name and the Time.</p>

            <table>
              <thead>
                <tr>
                  <th>Status</th>
                  <th>Updated By</th>
                  <th>Date Updated</th>
                </tr>
              </thead>
              <tbody id="historyRows"></tbody>
            </table>
          </section>

          <section class="card">
            <h2>Services</h2>
            <table>
              <thead>
                <tr>
                  <th>Service</th>
                  <th>Quantity</th>
                  <th>Actual Price</th>
                  <th>Subtotal</th>
                  <th style="width:90px;">Action</th>
                </tr>
              </thead>
              <tbody id="serviceRows"></tbody>
              <tfoot>
                <tr>
                  <td colspan="3">Total</td>
                  <td id="serviceTotal">₱0</td>
                  <td></td>
                </tr>
              </tfoot>
            </table>

            <form id="addServiceForm" class="add-svc-bar" autocomplete="off">
              <div class="svc-picker-wrap" id="svcPickerWrap">
                <label for="svcSearchInput">Search &amp; Select Service</label>
                <input
                  id="svcSearchInput"
                  class="svc-search-box"
                  type="text"
                  placeholder="Type service name (e.g., LCD, RAM, Port, Battery)..."
                  style="width:100%;"
                >
                <input type="hidden" id="svcSelectedId" value="">
                <div id="svcSuggestMenu" class="svc-suggest-menu" role="listbox"></div>
                <div id="consoleRecBox" class="rec-services" style="display:none;"></div>
              </div>
              <div style="width:90px;">
                <label for="svcQty">Quantity</label>
                <input id="svcQty" type="number" min="1" value="1" style="width:100%;">
              </div>
              <div style="width:140px;">
                <label for="svcActualPrice">Actual Price (₱)</label>
                <input id="svcActualPrice" type="number" step="0.01" min="0" value="0.00" style="width:100%;">
              </div>
              <div>
                <button type="submit" class="btn-green">Add</button>
              </div>
            </form>
            <div id="svcAddErr" style="margin-top:8px;font-size:12.5px;font-weight:600;color:#b91c1c;"></div>
          </section>

          <?php if ($canViewInvoice): ?>
            <section class="card">
              <div class="invoice-box">
                <div>
                  <h2 style="margin-bottom:4px;">Invoice</h2>
                  <p id="invoiceDesc" style="margin:0;font-size:13px;color:#64748b;">
                    No Invoice Yet. It is Created from the Services above.
                  </p>
                </div>
                <a id="genInvoiceBtn" href="invoices.php" class="btn-outline">Generate Invoice</a>
              </div>
            </section>
          <?php endif; ?>
        </div>
      </div>
    </main>
  </div>

  <script>
    const reqId = <?= json_encode($id) ?>;
    const canViewInvoice = <?= $canViewInvoice ? 'true' : 'false' ?>;
    const allServices = <?= json_encode(array_map(fn($s) => [
        'service_id'    => $s['service_ID'],
        'service_name'  => $s['service_name'],
        'default_price' => (float)$s['price'],
    ], $services), JSON_UNESCAPED_UNICODE) ?>;

    const stages = [
      'Received',
      'Pending',
      'Diagnosing',
      'Repairing',
      'Ready for pickup',
      'Picked up',
      'Released',
      'Completed',
      'Cancelled'
    ];

    const stageLabels = {
      'Received': 'Received',
      'Pending': 'Pending',
      'Diagnosing': 'Diagnosing',
      'Repairing': 'Repairing',
      'Ready for pickup': 'Ready for Pickup',
      'Ready for Pickup': 'Ready for Pickup',
      'Picked up': 'Picked Up',
      'Picked Up': 'Picked Up',
      'Released': 'Released',
      'Completed': 'Completed',
      'Cancelled': 'Cancelled'
    };

    function normalizeSelectValue(status) {
      const s = String(status || 'Received').trim().toLowerCase();
      const map = {
        'received': 'Received',
        'pending': 'Pending',
        'diagnosing': 'Diagnosing',
        'repairing': 'Repairing',
        'ready for pickup': 'Ready for pickup',
        'picked up': 'Picked up',
        'released': 'Released',
        'completed': 'Completed',
        'cancelled': 'Cancelled',
        'canceled': 'Cancelled'
      };
      return map[s] || 'Received';
    }

    const svcPickerWrap = document.getElementById('svcPickerWrap');
    const svcSearchInput = document.getElementById('svcSearchInput');
    const svcSelectedId = document.getElementById('svcSelectedId');
    const svcSuggestMenu = document.getElementById('svcSuggestMenu');
    const svcActualPrice = document.getElementById('svcActualPrice');
    const svcAddErr = document.getElementById('svcAddErr');
    const consoleRecBox = document.getElementById('consoleRecBox');
    const serviceRowsEl = document.getElementById('serviceRows');

    let currentRequest = null;
    let activeHighlightIdx = -1;
    let visibleSuggestions = [];

    function peso(n) {
      return '₱' + Number(n || 0).toLocaleString('en-US', { maximumFractionDigits: 0 });
    }

    function esc(v) {
      return String(v ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
    }

    function isConsoleRequest(req) {
      const cat = String(req?.category || '').toLowerCase();
      const text = `${req?.brand || ''} ${req?.model || ''} ${req?.reported_issue || ''}`.toLowerCase();
      return (
        cat.includes('console') ||
        /\b(psp|ps1|ps2|ps3|ps4|ps5|playstation|xbox|switch|nintendo|controller|joy-?con|drift|button)\b/.test(text)
      );
    }

    function getRecommendedConsoleServices(req) {
      if (!isConsoleRequest(req) || !allServices.length) return [];

      const issue = String(req?.reported_issue || '').toLowerCase();
      const keywords = [
        'button', 'switch', 'stick', 'drift', 'hdmi', 'port', 'charging',
        'power', 'psu', 'fan', 'thermal', 'disc', 'drive', 'console',
        'controller', 'battery', 'speaker'
      ];

      const scored = allServices.map(srv => {
        const hay = String(srv.service_name || '').toLowerCase();
        let score = 0;

        for (const kw of keywords) {
          if (hay.includes(kw)) score += 10;
        }
        if (issue.includes('button') && (hay.includes('button') || hay.includes('switch') || hay.includes('pad'))) score += 30;
        if ((issue.includes('stick') || issue.includes('drift')) && (hay.includes('stick') || hay.includes('analog') || hay.includes('drift'))) score += 30;
        if (issue.includes('hdmi') && hay.includes('hdmi')) score += 30;
        if (issue.includes('power') && (hay.includes('power') || hay.includes('psu') || hay.includes('board'))) score += 25;

        return { srv, score };
      });

      const matches = scored
        .filter(x => x.score > 0)
        .sort((a, b) => b.score - a.score)
        .map(x => x.srv);

      return matches.length ? matches.slice(0, 5) : allServices.slice(0, 4);
    }

    function selectServiceItem(srv, keepMenuClosed = true) {
      if (!srv) return;
      svcSelectedId.value = srv.service_id;
      svcSearchInput.value = srv.service_name;
      svcActualPrice.value = Number(srv.default_price || 0).toFixed(2);
      svcAddErr.textContent = '';
      if (keepMenuClosed) {
        closeSuggestMenu();
      }
    }

    function openSuggestMenu() {
      renderServiceSuggestions(svcSearchInput.value);
      svcSuggestMenu.classList.add('open');
    }

    function closeSuggestMenu() {
      svcSuggestMenu.classList.remove('open');
      activeHighlightIdx = -1;
    }

    function getScoredMatches(query, req) {
      const q = String(query || '').trim().toLowerCase();
      const recommended = getRecommendedConsoleServices(req);
      const recIds = new Set(recommended.map(s => String(s.service_id)));

      if (!q) {
        const recList = recommended.map(s => ({ ...s, _group: 'Recommended For Console', _score: 999 }));
        const otherList = allServices
          .filter(s => !recIds.has(String(s.service_id)))
          .map(s => ({ ...s, _group: recommended.length ? 'All Services' : '', _score: 0 }));
        return [...recList, ...otherList];
      }

      const terms = q.split(/\s+/).filter(Boolean);
      const scored = [];

      for (const s of allServices) {
        const nameLower = String(s.service_name || '').toLowerCase();
        const priceStr = String(s.default_price ?? '');
        let score = 0;

        if (nameLower === q) score += 200;
        else if (nameLower.startsWith(q)) score += 120;
        else if (nameLower.includes(q)) score += 80;

        let allTermsMatched = true;
        for (const t of terms) {
          if (nameLower.includes(t) || priceStr.includes(t)) {
            score += 25;
          } else {
            allTermsMatched = false;
          }
        }

        if (recIds.has(String(s.service_id))) score += 15;

        if (score > 0 && (allTermsMatched || terms.length === 1)) {
          scored.push({ ...s, _group: 'Matching Services', _score: score });
        }
      }

      scored.sort((a, b) => b._score - a._score || a.service_name.localeCompare(b.service_name));
      return scored;
    }

    function renderServiceSuggestions(query = '') {
      visibleSuggestions = getScoredMatches(query, currentRequest);
      activeHighlightIdx = visibleSuggestions.length ? 0 : -1;

      if (!visibleSuggestions.length) {
        svcSuggestMenu.innerHTML = `
          <div style="padding:10px 12px;font-size:12.5px;color:#64748b;">
            No matching service for "<strong>${esc(query)}</strong>". Try another keyword.
          </div>
        `;
        return;
      }

      let html = '';
      let lastGroup = null;
      visibleSuggestions.forEach((item, idx) => {
        if (item._group && item._group !== lastGroup) {
          lastGroup = item._group;
          html += `<div class="svc-suggest-group">${esc(lastGroup)}</div>`;
        }
        const hi = idx === activeHighlightIdx ? 'highlighted' : '';
        html += `
          <button
            type="button"
            class="svc-suggest-item ${hi}"
            data-idx="${idx}"
            onmouseenter="highlightSuggestion(${idx})"
          >
            <span>${esc(item.service_name)}</span>
            <span class="svc-suggest-price">${peso(item.default_price)}</span>
          </button>
        `;
      });

      svcSuggestMenu.innerHTML = html;

      svcSuggestMenu.querySelectorAll('.svc-suggest-item').forEach(btn => {
        btn.addEventListener('mousedown', (ev) => {
          ev.preventDefault();
          const chosen = visibleSuggestions[Number(btn.dataset.idx)];
          if (chosen) {
            selectServiceItem(chosen, true);
          }
        });
      });
    }

    function highlightSuggestion(idx) {
      activeHighlightIdx = idx;
      svcSuggestMenu.querySelectorAll('.svc-suggest-item').forEach((btn, i) => {
        btn.classList.toggle('highlighted', i === idx);
      });
    }

    function renderServicePicker(req) {
      if (!allServices.length) return;

      const isConsole = isConsoleRequest(req);
      const recommended = getRecommendedConsoleServices(req);

      if (!svcSelectedId.value) {
        const initial = recommended[0] || allServices[0];
        selectServiceItem(initial, true);
      }

      if (isConsole && recommended.length) {
        consoleRecBox.style.display = 'block';
        consoleRecBox.innerHTML = `
          <div><strong>Recommended Console Repair Options:</strong></div>
          <div class="rec-chips">
            ${recommended.map(s => `
              <button type="button" class="rec-chip" data-id="${esc(s.service_id)}">
                ${esc(s.service_name)} · ${peso(s.default_price)}
              </button>
            `).join('')}
          </div>
        `;

        consoleRecBox.querySelectorAll('.rec-chip').forEach(btn => {
          btn.addEventListener('click', () => {
            const found = allServices.find(s => String(s.service_id) === String(btn.dataset.id));
            if (found) {
              selectServiceItem(found, true);
            }
          });
        });
      } else {
        consoleRecBox.style.display = 'none';
        consoleRecBox.innerHTML = '';
      }
    }

    svcSearchInput.addEventListener('focus', openSuggestMenu);
    svcSearchInput.addEventListener('click', openSuggestMenu);
    svcSearchInput.addEventListener('input', () => {
      const typed = svcSearchInput.value.trim().toLowerCase();
      const exact = allServices.find(s => String(s.service_name || '').toLowerCase() === typed);
      if (exact) {
        svcSelectedId.value = exact.service_id;
        svcActualPrice.value = Number(exact.default_price || 0).toFixed(2);
      } else {
        svcSelectedId.value = '';
      }
      renderServiceSuggestions(svcSearchInput.value);
      svcSuggestMenu.classList.add('open');
    });

    svcSearchInput.addEventListener('keydown', (e) => {
      if (!svcSuggestMenu.classList.contains('open')) {
        if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
          openSuggestMenu();
        }
        return;
      }

      if (e.key === 'ArrowDown') {
        e.preventDefault();
        if (visibleSuggestions.length) {
          highlightSuggestion((activeHighlightIdx + 1) % visibleSuggestions.length);
        }
      } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        if (visibleSuggestions.length) {
          highlightSuggestion((activeHighlightIdx - 1 + visibleSuggestions.length) % visibleSuggestions.length);
        }
      } else if (e.key === 'Enter' && activeHighlightIdx >= 0 && visibleSuggestions[activeHighlightIdx]) {
        e.preventDefault();
        selectServiceItem(visibleSuggestions[activeHighlightIdx], true);
      } else if (e.key === 'Escape') {
        closeSuggestMenu();
      }
    });

    document.addEventListener('click', (e) => {
      if (!svcPickerWrap.contains(e.target)) {
        closeSuggestMenu();
      }
    });

    async function loadDetail() {
      const res = await fetch(`api/endpoints.php?action=request_detail&id=${encodeURIComponent(reqId)}&_=${Date.now()}`, {
        cache: 'no-store'
      });
      const data = await res.json();
      const r = data.request;
      if (!r) return;
      currentRequest = r;

      const normalizedStatus = normalizeSelectValue(r.status);
      document.getElementById('detailTitle').textContent = `Repair Request ${r.request_code}`;

      const badge = document.getElementById('detailBadge');
      badge.textContent = stageLabels[normalizedStatus] || normalizedStatus;
      badge.className = 'status-badge ' + normalizedStatus.toLowerCase().replace(/\s+/g, '-');

      document.getElementById('statusSelect').value = normalizedStatus;

      if (canViewInvoice && r.invoice_id) {
        const invHref = `invoices.php?id=${encodeURIComponent(r.invoice_id)}`;
        const topLink = document.getElementById('topInvoiceLink');
        const genBtn = document.getElementById('genInvoiceBtn');
        const invDesc = document.getElementById('invoiceDesc');
        if (topLink) topLink.href = invHref;
        if (genBtn) {
          genBtn.href = invHref;
          genBtn.textContent = `Open ${r.invoice_code}`;
        }
        if (invDesc) {
          invDesc.textContent = `Invoice ${r.invoice_code} Is Linked To This Repair Request.`;
        }
      }

      const activeIdx = stages.indexOf(normalizedStatus);
      const isCancelled = normalizedStatus === 'Cancelled';

      document.getElementById('stepBar').innerHTML = stages.map((st, idx) => {
        let cls = 'step-box';
        if (isCancelled) {
          if (st === 'Cancelled') cls += ' cancelled';
        } else {
          if (idx < activeIdx) cls += ' done';
          else if (idx === activeIdx) cls += ' active';
        }
        return `<div class="${cls}" title="${esc(stageLabels[st] || st)}">${esc(stageLabels[st] || st)}</div>`;
      }).join('');

      document.getElementById('customerInfo').innerHTML = `
        <div class="info-row"><strong class="lead">${esc(r.customer_name)}</strong></div>
        <div class="info-row">${esc(r.contact_number)}</div>
        <div class="info-row">${esc(r.email || 'None')}</div>
        <div class="info-row">${esc(r.address || 'None')}</div>
        <div class="info-row" style="color:#64748b;">Registered: ${esc(r.customer_reg_date || '')}</div>
      `;

      document.getElementById('gadgetInfo').innerHTML = `
        <div class="info-row"><strong class="lead">${esc(r.brand)} ${esc(r.model)}</strong></div>
        <div class="info-row">Category: ${esc(r.category)}</div>
        <div class="info-row">Serial Number: ${esc(r.serial_number)}</div>
        <div class="info-row" style="color:#64748b;">Registered: ${esc(r.gadget_reg_date || '')}</div>
      `;

      document.getElementById('requestInfo').innerHTML = `
        <div class="info-row">Reported Issue: ${esc(r.reported_issue)}</div>
        <div class="info-row">Initial Condition: ${esc(r.initial_condition || 'None')}</div>
        <div class="info-row">Accessories Included: ${esc(r.accessories_included || 'None')}</div>
        <div class="info-row">Assigned Technician: ${esc(r.technician_name)}</div>
        <div class="info-row">Received By: ${esc(r.received_by_name)}</div>
        <div class="info-row">Date Submitted: ${esc(r.submitted_date)}</div>
      `;

      document.getElementById('historyRows').innerHTML = (data.history || []).map(h => `
        <tr>
          <td>${esc(stageLabels[h.status] || h.status)}</td>
          <td>${esc(h.updated_by)}</td>
          <td>${esc(h.formatted_date)}</td>
        </tr>
      `).join('');

      const items = data.items || [];
      let sum = 0;
      serviceRowsEl.innerHTML = items.length
        ? items.map(it => {
            sum += Number(it.subtotal || 0);
            return `
              <tr>
                <td>${esc(it.service_label)}</td>
                <td>${esc(it.quantity)}</td>
                <td>${peso(it.actual_price)}</td>
                <td>${peso(it.subtotal)}</td>
                <td>
                  <button
                    type="button"
                    class="btn-remove-svc"
                    data-service-id="${esc(it.service_id ?? it.service_ID ?? '')}"
                    data-service-label="${esc(it.service_label ?? '')}"
                    data-quantity="${esc(it.quantity ?? 1)}"
                    data-actual-price="${esc(it.actual_price ?? 0)}"
                  >Remove</button>
                </td>
              </tr>
            `;
          }).join('')
        : `<tr><td colspan="5" style="color:#64748b;">No Services Added Yet.</td></tr>`;

      document.getElementById('serviceTotal').textContent = peso(sum);

      renderServicePicker(r);
    }

    serviceRowsEl.addEventListener('click', async (e) => {
      const btn = e.target.closest('.btn-remove-svc');
      if (!btn) return;

      btn.disabled = true;
      btn.textContent = 'Removing...';

      try {
        const res = await fetch('api/endpoints.php?action=remove_request_service', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            request_id: reqId,
            service_id: btn.dataset.serviceId,
            service_label: btn.dataset.serviceLabel,
            quantity: Number(btn.dataset.quantity || 1),
            actual_price: Number(btn.dataset.actualPrice || 0)
          })
        });
        const out = await res.json();
        if (res.ok && out.ok) {
          await loadDetail();
        } else {
          btn.disabled = false;
          btn.textContent = 'Remove';
          alert(out.error || 'Could not remove service.');
        }
      } catch (_) {
        btn.disabled = false;
        btn.textContent = 'Remove';
        alert('Unable to reach server.');
      }
    });

    document.getElementById('saveStatusBtn').addEventListener('click', async () => {
      await fetch('api/endpoints.php?action=update_request_status', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          request_id: reqId,
          status: document.getElementById('statusSelect').value
        })
      });
      loadDetail();
    });

    document.getElementById('addServiceForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      svcAddErr.textContent = '';

      const typed = svcSearchInput.value.trim().toLowerCase();
      let matched = allServices.find(s => String(s.service_id) === String(svcSelectedId.value));
      if (!matched && typed) {
        matched = allServices.find(s => String(s.service_name || '').toLowerCase() === typed)
          || allServices.find(s => String(s.service_name || '').toLowerCase().includes(typed));
      }

      if (!matched) {
        svcAddErr.textContent = 'Please type or pick a valid service from the suggestions.';
        openSuggestMenu();
        return;
      }

      await fetch('api/endpoints.php?action=add_request_service', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          request_id: reqId,
          service_id: matched.service_id,
          service_label: matched.service_name,
          quantity: Number(document.getElementById('svcQty').value),
          actual_price: Number(svcActualPrice.value)
        })
      });
      loadDetail();
    });

    loadDetail();
  </script>
</body>
</html>
