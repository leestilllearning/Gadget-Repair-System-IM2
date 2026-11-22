<?php
require_once __DIR__ . '/partials/auth.php';
require_once __DIR__ . '/api/db.php';

$counts = [
    'requests'  => (int)$pdo->query('SELECT COUNT(*) FROM repair_request')->fetchColumn(),
    'invoices'  => (int)$pdo->query('SELECT COUNT(*) FROM invoice')->fetchColumn(),
    'customers' => (int)$pdo->query('SELECT COUNT(*) FROM customer')->fetchColumn(),
    'services'  => (int)$pdo->query('SELECT COUNT(*) FROM service')->fetchColumn(),
];

$staffRole = $_SESSION['staff']['role'] ?? '';
$isManager = ($staffRole === 'Manager');
$isManagerOrAdmin = in_array($staffRole, ['Manager', 'Admin'], true);

$allRequestsForAssign = [];
$activeTechsForAssign = [];
if ($isManagerOrAdmin) {
    $allRequestsForAssign = $pdo->query("
        SELECT r.repair_request_ID,
               TRIM(CONCAT(c.first_name, ' ', c.last_name)) AS customer_name,
               CONCAT(g.brand, ' ', g.model) AS gadget_name,
               r.assigned_tech_ID
        FROM repair_request r
        JOIN gadget g ON r.gadget_ID = g.gadget_ID
        JOIN customer c ON g.customer_ID = c.customer_ID
        ORDER BY r.repair_request_ID DESC
    ")->fetchAll();

    $activeTechsForAssign = $pdo->query("
        SELECT user_id, TRIM(CONCAT(first_name, ' ', last_name)) AS full_name, specialization
        FROM system_user
        WHERE role = 'Technician' AND account_status = 'Active'
        ORDER BY first_name ASC
    ")->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Dashboard — Gadget Repair Shop</title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    .overview-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 16px;
      align-items: start;
    }
    .overview-card {
      background: var(--surface, #ffffff);
      border: 1px solid var(--border, #e2e8f0);
      border-radius: 10px;
      padding: 18px 20px;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      gap: 10px;
      min-width: 0;
    }
    .overview-card strong {
      font-size: 14px;
      color: var(--muted, #64748b);
    }
    .overview-num {
      font-size: 28px;
      font-weight: 700;
      color: var(--text, #0f172a);
    }
    .overview-link {
      font-size: 13px;
      font-weight: 600;
      color: var(--green, #059669);
      text-decoration: none;
    }
    .overview-link:hover {
      text-decoration: underline;
    }
    .mgr-section {
      margin-top: 28px;
      display: flex;
      flex-direction: column;
      gap: 18px;
    }
    .mgr-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 12px;
      flex-wrap: wrap;
    }
    .mgr-header h2 {
      margin: 0;
      font-size: 20px;
      font-weight: 700;
    }
    .mgr-header p {
      margin: 4px 0 0;
      font-size: 13px;
      color: #64748b;
    }
    .mgr-badge {
      padding: 6px 12px;
      border-radius: 999px;
      background: #fef3c7;
      color: #92400e;
      font-size: 12.5px;
      font-weight: 600;
    }
    .mgr-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 12.5px;
    }
    .mgr-table th {
      text-align: left;
      padding: 8px 6px;
      font-size: 11.5px;
      color: #475569;
      border-bottom: 1px solid #e2e8f0;
      white-space: nowrap;
    }
    .mgr-table td {
      padding: 7px 6px;
      border-bottom: 1px solid #f1f5f9;
      vertical-align: top;
    }
    .mgr-field label {
      display: block;
      font-size: 12.5px;
      font-weight: 600;
      margin-bottom: 4px;
    }
    .mgr-field select {
      width: 100%;
      padding: 8px 10px;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      font: inherit;
      background: #ffffff;
    }
    @media (max-width: 720px) {
      .overview-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <div class="layout">
    <?php include __DIR__ . '/partials/nav.php'; ?>

    <main class="main">
      <div class="page-header">
        <h1>Gadget Repair Shop Overview</h1>
        <div class="header-actions">
          <?php if (in_array($staffRole, ['Receptionist', 'Manager', 'Admin'], true)): ?>
            <a class="btn btn-green" href="new_request.php">+ New Repair Request</a>
          <?php endif; ?>
        </div>
      </div>

      <div class="overview-grid">
        <?php if (in_array($staffRole, ['Technician', 'Receptionist', 'Manager', 'Admin'], true)): ?>
          <div class="overview-card">
            <strong>Repair Requests</strong>
            <div class="overview-num"><?= $counts['requests'] ?></div>
            <a class="overview-link" href="repair_requests.php">Manage Repair Requests →</a>
          </div>
        <?php endif; ?>

        <?php if (in_array($staffRole, ['Cashier', 'Manager', 'Admin'], true)): ?>
          <div class="overview-card">
            <strong>Invoices</strong>
            <div class="overview-num"><?= $counts['invoices'] ?></div>
            <a class="overview-link" href="invoices.php">View &amp; Record Payments →</a>
          </div>
        <?php endif; ?>

        <?php if (in_array($staffRole, ['Receptionist', 'Manager', 'Admin'], true)): ?>
          <div class="overview-card">
            <strong>Customers</strong>
            <div class="overview-num"><?= $counts['customers'] ?></div>
            <a class="overview-link" href="customer_gadgets.php">Browse Customers &amp; Gadgets →</a>
          </div>
        <?php endif; ?>

        <?php if ($isManager): ?>
          <div class="overview-card">
            <strong>Services</strong>
            <div class="overview-num"><?= $counts['services'] ?></div>
            <a class="overview-link" href="service_catalog.php">Manage Service Catalog →</a>
          </div>
        <?php endif; ?>
      </div>

      <?php if ($isManagerOrAdmin): ?>
        <section class="mgr-section">
          <div class="mgr-header">
            <div>
              <h2>Manager Operations &amp; Oversight</h2>
            </div>
            <span id="mgrPendingBadge" class="mgr-badge">Pending Payments: 0</span>
          </div>

          <div class="overview-grid">
            <div class="overview-card">
              <h3 style="margin:0 0 6px; font-size:16px;">Stalled Open Repairs (3+ Days)</h3>
              <div style="overflow-x:auto;">
                <table class="mgr-table">
                  <thead>
                    <tr>
                      <th>Request</th>
                      <th>Gadget &amp; Customer</th>
                      <th>Stage</th>
                      <th>Days Open</th>
                    </tr>
                  </thead>
                  <tbody id="stalledBody">
                    <tr><td colspan="4" style="color:#64748b;">Loading...</td></tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="overview-card">
              <h3 style="margin:0 0 6px; font-size:16px;">Ready for Pickup Queue</h3>
              <div style="overflow-x:auto;">
                <table class="mgr-table">
                  <thead>
                    <tr>
                      <th>Request</th>
                      <th>Customer</th>
                      <th>Invoice</th>
                      <th>Balance</th>
                    </tr>
                  </thead>
                  <tbody id="unclaimedBody">
                    <tr><td colspan="4" style="color:#64748b;">Loading...</td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div class="overview-grid">
            <div class="overview-card">
              <h3 style="margin:0 0 6px; font-size:16px;">Technician Active Workload</h3>
              <div style="overflow-x:auto;">
                <table class="mgr-table">
                  <thead>
                    <tr>
                      <th>Technician</th>
                      <th>Specialization</th>
                      <th>Status</th>
                      <th>Open Jobs</th>
                    </tr>
                  </thead>
                  <tbody id="techLoadBody">
                    <tr><td colspan="4" style="color:#64748b;">Loading...</td></tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="overview-card">
              <h3 style="margin:0 0 6px; font-size:16px;">Reassign Repair Technician</h3>
              <form id="reassignForm" style="display:flex; flex-direction:column; gap:12px;">
                <div class="mgr-field">
                  <label for="reassignReqId">Repair Request</label>
                  <select id="reassignReqId">
                    <?php foreach ($allRequestsForAssign as $rq): ?>
                      <option value="<?= htmlspecialchars($rq['repair_request_ID']) ?>">
                        <?= htmlspecialchars($rq['repair_request_ID'] . ' — ' . $rq['gadget_name'] . ' (' . $rq['customer_name'] . ')') ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div class="mgr-field">
                  <label for="reassignTechId">Assign to Active Technician</label>
                  <select id="reassignTechId">
                    <?php foreach ($activeTechsForAssign as $atc): ?>
                      <option value="<?= htmlspecialchars($atc['user_id']) ?>">
                        <?= htmlspecialchars($atc['full_name'] . ($atc['specialization'] ? ' · ' . $atc['specialization'] : '')) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div style="display:flex; align-items:center; gap:12px;">
                  <button type="submit" class="btn btn-green">Reassign Technician</button>
                  <span id="reassignMsg" style="font-size:12.5px; font-weight:600;"></span>
                </div>
              </form>
            </div>
          </div>
        </section>

        <script>
          function mgrEsc(v) {
            return String(v ?? '')
              .replace(/&/g, '&amp;')
              .replace(/</g, '&lt;')
              .replace(/>/g, '&gt;')
              .replace(/"/g, '&quot;');
          }

          function mgrPeso(n) {
            return '₱' + Number(n || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
          }

          async function loadManagerOverview() {
            const res = await fetch('api/endpoints.php?action=manager_overview');
            const d = await res.json();

            document.getElementById('mgrPendingBadge').textContent = `Pending Payments: ${Number(d.pending_payments || 0)}`;

            const stalled = d.stalled_repairs || [];
            document.getElementById('stalledBody').innerHTML = stalled.length
              ? stalled.map(s => `
                  <tr>
                    <td><a href="request_detail.php?id=${encodeURIComponent(s.request_id)}">${mgrEsc(s.request_code)}</a></td>
                    <td>${mgrEsc(s.gadget_name)} · ${mgrEsc(s.customer_name)}</td>
                    <td>${mgrEsc(s.raw_status)}</td>
                    <td style="font-weight:600; color:#b91c1c; white-space:nowrap;">${mgrEsc(s.days_open)} Days</td>
                  </tr>
                `).join('')
              : `<tr><td colspan="4" style="color:#64748b;">No Stalled Repairs Right Now.</td></tr>`;

            const unclaimed = d.unclaimed_pickups || [];
            document.getElementById('unclaimedBody').innerHTML = unclaimed.length
              ? unclaimed.map(u => {
                  const bal = Math.max(0, Number(u.total_amount || 0) - Number(u.paid_amount || 0));
                  return `
                    <tr>
                      <td><a href="request_detail.php?id=${encodeURIComponent(u.request_id)}">${mgrEsc(u.request_code)}</a></td>
                      <td>${mgrEsc(u.customer_name)} (${mgrEsc(u.contact_number)})</td>
                      <td>${mgrEsc(u.invoice_code)} (${mgrEsc(u.invoice_status)})</td>
                      <td style="font-weight:600; white-space:nowrap;">${mgrPeso(bal)}</td>
                    </tr>
                  `;
                }).join('')
              : `<tr><td colspan="4" style="color:#64748b;">No Devices Waiting For Pickup.</td></tr>`;

            const techs = d.technician_workload || [];
            document.getElementById('techLoadBody').innerHTML = techs.length
              ? techs.map(t => `
                  <tr>
                    <td style="font-weight:600;">${mgrEsc(t.full_name)}</td>
                    <td>${mgrEsc(t.specialization)}</td>
                    <td>${mgrEsc(t.account_status)}</td>
                    <td style="font-weight:700;">${mgrEsc(t.active_jobs)}</td>
                  </tr>
                `).join('')
              : `<tr><td colspan="4" style="color:#64748b;">No Technicians Found.</td></tr>`;
          }

          document.getElementById('reassignForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const msg = document.getElementById('reassignMsg');
            const res = await fetch('api/endpoints.php?action=reassign_technician', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({
                request_id: document.getElementById('reassignReqId').value,
                assigned_tech_id: document.getElementById('reassignTechId').value
              })
            });
            const out = await res.json();
            if (res.ok && out.ok) {
              msg.style.color = '#15803d';
              msg.textContent = 'Technician Reassigned.';
              loadManagerOverview();
            } else {
              msg.style.color = '#b91c1c';
              msg.textContent = out.message || 'Could Not Reassign.';
            }
          });

          loadManagerOverview();
        </script>
      <?php endif; ?>
    </main>
  </div>
</body>
</html>
