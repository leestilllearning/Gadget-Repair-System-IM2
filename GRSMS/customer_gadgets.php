<?php
require_once __DIR__ . '/partials/auth.php';
requireRole(['Receptionist', 'Manager', 'Admin']);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isAdmin = (($_SESSION['staff']['role'] ?? '') === 'Admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Customers &amp; Gadgets — Gadget Repair Shop</title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    .cg-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 16px;
      margin-bottom: 20px;
      flex-wrap: wrap;
    }
    .cg-header h1 {
      margin: 0;
      font-size: 26px;
      font-weight: 700;
      letter-spacing: -0.02em;
    }
    .cg-search {
      width: 290px;
      max-width: 100%;
      padding: 9px 14px;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      font: inherit;
      font-size: 13.5px;
      background: #ffffff;
      color: #0f172a;
    }
    .cg-search:focus {
      outline: 2px solid #047857;
      border-color: #047857;
    }
    .cg-section {
      margin-bottom: 28px;
    }
    .cg-section-title {
      font-size: 18px;
      font-weight: 700;
      margin: 0 0 12px;
      color: #0f172a;
    }
    .cg-card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      overflow-x: auto;
    }
    .cg-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13.5px;
    }
    .cg-table th {
      text-align: left;
      padding: 11px 14px;
      font-size: 12px;
      font-weight: 600;
      color: #475569;
      border-bottom: 1px solid #e2e8f0;
      background: #ffffff;
      white-space: nowrap;
    }
    .cg-table td {
      padding: 12px 14px;
      border-bottom: 1px solid #f1f5f9;
      vertical-align: middle;
    }
    .cg-table tr:last-child td {
      border-bottom: none;
    }
    .btn-edit-cust {
      background: #f1f5f9;
      color: #0f172a;
      border: 1px solid #cbd5e1;
      border-radius: 6px;
      padding: 5px 10px;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
      white-space: nowrap;
    }
    .btn-edit-cust:hover {
      background: #e2e8f0;
    }
    .btn-del-cust {
      background: #fee2e2;
      color: #b91c1c;
      border: 1px solid #fca5a5;
      border-radius: 6px;
      padding: 5px 10px;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
      white-space: nowrap;
    }
    .btn-del-cust:hover {
      background: #fecaca;
    }
    .archived-pill {
      display: inline-block;
      padding: 2px 8px;
      border-radius: 999px;
      font-size: 11.5px;
      font-weight: 600;
      background: #fee2e2;
      color: #b91c1c;
    }
    .edit-cust-panel {
      display: none;
      background: #ffffff;
      border: 1px solid #cbd5e1;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 24px;
      box-shadow: 0 6px 20px rgba(15, 23, 42, 0.06);
    }
    .edit-cust-grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 12px;
      margin-top: 12px;
    }
    .edit-cust-field label {
      display: block;
      font-size: 12px;
      font-weight: 600;
      margin-bottom: 4px;
      color: #334155;
    }
    .edit-cust-field input {
      width: 100%;
      padding: 8px 10px;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      font: inherit;
      font-size: 13px;
    }
    @media (max-width: 820px) {
      .edit-cust-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <div class="layout">
    <?php include __DIR__ . '/partials/nav.php'; ?>

    <main class="main">
      <div class="cg-header">
        <h1>Customers &amp; Gadgets</h1>
        <input id="cgSearch" class="cg-search" type="search" placeholder="Search customer, gadget, serial..." autocomplete="off">
      </div>

      <section id="editCustomerPanel" class="edit-cust-panel">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;">
          <h2 id="editCustomerTitle" style="margin:0;font-size:17px;font-weight:700;">Edit Customer</h2>
          <button type="button" id="cancelEditCustomerBtn" class="btn-edit-cust">Close</button>
        </div>

        <form id="editCustomerForm">
          <input type="hidden" id="edit_customer_id">
          <div class="edit-cust-grid">
            <div class="edit-cust-field">
              <label for="edit_first_name">First Name *</label>
              <input id="edit_first_name" type="text" required>
            </div>
            <div class="edit-cust-field">
              <label for="edit_middle_name">Middle Name</label>
              <input id="edit_middle_name" type="text">
            </div>
            <div class="edit-cust-field">
              <label for="edit_last_name">Last Name *</label>
              <input id="edit_last_name" type="text" required>
            </div>
            <div class="edit-cust-field">
              <label for="edit_contact_number">Contact Number *</label>
              <input id="edit_contact_number" type="text" required>
            </div>
            <div class="edit-cust-field">
              <label for="edit_email">Email</label>
              <input id="edit_email" type="email">
            </div>
            <div class="edit-cust-field">
              <label for="edit_barangay">Barangay</label>
              <input id="edit_barangay" type="text">
            </div>
            <div class="edit-cust-field">
              <label for="edit_city">City / Municipality</label>
              <input id="edit_city" type="text">
            </div>
            <div class="edit-cust-field">
              <label for="edit_province">Province</label>
              <input id="edit_province" type="text">
            </div>
            <div class="edit-cust-field">
              <label for="edit_zip_code">ZIP Code</label>
              <input id="edit_zip_code" type="text">
            </div>
          </div>

          <div style="margin-top:14px;display:flex;align-items:center;gap:12px;">
            <button type="submit" class="btn btn-green">Save Changes</button>
            <span id="editCustomerMsg" style="font-size:13px;font-weight:600;"></span>
          </div>
        </form>
      </section>

      <section class="cg-section">
        <h2 class="cg-section-title">Customers</h2>
        <div class="cg-card">
          <table class="cg-table">
            <thead>
              <tr>
                <th>Customer</th>
                <th>Contact</th>
                <th>Email</th>
                <th>Address</th>
                <th>Registered</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="custRows"></tbody>
          </table>
        </div>
      </section>

      <section class="cg-section">
        <h2 class="cg-section-title">Gadgets</h2>
        <div class="cg-card">
          <table class="cg-table">
            <thead>
              <tr>
                <th>Gadget</th>
                <th>Category</th>
                <th>Serial Number</th>
                <th>Owner</th>
                <th>Registered</th>
              </tr>
            </thead>
            <tbody id="gadgetRows"></tbody>
          </table>
        </div>
      </section>

      <?php if ($isAdmin): ?>
        <section class="cg-section" id="deletedCustSection">
          <h2 class="cg-section-title">Deleted Customers Archive (Admin Only)</h2>
          <div class="cg-card">
            <table class="cg-table">
              <thead>
                <tr>
                  <th>Original ID</th>
                  <th>Customer</th>
                  <th>Contact</th>
                  <th>Email</th>
                  <th>Address</th>
                  <th>Deleted By</th>
                  <th>Date Deleted</th>
                </tr>
              </thead>
              <tbody id="deletedCustRows"></tbody>
            </table>
          </div>
        </section>
      <?php endif; ?>
    </main>
  </div>

  <script>
    const isAdmin = <?= $isAdmin ? 'true' : 'false' ?>;
    let customersCache = [];

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

    function openEditCustomer(customerId) {
      const c = customersCache.find(item => String(item.customer_id) === String(customerId));
      if (!c) return;

      document.getElementById('edit_customer_id').value = c.customer_id;
      document.getElementById('edit_first_name').value = c.first_name || '';
      document.getElementById('edit_middle_name').value = c.middle_name || '';
      document.getElementById('edit_last_name').value = c.last_name || '';
      document.getElementById('edit_contact_number').value = c.contact_number || '';
      document.getElementById('edit_email').value = c.email || '';
      document.getElementById('edit_barangay').value = c.barangay || '';
      document.getElementById('edit_city').value = c.city || '';
      document.getElementById('edit_province').value = c.province || '';
      document.getElementById('edit_zip_code').value = c.zip_code || '';
      document.getElementById('editCustomerTitle').textContent = `Edit Customer (${c.customer_id})`;
      document.getElementById('editCustomerMsg').textContent = '';

      const panel = document.getElementById('editCustomerPanel');
      panel.style.display = 'block';
      panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    document.getElementById('cancelEditCustomerBtn').addEventListener('click', () => {
      document.getElementById('editCustomerPanel').style.display = 'none';
    });

    document.getElementById('editCustomerForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      const msgEl = document.getElementById('editCustomerMsg');
      msgEl.style.color = '#334155';
      msgEl.textContent = 'Saving...';

      const res = await fetch('api/endpoints.php?action=save_customer', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          customer_id: document.getElementById('edit_customer_id').value,
          first_name: document.getElementById('edit_first_name').value.trim(),
          middle_name: document.getElementById('edit_middle_name').value.trim(),
          last_name: document.getElementById('edit_last_name').value.trim(),
          contact_number: document.getElementById('edit_contact_number').value.trim(),
          email: document.getElementById('edit_email').value.trim(),
          barangay: document.getElementById('edit_barangay').value.trim(),
          city: document.getElementById('edit_city').value.trim(),
          province: document.getElementById('edit_province').value.trim(),
          zip_code: document.getElementById('edit_zip_code').value.trim()
        })
      });
      const out = await res.json();
      if (res.ok && out.ok) {
        msgEl.style.color = '#15803d';
        msgEl.textContent = 'Customer updated.';
        await loadData();
        setTimeout(() => {
          document.getElementById('editCustomerPanel').style.display = 'none';
        }, 500);
      } else {
        msgEl.style.color = '#b91c1c';
        msgEl.textContent = out.message || out.error || 'Could not update customer.';
      }
    });

    async function deleteCustomer(customerId, customerName) {
      if (!isAdmin) {
        alert('Only an Admin can delete a customer.');
        return;
      }
      const confirmed = confirm(
        `Archive and delete customer "${customerName}" (${customerId}) along with their gadgets, repair requests, and invoices?`
      );
      if (!confirmed) return;

      const res = await fetch('api/endpoints.php?action=delete_customer', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ customer_id: customerId })
      });
      const out = await res.json();
      if (res.ok && out.ok) {
        if (document.getElementById('edit_customer_id').value === customerId) {
          document.getElementById('editCustomerPanel').style.display = 'none';
        }
        loadData();
      } else {
        alert(out.message || out.error || 'Could not delete customer.');
      }
    }

    async function loadData() {
      const q = document.getElementById('cgSearch').value.trim();
      const res = await fetch(`api/endpoints.php?action=customers_and_gadgets&q=${encodeURIComponent(q)}&_=${Date.now()}`, {
        cache: 'no-store'
      });
      const data = await res.json();
      customersCache = data.customers || [];

      document.getElementById('custRows').innerHTML = customersCache.length
        ? customersCache.map(c => {
            const delBtn = isAdmin
              ? `<button type="button" class="btn-del-cust" onclick="deleteCustomer(${jsQuote(c.customer_id)}, ${jsQuote(c.full_name)})">Delete</button>`
              : '';
            return `
              <tr>
                <td style="font-weight:600;">${esc(c.full_name)}</td>
                <td>${esc(c.contact_number)}</td>
                <td>${esc(c.email || 'None')}</td>
                <td>${esc(c.address || 'None')}</td>
                <td>${esc(c.reg_date)}</td>
                <td>
                  <div style="display:flex;gap:6px;flex-wrap:wrap;">
                    <button type="button" class="btn-edit-cust" onclick="openEditCustomer(${jsQuote(c.customer_id)})">Edit</button>
                    ${delBtn}
                  </div>
                </td>
              </tr>
            `;
          }).join('')
        : `<tr><td colspan="6" style="color:#64748b;text-align:center;padding:20px;">No customers found.</td></tr>`;

      const gadgets = data.gadgets || [];
      document.getElementById('gadgetRows').innerHTML = gadgets.length
        ? gadgets.map(g => `
            <tr>
              <td style="font-weight:600;">${esc(g.brand)} ${esc(g.model)}</td>
              <td>${esc(g.category)}</td>
              <td>${esc(g.serial_number)}</td>
              <td>${esc(g.owner_name)}</td>
              <td>${esc(g.reg_date)}</td>
            </tr>
          `).join('')
        : `<tr><td colspan="5" style="color:#64748b;text-align:center;padding:20px;">No gadgets found.</td></tr>`;

      if (isAdmin) {
        const deletedList = data.deleted_customers || [];
        const delBody = document.getElementById('deletedCustRows');
        if (delBody) {
          delBody.innerHTML = deletedList.length
            ? deletedList.map(d => `
                <tr>
                  <td><span class="archived-pill">${esc(d.original_customer_id)}</span></td>
                  <td style="font-weight:600;">${esc(d.full_name)}</td>
                  <td>${esc(d.contact_number)}</td>
                  <td>${esc(d.email || 'None')}</td>
                  <td>${esc(d.address || 'None')}</td>
                  <td>${esc(d.deleted_by)}</td>
                  <td>${esc(d.deleted_date)}</td>
                </tr>
              `).join('')
            : `<tr><td colspan="7" style="color:#64748b;text-align:center;padding:20px;">No archived deleted customers yet.</td></tr>`;
        }
      }
    }

    document.getElementById('cgSearch').addEventListener('input', loadData);
    loadData();
  </script>
</body>
</html>
