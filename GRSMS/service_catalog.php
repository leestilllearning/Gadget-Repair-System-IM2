<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Service Catalog — Gadget Repair Shop</title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    .catalog-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 14px;
      flex-wrap: wrap;
      margin-bottom: 18px;
    }
    .catalog-header h1 {
      margin: 0;
    }
    .srv-search {
      width: 280px;
      max-width: 100%;
      padding: 9px 14px;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      font: inherit;
      font-size: 13.5px;
      background: #fff;
      color: #0f172a;
    }
    .srv-search:focus {
      outline: 2px solid #059669;
      border-color: #059669;
    }
    .card {
      background: #fff;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      padding: 20px;
    }
    .edit-card {
      display: none;
      max-width: 540px;
    }
    .edit-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      margin-bottom: 14px;
    }
    .back-btn {
      background: none;
      border: none;
      color: #475569;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      text-decoration: underline;
      padding: 0;
    }
    .back-btn:hover {
      color: #0f172a;
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
      padding: 12px;
      border-bottom: 1px solid #f1f5f9;
      vertical-align: top;
    }
    .field {
      margin-bottom: 12px;
    }
    .field label {
      display: block;
      font-size: 13px;
      font-weight: 600;
      margin-bottom: 5px;
    }
    .field input,
    .field textarea {
      width: 100%;
      padding: 9px 12px;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      font: inherit;
    }
    .edit-btn {
      background: none;
      border: none;
      color: #059669;
      font-weight: 600;
      cursor: pointer;
      text-decoration: underline;
    }
    .status-banner {
      display: none;
      margin-top: 12px;
      padding: 10px 12px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 600;
    }
    .status-banner.success {
      display: block;
      background: #dcfce7;
      color: #166534;
      border: 1px solid #86efac;
    }
    .status-banner.error {
      display: block;
      background: #fee2e2;
      color: #991b1b;
      border: 1px solid #fca5a5;
    }
    .similar-box {
      display: none;
      margin-top: 8px;
      padding: 10px 12px;
      border-radius: 8px;
      background: #fffbeb;
      border: 1px solid #fde68a;
      font-size: 12.5px;
      color: #92400e;
    }
    .similar-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 8px;
      padding: 5px 0;
      border-top: 1px dashed #fcd34d;
    }
    .similar-item:first-of-type {
      border-top: none;
      padding-top: 2px;
    }
    .use-sim-btn {
      background: #fef3c7;
      border: 1px solid #d97706;
      color: #92400e;
      border-radius: 6px;
      padding: 3px 8px;
      font-size: 11.5px;
      font-weight: 600;
      cursor: pointer;
      white-space: nowrap;
    }
    .use-sim-btn:hover {
      background: #fde68a;
    }
  </style>
</head>
<body>
  <div class="layout">
    <?php include __DIR__ . '/partials/nav.php'; ?>
    <main class="main">
      <div class="catalog-header">
        <h1>Service Catalog</h1>
        <input
          id="srvSearch"
          class="srv-search"
          type="search"
          placeholder="Search service, description, or price..."
          autocomplete="off"
        >
      </div>

      <div id="catalogBanner" class="status-banner" style="margin-top:0;margin-bottom:16px;"></div>

      <div id="tableCard" class="card" style="padding:0;overflow-x:auto;">
        <table>
          <thead>
            <tr><th>Service</th><th>Description</th><th>Price</th><th></th></tr>
          </thead>
          <tbody id="srvBody"></tbody>
        </table>
      </div>

      <div id="editCard" class="card edit-card">
        <div class="edit-header">
          <h2 id="formTitle" style="font-size:18px;font-weight:700;margin:0;">Edit Service</h2>
          <button type="button" id="backToListBtn" class="back-btn">Back To Service Catalog</button>
        </div>

        <form id="srvForm" novalidate>
          <input type="hidden" id="service_id" value="">
          <div class="field">
            <label for="service_name">Service Name</label>
            <input id="service_name" type="text" autocomplete="off" required>
            <div id="similarBox" class="similar-box"></div>
          </div>
          <div class="field">
            <label for="description">Description</label>
            <textarea id="description" rows="2" required></textarea>
          </div>
          <div class="field">
            <label for="default_price">Price (₱)</label>
            <input id="default_price" type="number" step="0.01" min="0" required>
          </div>

          <div style="display:flex;gap:10px;align-items:center;">
            <button type="submit" id="saveBtn" class="btn btn-green">Update Service</button>
            <button type="button" id="cancelBtn" class="btn" style="background:#f1f5f9;color:#334155;">Cancel</button>
          </div>

          <div id="srvMsg" class="status-banner"></div>
        </form>
      </div>
    </main>
  </div>

  <script>
    let servicesCache = [];
    const tableCard = document.getElementById('tableCard');
    const editCard = document.getElementById('editCard');
    const catalogBanner = document.getElementById('catalogBanner');
    const srvMsg = document.getElementById('srvMsg');
    const srvSearch = document.getElementById('srvSearch');

    const peso = n => '₱' + Number(n || 0).toLocaleString('en-US', { maximumFractionDigits: 2 });
    const esc = s => String(s ?? '').replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
    const jsQuote = v => "'" + String(v ?? '').replace(/\\/g, '\\\\').replace(/'/g, "\\'") + "'";

    function showBanner(el, type, text) {
      el.className = 'status-banner ' + type;
      el.textContent = text;
    }

    function clearBanner(el) {
      el.className = 'status-banner';
      el.textContent = '';
    }

    function renderServicesTable() {
      const q = srvSearch.value.trim().toLowerCase();
      const filtered = servicesCache.filter(s => {
        if (!q) return true;
        const pricePlain = String(s.default_price ?? '');
        const priceFormatted = peso(s.default_price).toLowerCase();
        const hay = [s.service_name, s.description, pricePlain, priceFormatted].join(' ').toLowerCase();
        return hay.includes(q);
      });

      if (!filtered.length) {
        document.getElementById('srvBody').innerHTML = `
          <tr>
            <td colspan="4" style="text-align:center;color:#64748b;padding:22px;">
              ${q ? 'No matching services found.' : 'No services in catalog yet.'}
            </td>
          </tr>
        `;
        return;
      }

      document.getElementById('srvBody').innerHTML = filtered.map(s => `
        <tr>
          <td style="font-weight:600;">${esc(s.service_name)}</td>
          <td style="color:#475569;">${esc(s.description)}</td>
          <td style="white-space:nowrap;">${peso(s.default_price)}</td>
          <td><button type="button" class="edit-btn" onclick="editSrv(${jsQuote(s.service_id)})">Edit</button></td>
        </tr>
      `).join('');
    }

    function findSimilarServices(typedName, editingId) {
      const clean = typedName.trim().toLowerCase().replace(/[^a-z0-9\s]/g, ' ');
      if (clean.length < 2) return [];
      const words = clean.split(/\s+/).filter(w => w.length >= 2);

      return servicesCache
        .filter(s => String(s.service_id) !== String(editingId || ''))
        .map(s => {
          const target = String(s.service_name || '').toLowerCase();
          const targetClean = target.replace(/[^a-z0-9\s]/g, ' ');
          let score = 0;

          if (target === typedName.trim().toLowerCase()) score += 100;
          else if (target.includes(typedName.trim().toLowerCase()) || typedName.trim().toLowerCase().includes(target)) score += 60;

          for (const w of words) {
            if (targetClean.includes(w)) score += 25;
          }
          return { service: s, score };
        })
        .filter(entry => entry.score >= 25)
        .sort((a, b) => b.score - a.score)
        .slice(0, 4)
        .map(entry => entry.service);
    }

    function updateSimilarAlert() {
      const typed = document.getElementById('service_name').value;
      const editingId = document.getElementById('service_id').value;
      const box = document.getElementById('similarBox');
      const matches = findSimilarServices(typed, editingId);

      if (!matches.length) {
        box.style.display = 'none';
        box.innerHTML = '';
        return;
      }

      box.style.display = 'block';
      box.innerHTML = `
        <div style="font-weight:700;margin-bottom:4px;">Similar Service Already In Catalog:</div>
        ${matches.map(m => `
          <div class="similar-item">
            <span><strong>${esc(m.service_name)}</strong> (${peso(m.default_price)})</span>
            <button type="button" class="use-sim-btn" onclick="editSrv(${jsQuote(m.service_id)})">Edit This</button>
          </div>
        `).join('')}
      `;
    }

    function showCatalogView(successMessage = '') {
      editCard.style.display = 'none';
      tableCard.style.display = 'block';
      srvSearch.style.display = '';
      document.getElementById('service_id').value = '';
      document.getElementById('srvForm').reset();
      clearBanner(srvMsg);
      updateSimilarAlert();

      if (successMessage) {
        showBanner(catalogBanner, 'success', successMessage);
      } else {
        clearBanner(catalogBanner);
      }
    }

    async function loadServices() {
      try {
        const res = await fetch(`api/endpoints.php?action=services_list&_=${Date.now()}`, { cache: 'no-store' });
        if (!res.ok) throw new Error('Failed');
        servicesCache = await res.json();
        renderServicesTable();
        updateSimilarAlert();
      } catch (err) {
        showBanner(catalogBanner, 'error', 'Failed To Load Service Catalog.');
      }
    }

    function editSrv(id) {
      const item = servicesCache.find(x => String(x.service_id) === String(id));
      if (!item) return;

      clearBanner(catalogBanner);
      clearBanner(srvMsg);
      document.getElementById('service_id').value = item.service_id;
      document.getElementById('service_name').value = item.service_name;
      document.getElementById('description').value = item.description;
      document.getElementById('default_price').value = item.default_price;

      tableCard.style.display = 'none';
      srvSearch.style.display = 'none';
      editCard.style.display = 'block';
      updateSimilarAlert();
    }

    srvSearch.addEventListener('input', renderServicesTable);
    document.getElementById('service_name').addEventListener('input', updateSimilarAlert);
    document.getElementById('cancelBtn').addEventListener('click', () => showCatalogView());
    document.getElementById('backToListBtn').addEventListener('click', () => showCatalogView());

    document.getElementById('srvForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      clearBanner(srvMsg);

      const serviceId = document.getElementById('service_id').value.trim();
      const serviceName = document.getElementById('service_name').value.trim();
      const description = document.getElementById('description').value.trim();
      const rawPrice = document.getElementById('default_price').value.trim();
      const priceNum = Number(rawPrice);

      if (!serviceId) {
        showBanner(srvMsg, 'error', 'Invalid Service Selected.');
        return;
      }
      if (!serviceName || !description) {
        showBanner(srvMsg, 'error', 'Invalid Input: Service Name And Description Are Required.');
        return;
      }
      if (rawPrice === '' || Number.isNaN(priceNum) || priceNum < 0) {
        showBanner(srvMsg, 'error', 'Invalid Price: Enter A Valid Non-Negative Number.');
        return;
      }

      const saveBtn = document.getElementById('saveBtn');
      saveBtn.disabled = true;

      try {
        const res = await fetch('api/endpoints.php?action=save_service', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            service_id: serviceId,
            service_name: serviceName,
            description: description,
            default_price: priceNum
          })
        });
        const data = await res.json();

        if (res.ok && data.ok) {
          showBanner(srvMsg, 'success', 'Service Updated Successfully.');
          await loadServices();
          setTimeout(() => {
            showCatalogView('Service Updated Successfully.');
          }, 900);
        } else {
          showBanner(srvMsg, 'error', data.message || data.error || 'Failed To Update Service.');
        }
      } catch (err) {
        showBanner(srvMsg, 'error', 'Failed To Update Service. Check Your Server Connection.');
      } finally {
        saveBtn.disabled = false;
      }
    });

    loadServices();
  </script>
</body>
</html>