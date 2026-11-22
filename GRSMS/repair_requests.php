<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/partials/auth.php';
requireRole(['Technician', 'Receptionist', 'Manager', 'Admin']);

$staffRole = $_SESSION['staff']['role'] ?? '';
$canCreateRequest = in_array($staffRole, ['Receptionist', 'Manager', 'Admin'], true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Repair Requests — Gadget Repair Shop</title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    * {
      box-sizing: border-box;
    }
    body {
      margin: 0;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
      background: #f8fafc;
      color: #0f172a;
    }
    .layout {
      display: flex;
      min-height: 100vh;
    }
    .sidebar {
      width: 220px;
      flex-shrink: 0;
      background: #0b1320;
      color: #e2e8f0;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 20px 14px;
    }
    .brand {
      font-size: 16px;
      font-weight: 700;
      color: #ffffff;
      padding: 6px 10px 18px;
    }
    .nav-list {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }
    .nav-link {
      display: block;
      padding: 10px 12px;
      border-radius: 8px;
      color: #cbd5e1;
      text-decoration: none;
      font-size: 14px;
      font-weight: 500;
    }
    .nav-link:hover {
      background: rgba(255, 255, 255, 0.06);
      color: #ffffff;
    }
    .nav-link.active {
      background: #1e293b;
      color: #ffffff;
      font-weight: 600;
    }
    .sidebar-footer {
      border-top: 1px solid rgba(148, 163, 184, 0.2);
      padding: 14px 10px 4px;
      font-size: 13px;
    }
    .staff-name {
      font-weight: 700;
      color: #ffffff;
      margin-bottom: 2px;
    }
    .staff-role {
      font-size: 12px;
      color: #94a3b8;
      margin-bottom: 8px;
    }
    .sidebar-footer a {
      color: #cbd5e1;
      font-size: 12px;
      text-decoration: underline;
    }

    .main {
      flex: 1;
      padding: 28px 36px;
      background: #ffffff;
    }
    .top-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      margin-bottom: 22px;
      flex-wrap: wrap;
    }
    .top-bar h1 {
      margin: 0;
      font-size: 26px;
      font-weight: 700;
      letter-spacing: -0.02em;
      color: #0f172a;
    }
    .toolbar-right {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .search-input {
      width: 290px;
      padding: 9px 14px;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      font-size: 13px;
      color: #0f172a;
      background: #ffffff;
    }
    .search-input:focus {
      outline: 2px solid #059669;
      border-color: #059669;
    }
    .btn-green {
      background: #047857;
      color: #ffffff;
      border: none;
      border-radius: 8px;
      padding: 10px 16px;
      font-size: 13px;
      font-weight: 600;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      cursor: pointer;
      white-space: nowrap;
    }
    .btn-green:hover {
      background: #065f46;
    }

    .summary-grid {
      display: grid;
      grid-template-columns: repeat(9, 1fr);
      gap: 10px;
      margin-bottom: 24px;
    }
    .stat-card {
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      padding: 14px 12px;
      background: #ffffff;
      cursor: pointer;
      transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .stat-card:hover,
    .stat-card.selected {
      border-color: #047857;
      box-shadow: 0 0 0 1px #047857;
    }
    .stat-label {
      font-size: 11.5px;
      color: #64748b;
      margin-bottom: 6px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .stat-num {
      font-size: 22px;
      font-weight: 700;
      color: #0f172a;
    }

    .table-wrap {
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      overflow-x: auto;
      background: #ffffff;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13.5px;
    }
    thead th {
      text-align: left;
      padding: 12px 16px;
      font-size: 12px;
      font-weight: 600;
      color: #475569;
      border-bottom: 1px solid #e2e8f0;
      background: #ffffff;
      white-space: nowrap;
    }
    tbody td {
      padding: 14px 16px;
      border-bottom: 1px solid #f1f5f9;
      color: #0f172a;
      white-space: nowrap;
    }
    tbody tr:last-child td {
      border-bottom: none;
    }
    tbody tr:hover {
      background: #f8fafc;
    }
    .req-link {
      color: #0f172a;
      font-weight: 600;
      text-decoration: underline;
      text-underline-offset: 2px;
    }

    .pill {
      display: inline-block;
      padding: 4px 10px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: 600;
    }
    .pill-received {
      background: #fef9c3;
      color: #854d0e;
    }
    .pill-pending {
      background: #ffedd5;
      color: #c2410c;
    }
    .pill-diagnosing {
      background: #f3e8ff;
      color: #6b21a8;
    }
    .pill-repairing {
      background: #dbeafe;
      color: #1d4ed8;
    }
    .pill-ready {
      background: #dcfce7;
      color: #15803d;
    }
    .pill-picked {
      background: #f1f5f9;
      color: #334155;
    }
    .pill-released {
      background: #e0f2fe;
      color: #0369a1;
    }
    .pill-completed {
      background: #d1fae5;
      color: #065f46;
    }
    .pill-cancelled {
      background: #fee2e2;
      color: #b91c1c;
    }

    @media (max-width: 1350px) {
      .summary-grid {
        grid-template-columns: repeat(3, 1fr);
      }
    }
    @media (max-width: 720px) {
      .summary-grid {
        grid-template-columns: repeat(2, 1fr);
      }
      .top-bar {
        flex-direction: column;
        align-items: flex-start;
      }
      .search-input {
        width: 100%;
      }
    }
  </style>
</head>
<body>
  <div class="layout">
    <?php include __DIR__ . '/partials/nav.php'; ?>

    <main class="main">
      <div class="top-bar">
        <h1>Repair Requests</h1>
        <div class="toolbar-right">
          <input
            id="searchInput"
            class="search-input"
            type="text"
            placeholder="Search Customer, Gadget or Request No."
          >
          <?php if ($canCreateRequest): ?>
            <a href="new_request.php" class="btn-green">New Request</a>
          <?php endif; ?>
        </div>
      </div>

      <div class="summary-grid" id="summaryCards">
        <div class="stat-card" data-status="received">
          <div class="stat-label">Received</div>
          <div class="stat-num" id="cnt-received">0</div>
        </div>
        <div class="stat-card" data-status="pending">
          <div class="stat-label">Pending</div>
          <div class="stat-num" id="cnt-pending">0</div>
        </div>
        <div class="stat-card" data-status="diagnosing">
          <div class="stat-label">Diagnosing</div>
          <div class="stat-num" id="cnt-diagnosing">0</div>
        </div>
        <div class="stat-card" data-status="repairing">
          <div class="stat-label">Repairing</div>
          <div class="stat-num" id="cnt-repairing">0</div>
        </div>
        <div class="stat-card" data-status="ready for pickup">
          <div class="stat-label">Ready for Pickup</div>
          <div class="stat-num" id="cnt-ready">0</div>
        </div>
        <div class="stat-card" data-status="picked up">
          <div class="stat-label">Picked Up</div>
          <div class="stat-num" id="cnt-picked">0</div>
        </div>
        <div class="stat-card" data-status="released">
          <div class="stat-label">Released</div>
          <div class="stat-num" id="cnt-released">0</div>
        </div>
        <div class="stat-card" data-status="completed">
          <div class="stat-label">Completed</div>
          <div class="stat-num" id="cnt-completed">0</div>
        </div>
        <div class="stat-card" data-status="cancelled">
          <div class="stat-label">Cancelled</div>
          <div class="stat-num" id="cnt-cancelled">0</div>
        </div>
      </div>

      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Request</th>
              <th>Customer</th>
              <th>Gadget</th>
              <th>Reported Issue</th>
              <th>Assigned Technician</th>
              <th>Status</th>
              <th>Date Submitted</th>
            </tr>
          </thead>
          <tbody id="requestsBody">
            <tr>
              <td colspan="7" style="text-align:center;color:#64748b;padding:24px;">Loading Repair Requests...</td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>
  </div>

  <script>
    let allRequests = [];
    let activeStatusFilter = '';
    let isFetching = false;

    function normalizeStatusKey(status) {
      return String(status || 'Received').trim().toLowerCase();
    }

    function formatStatusLabel(status) {
      const key = normalizeStatusKey(status);
      const labels = {
        'received': 'Received',
        'pending': 'Pending',
        'diagnosing': 'Diagnosing',
        'repairing': 'Repairing',
        'ready for pickup': 'Ready for Pickup',
        'picked up': 'Picked Up',
        'released': 'Released',
        'completed': 'Completed',
        'cancelled': 'Cancelled'
      };
      return labels[key] || (status || 'Received');
    }

    function statusPill(status) {
      const key = normalizeStatusKey(status);
      const map = {
        'received': 'pill-received',
        'pending': 'pill-pending',
        'diagnosing': 'pill-diagnosing',
        'repairing': 'pill-repairing',
        'ready for pickup': 'pill-ready',
        'picked up': 'pill-picked',
        'released': 'pill-released',
        'completed': 'pill-completed',
        'cancelled': 'pill-cancelled'
      };
      const cls = map[key] || 'pill-picked';
      return `<span class="pill ${cls}">${escapeHtml(formatStatusLabel(status))}</span>`;
    }

    function escapeHtml(str) {
      return String(str ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
    }

    function updateSummaryCounts() {
      const counts = {
        'received': 0,
        'pending': 0,
        'diagnosing': 0,
        'repairing': 0,
        'ready for pickup': 0,
        'picked up': 0,
        'released': 0,
        'completed': 0,
        'cancelled': 0
      };

      allRequests.forEach(r => {
        const key = normalizeStatusKey(r.status);
        if (Object.prototype.hasOwnProperty.call(counts, key)) {
          counts[key]++;
        }
      });

      document.getElementById('cnt-received').textContent   = counts['received'];
      document.getElementById('cnt-pending').textContent    = counts['pending'];
      document.getElementById('cnt-diagnosing').textContent = counts['diagnosing'];
      document.getElementById('cnt-repairing').textContent  = counts['repairing'];
      document.getElementById('cnt-ready').textContent      = counts['ready for pickup'];
      document.getElementById('cnt-picked').textContent     = counts['picked up'];
      document.getElementById('cnt-released').textContent   = counts['released'];
      document.getElementById('cnt-completed').textContent  = counts['completed'];
      document.getElementById('cnt-cancelled').textContent  = counts['cancelled'];
    }

    function renderTable() {
      const q = document.getElementById('searchInput').value.trim().toLowerCase();
      const tbody = document.getElementById('requestsBody');

      const filtered = allRequests.filter(r => {
        const matchesStatus = !activeStatusFilter || normalizeStatusKey(r.status) === activeStatusFilter;
        const haystack = [
          r.request_code,
          r.customer_name,
          r.gadget_name,
          r.reported_issue,
          r.technician_name,
          r.status
        ].join(' ').toLowerCase();
        const matchesQuery = !q || haystack.includes(q);
        return matchesStatus && matchesQuery;
      });

      if (!filtered.length) {
        tbody.innerHTML = `
          <tr>
            <td colspan="7" style="text-align:center;color:#64748b;padding:24px;">No Matching Repair Requests Found.</td>
          </tr>
        `;
        return;
      }

      tbody.innerHTML = filtered.map(r => `
        <tr>
          <td><a class="req-link" href="request_detail.php?id=${encodeURIComponent(r.request_id)}">${escapeHtml(r.request_code)}</a></td>
          <td>${escapeHtml(r.customer_name)}</td>
          <td>${escapeHtml(r.gadget_name)}</td>
          <td>${escapeHtml(r.reported_issue)}</td>
          <td>${escapeHtml(r.technician_name || 'Unassigned')}</td>
          <td>${statusPill(r.status)}</td>
          <td>${escapeHtml(r.date_label || '')}</td>
        </tr>
      `).join('');
    }

    async function loadDashboard() {
      if (isFetching) return;
      isFetching = true;
      try {
        const reqRes = await fetch(`api/endpoints.php?action=repair_requests&_=${Date.now()}`, {
          cache: 'no-store'
        });
        if (!reqRes.ok) throw new Error(`HTTP ${reqRes.status}`);

        const data = await reqRes.json();
        allRequests = Array.isArray(data) ? data : [];
        updateSummaryCounts();
        renderTable();
      } catch (err) {
        if (!allRequests.length) {
          document.getElementById('requestsBody').innerHTML = `
            <tr>
              <td colspan="7" style="text-align:center;color:#b91c1c;padding:24px;">Failed To Load Repair Requests.</td>
            </tr>
          `;
        }
      } finally {
        isFetching = false;
      }
    }

    document.querySelectorAll('.stat-card').forEach(card => {
      card.addEventListener('click', () => {
        const clickedStatus = card.dataset.status;
        if (activeStatusFilter === clickedStatus) {
          activeStatusFilter = '';
          card.classList.remove('selected');
        } else {
          document.querySelectorAll('.stat-card').forEach(c => c.classList.remove('selected'));
          activeStatusFilter = clickedStatus;
          if (clickedStatus) card.classList.add('selected');
        }
        renderTable();
      });
    });

    document.getElementById('searchInput').addEventListener('input', renderTable);

    // Initial load + real-time refresh every 5 seconds & on tab focus
    loadDashboard();
    setInterval(loadDashboard, 5000);
    document.addEventListener('visibilitychange', () => {
      if (!document.hidden) loadDashboard();
    });
  </script>
</body>
</html>
