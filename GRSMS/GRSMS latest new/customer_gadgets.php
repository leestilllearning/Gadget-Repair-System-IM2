<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isAdmin = (($_SESSION['staff']['role'] ?? '') === 'Admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Customers and Gadgets — Gadget Repair Shop</title>
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
    .top-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      margin-bottom: 24px;
      flex-wrap: wrap;
    }
    .top-bar h1 {
      margin: 0;
      font-size: 26px;
      font-weight: 700;
      letter-spacing: -0.02em;
    }
    .top-actions {
      display: flex;
      align-items: center;
      gap: 10px;
      flex-wrap: wrap;
    }
    .search-input {
      width: 300px;
      max-width: 100%;
      padding: 9px 14px;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      font-size: 13px;
      color: #0f172a;
      background: #ffffff;
    }
    .search-input:focus {
      outline: 2px solid #047857;
      border-color: #047857;
    }
    .btn-green {
      background: #047857;
      color: #ffffff;
      border: none;
      border-radius: 8px;
      padding: 9px 14px;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
    }
    .btn-green:hover { background: #065f46; }
    .btn-secondary {
      background: #f1f5f9;
      color: #334155;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      padding: 9px 14px;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
    }
    .btn-secondary:hover { background: #e2e8f0; }

    .form-card {
      display: none;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      padding: 20px;
      background: #ffffff;
      margin-bottom: 24px;
      max-width: 780px;
    }
    .form-card h2 {
      margin: 0 0 14px;
      font-size: 18px;
      font-weight: 700;
    }
    .form-grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 12px;
      margin-bottom: 14px;
    }
    .field label {
      display: block;
      font-size: 12.5px;
      font-weight: 600;
      margin-bottom: 5px;
      color: #334155;
    }
    .field input {
      width: 100%;
      padding: 8px 10px;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      font: inherit;
      font-size: 13px;
    }

    .section-heading {
      font-size: 18px;
      font-weight: 700;
      margin: 24px 0 12px;
      color: #0f172a;
    }
    .section-heading:first-of-type { margin-top: 0; }
    .table-card {
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
      white-space: nowrap;
    }
    tbody td {
      padding: 13px 16px;
      border-bottom: 1px solid #f1f5f9;
      color: #0f172a;
    }
    tbody tr:last-child td { border-bottom: none; }
    tbody tr:hover { background: #f8fafc; }
    .muted-cell { color: #64748b; }
    .action-group {
      display: flex;
      gap: 6px;
      align-items: center;
      white-space: nowrap;
    }
    .btn-edit {
      padding: 5px 10px;
      border-radius: 6px;
      border: 1px solid #cbd5e1;
      background: #ffffff;
      color: #0f172a;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
    }
    .btn-edit:hover { background: #f8fafc; }
    .btn-del {
      padding: 5px 10px;
      border-radius: 6px;
      border: 1px solid #fecaca;
      background: #fef2f2;
      color: #b91c1c;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
    }
    .btn-del:hover { background: #fee2e2; }
    .err-banner {
      background: #fef2f2;
      border: 1px solid #fecaca;
      color: #b91c1c;
      padding: 12px 16px;
      border-radius: 8px;
      font-size: 13px;
      margin-bottom: 18px;
      display: none;
    }
    .ok-banner {
      background: #ecfdf5;
      border: 1px solid #a7f3d0;
      color: #065f46;
      padding: 12px 16px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 600;
      margin-bottom: 18px;
      display: none;
    }
    @media (max-width: 820px) {
      .top-bar { flex-direction: column; align-items: stretch; }
      .form-grid { grid-template-columns: 1fr; }
      .main { padding: 20px; }
    }
  </style>
</head>
<body>
  <div class="layout">
    <?php include __DIR__ . '/partials/nav.php'; ?>

    <main class="main">
      <div class="top-bar">
        <h1>Customers & Gadgets</h1>
        <div class="top-actions">
          <input
            id="cgSearch"
            class="search-input"
            type="text"
            placeholder="Search Name, Contact Number Or Serial Number"
          >
          <button type="button" id="openAddCustBtn" class="btn-green">+ Add Customer</button>
        </div>
      </div>

      <div id="cgError" class="err-banner"></div>
      <div id="cgSuccess" class="ok-banner"></div>

      <section id="custFormCard" class="form-card">
        <h2 id="custFormTitle">Add Customer</h2>
        <form id="custForm">
          <input type="hidden" id="cust_id" value="">
          <div class="form-grid">
            <div class="field">
              <label for="first_name">First Name *</label>
              <input id="first_name" type="text" required>
            </div>
            <div class="field">
              <label for="middle_name">Middle Name</label>
              <input id="middle_name" type="text">
            </div>
            <div class="field">
              <label for="last_name">Last Name *</label>
              <input id="last_name" type="text" required>
            </div>
            <div class="field">
              <label for="contact_number">Contact Number *</label>
              <input id="contact_number" type="text" required>
            </div>
            <div class="field">
              <label for="email">Email</label>
              <input id="email" type="email">
            </div>
            <div class="field">
              <label for="barangay">Barangay</label>
              <input id="barangay" type="text">
            </div>
            <div class="field">
              <label for="city">City / Municipality</label>
              <input id="city" type="text">
            </div>
            <div class="field">
              <label for="province">Province</label>
              <input id="province" type="text">
            </div>
            <div class="field">
              <label for="zip_code">ZIP Code</label>
              <input id="zip_code" type="text">
            </div>
          </div>

          <div style="display:flex;gap:10px;align-items:center;">
            <button type="submit" id="saveCustBtn" class="btn-green">Save Customer</button>
            <button type="button" id="cancelCustBtn" class="btn-secondary">Cancel</button>
          </div>
        </form>
      </section>

      <h2 class="section-heading">Customers</h2>
      <div class="table-card">
        <table>
          <thead>
            <tr>
              <th>Name</th>
              <th>Contact Number</th>
              <th>Email</th>
              <th>Address</th>
              <th>Date Registered</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="custBody">
            <tr><td colspan="6" class="muted-cell" style="text-align:center;padding:20px;">Loading Customers...</td></tr>
          </tbody>
        </table>
      </div>

      <h2 class="section-heading">Gadgets</h2>
      <div class="table-card">
        <table>
          <thead>
            <tr>
              <th>Category</th>
              <th>Brand</th>
              <th>Model</th>
              <th>Serial Number</th>
              <th>Owner</th>
              <th>Date Registered</th>
            </tr>
          </thead>
          <tbody id="gadgetBody">
            <tr><td colspan="6" class="muted-cell" style="text-align:center;padding:20px;">Loading Gadgets...</td></tr>
          </tbody>
        </table>
      </div>

      <?php if ($isAdmin): ?>
        <h2 class="section-heading">Deleted Customers</h2>
        <div class="table-card">
          <table>
            <thead>
              <tr>
                <th>Name</th>
                <th>Contact Number</th>
                <th>Email</th>
                <th>Address</th>
                <th>Deleted By</th>
                <th>Date Deleted</th>
              </tr>
            </thead>
            <tbody id="deletedCustBody">
              <tr><td colspan="6" class="muted-cell" style="text-align:center;padding:20px;">Loading Deleted Customers...</td></tr>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </main>
  </div>

  <script>
    const isAdmin = <?= $isAdmin ? 'true' : 'false' ?>;
    let customersCache = [];
    let isSyncing = false;

    function esc(v) {
      return String(v ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
    }

    function jsQuote(v) {
      return "'" + String(v ?? '').replace(/\\/g, '\\\\').replace(/'/g, "\\'") + "'";
    }

    function showErr(msg) {
      const el = document.getElementById('cgError');
      el.textContent = msg;
      el.style.display = 'block';
    }

    function clearErr() {
      const el = document.getElementById('cgError');
      el.textContent = '';
      el.style.display = 'none';
    }

    function showOk(msg) {
      const el = document.getElementById('cgSuccess');
      el.textContent = msg;
      el.style.display = 'block';
      setTimeout(() => { el.style.display = 'none'; }, 3000);
    }

    function openCreateForm() {
      document.getElementById('cust_id').value = '';
      document.getElementById('custForm').reset();
      document.getElementById('custFormTitle').textContent = 'Add Customer';
      document.getElementById('saveCustBtn').textContent = 'Save Customer';
      document.getElementById('custFormCard').style.display = 'block';
      document.getElementById('first_name').focus();
    }

    function openEditForm(customerId) {
      const c = customersCache.find(x => String(x.customer_id) === String(customerId));
      if (!c) return;

      document.getElementById('cust_id').value = c.customer_id || '';
      document.getElementById('first_name').value = c.first_name || '';
      document.getElementById('middle_name').value = c.middle_name || '';
      document.getElementById('last_name').value = c.last_name || '';
      document.getElementById('contact_number').value = c.contact_number || '';
      document.getElementById('email').value = c.email || '';
      document.getElementById('barangay').value = c.barangay || '';
      document.getElementById('city').value = c.city || '';
      document.getElementById('province').value = c.province || '';
      document.getElementById('zip_code').value = c.zip_code || '';

      document.getElementById('custFormTitle').textContent = `Edit Customer (${c.customer_id})`;
      document.getElementById('saveCustBtn').textContent = 'Update Customer';
      document.getElementById('custFormCard').style.display = 'block';
      document.getElementById('first_name').focus();
    }

    function closeCustForm() {
      document.getElementById('custFormCard').style.display = 'none';
      document.getElementById('cust_id').value = '';
      document.getElementById('custForm').reset();
    }

    document.getElementById('openAddCustBtn').addEventListener('click', openCreateForm);
    document.getElementById('cancelCustBtn').addEventListener('click', closeCustForm);

    document.getElementById('custForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      clearErr();

      const customerId = document.getElementById('cust_id').value.trim();
      const payload = {
        customer_id: customerId,
        first_name: document.getElementById('first_name').value.trim(),
        middle_name: document.getElementById('middle_name').value.trim(),
        last_name: document.getElementById('last_name').value.trim(),
        contact_number: document.getElementById('contact_number').value.trim(),
        email: document.getElementById('email').value.trim(),
        barangay: document.getElementById('barangay').value.trim(),
        city: document.getElementById('city').value.trim(),
        province: document.getElementById('province').value.trim(),
        zip_code: document.getElementById('zip_code').value.trim()
      };

      const btn = document.getElementById('saveCustBtn');
      btn.disabled = true;
      try {
        const res = await fetch('api/endpoints.php?action=save_customer', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });
        const data = await res.json();
        if (res.ok && data.ok) {
          closeCustForm();
          showOk(customerId ? 'Customer updated in database.' : 'Customer created in database.');
          await loadData(document.getElementById('cgSearch').value.trim());
        } else {
          showErr(data.message || data.error || 'Could not save customer.');
        }
      } catch (err) {
        showErr(`Network Error: ${err.message}`);
      } finally {
        btn.disabled = false;
      }
    });

    async function deleteCustomer(customerId, customerName) {
      if (!confirm(`Delete Customer "${customerName}"? They Will Be Saved In Deleted Customers.`)) return;
      clearErr();
      const res = await fetch('api/endpoints.php?action=delete_customer', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ customer_id: customerId })
      });
      const data = await res.json();
      if (res.ok && data.ok) {
        showOk('Customer deleted from database.');
        loadData(document.getElementById('cgSearch').value.trim());
      } else {
        showErr(data.message || data.error || 'Could Not Delete Customer.');
      }
    }

    async function loadData(q = '') {
      if (isSyncing) return;
      isSyncing = true;
      try {
        const res = await fetch(`api/endpoints.php?action=customers_and_gadgets&q=${encodeURIComponent(q)}&_=${Date.now()}`, {
          cache: 'no-store'
        });
        const text = await res.text();
        let data;
        try {
          data = JSON.parse(text);
        } catch {
          showErr(`API Error (HTTP ${res.status}): ${text.slice(0, 200)}`);
          return;
        }

        if (!res.ok || data.error) {
          showErr(data.error || data.message || `Request Failed (${res.status})`);
          return;
        }

        clearErr();
        customersCache = Array.isArray(data.customers) ? data.customers : [];
        const gadgets = Array.isArray(data.gadgets) ? data.gadgets : [];
        const deletedCustomers = Array.isArray(data.deleted_customers) ? data.deleted_customers : [];

        document.getElementById('custBody').innerHTML = customersCache.length
          ? customersCache.map(c => `
              <tr>
                <td style="font-weight:500;">${esc(c.full_name)}</td>
                <td>${esc(c.contact_number)}</td>
                <td class="${!c.email || c.email === 'None' ? 'muted-cell' : ''}">${esc(c.email || 'None')}</td>
                <td>${esc(c.address || 'None')}</td>
                <td style="white-space:nowrap;">${esc(c.reg_date || '')}</td>
                <td>
                  <div class="action-group">
                    <button type="button" class="btn-edit" onclick="openEditForm(${jsQuote(c.customer_id)})">Edit</button>
                    ${isAdmin ? `<button type="button" class="btn-del" onclick="deleteCustomer(${jsQuote(c.customer_id)}, ${jsQuote(c.full_name)})">Delete</button>` : ''}
                  </div>
                </td>
              </tr>
            `).join('')
          : `<tr><td colspan="6" class="muted-cell" style="text-align:center;padding:20px;">No Customers Found.</td></tr>`;

        document.getElementById('gadgetBody').innerHTML = gadgets.length
          ? gadgets.map(g => `
              <tr>
                <td>${esc(g.category)}</td>
                <td>${esc(g.brand)}</td>
                <td>${esc(g.model)}</td>
                <td>${esc(g.serial_number)}</td>
                <td>${esc(g.owner_name)}</td>
                <td style="white-space:nowrap;">${esc(g.reg_date || '')}</td>
              </tr>
            `).join('')
          : `<tr><td colspan="6" class="muted-cell" style="text-align:center;padding:20px;">No Gadgets Found.</td></tr>`;

        if (isAdmin) {
          const delBody = document.getElementById('deletedCustBody');
          delBody.innerHTML = deletedCustomers.length
            ? deletedCustomers.map(d => `
                <tr>
                  <td style="font-weight:500;">${esc(d.full_name)}</td>
                  <td>${esc(d.contact_number)}</td>
                  <td class="${!d.email || d.email === 'None' ? 'muted-cell' : ''}">${esc(d.email || 'None')}</td>
                  <td>${esc(d.address || 'None')}</td>
                  <td>${esc(d.deleted_by || '')}</td>
                  <td style="white-space:nowrap;">${esc(d.deleted_date || '')}</td>
                </tr>
              `).join('')
            : `<tr><td colspan="6" class="muted-cell" style="text-align:center;padding:20px;">No Deleted Customers Yet.</td></tr>`;
        }
      } catch (err) {
        showErr(`Network Error: ${err.message}`);
      } finally {
        isSyncing = false;
      }
    }

    document.getElementById('cgSearch').addEventListener('input', e => loadData(e.target.value.trim()));
    document.addEventListener('visibilitychange', () => {
      if (!document.hidden) loadData(document.getElementById('cgSearch').value.trim());
    });
    setInterval(() => {
      if (!document.hidden && document.getElementById('custFormCard').style.display !== 'block') {
        loadData(document.getElementById('cgSearch').value.trim());
      }
    }, 5000);

    loadData();
  </script>
</body>
</html>