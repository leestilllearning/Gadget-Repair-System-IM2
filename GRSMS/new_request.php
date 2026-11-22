<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/api/db.php';

$receivedBy = $_SESSION['staff']['full_name'] ?? 'Sarah Gomez';
$submittedDisplay = date('M j, Y');

$customers = $pdo->query("
    SELECT customer_ID,
           first_name, middle_name, last_name,
           TRIM(CONCAT(first_name, ' ', IFNULL(CONCAT(middle_name, ' '), ''), last_name)) AS full_name,
           contact_number, email, barangay, city, province, zip_code
    FROM customer
    ORDER BY first_name ASC, last_name ASC
")->fetchAll();

$techs = $pdo->query("
    SELECT user_id, TRIM(CONCAT(first_name, ' ', last_name)) AS full_name, specialization
    FROM system_user
    WHERE role = 'Technician' AND account_status = 'Active'
    ORDER BY first_name ASC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>New Repair Request — Gadget Repair Shop</title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    .req-stack { max-width: 780px; display: flex; flex-direction: column; gap: 20px; }
    .section-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; }
    .section-card h2 { font-size: 18px; font-weight: 700; margin: 0 0 14px; }
    .section-sub { font-size: 13px; color: #64748b; margin: 0 0 18px; }
    .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
    .grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
    .field { margin-bottom: 14px; }
    .field label { display: block; font-size: 13px; font-weight: 600; color: #1e293b; margin-bottom: 6px; }
    .field-hint { display: block; font-size: 11.5px; color: #64748b; margin-top: 4px; }
    .field input, .field select, .field textarea {
      width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 8px;
      font: inherit; background: #fff; color: #0f172a;
    }
    .field input[readonly] { background: #f8fafc; color: #475569; }
    #serial_number { text-transform: uppercase; }
    .addr-mode-bar {
      display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 14px;
    }
    .mode-pill {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 8px 14px; border: 1px solid #cbd5e1; border-radius: 8px;
      font-size: 13px; font-weight: 600; color: #334155; background: #f8fafc;
      cursor: pointer; user-select: none;
    }
    .mode-pill input { width: auto; margin: 0; }
    .mode-pill.active {
      border-color: #047857; background: #ecfdf5; color: #065f46;
    }
    .form-actions { display: flex; align-items: center; gap: 14px; margin-top: 6px; }
    .cancel-link { color: #475569; text-decoration: none; font-size: 14px; font-weight: 500; }
    .err-text { color: #b91c1c; font-size: 13px; font-weight: 600; }
    @media (max-width: 720px) { .grid-3, .grid-2 { grid-template-columns: 1fr; } }
  </style>
</head>
<body>
  <div class="layout">
    <?php include __DIR__ . '/partials/nav.php'; ?>
    <main class="main">
      <div class="page-header"><h1>New Repair Request</h1></div>

      <form id="newReqForm" class="req-stack">
        <section class="section-card">
          <h2>Customer</h2>

          <div class="field">
            <label for="returningCustomerSelect">Returning Customer? Search by Name or Contact Number</label>
            <input list="customerDatalist" id="returningCustomerSelect" placeholder="Type Name or Phone to Autofill...">
            <datalist id="customerDatalist">
              <?php foreach ($customers as $c): ?>
                <option
                  value="<?= htmlspecialchars($c['full_name'] . ' (' . $c['contact_number'] . ')') ?>"
                  data-id="<?= htmlspecialchars($c['customer_ID']) ?>"
                  data-first="<?= htmlspecialchars($c['first_name'] ?? '') ?>"
                  data-middle="<?= htmlspecialchars($c['middle_name'] ?? '') ?>"
                  data-last="<?= htmlspecialchars($c['last_name'] ?? '') ?>"
                  data-phone="<?= htmlspecialchars($c['contact_number']) ?>"
                  data-email="<?= htmlspecialchars($c['email'] ?? '') ?>"
                  data-province="<?= htmlspecialchars($c['province'] ?? '') ?>"
                  data-city="<?= htmlspecialchars($c['city'] ?? '') ?>"
                  data-barangay="<?= htmlspecialchars($c['barangay'] ?? '') ?>"
                  data-zip="<?= htmlspecialchars($c['zip_code'] ?? '') ?>"
                ></option>
              <?php endforeach; ?>
            </datalist>
            <input type="hidden" id="existing_customer_id" value="">
          </div>

          <div class="grid-3">
            <div class="field">
              <label for="first_name">First Name</label>
              <input id="first_name" type="text" required>
            </div>
            <div class="field">
              <label for="middle_name">Middle Name</label>
              <input id="middle_name" type="text">
            </div>
            <div class="field">
              <label for="last_name">Last Name</label>
              <input id="last_name" type="text" required>
            </div>
          </div>

          <div class="grid-2">
            <div class="field">
              <label for="contact_number">Contact Number</label>
              <input
                id="contact_number"
                type="text"
                inputmode="numeric"
                pattern="[0-9]{11}"
                minlength="11"
                maxlength="11"
                placeholder="09XXXXXXXXX"
                required
              >
              <span class="field-hint"> </span>
            </div>
            <div class="field">
              <label for="email">Email</label>
              <input id="email" type="email" placeholder="e.g., customer@email.com">
            </div>
          </div>

          <div class="field" style="margin-bottom:10px;">
            <label>Address Location</label>
            <div class="addr-mode-bar">
              <label class="mode-pill active" id="pillRegionV">
                <input type="radio" name="address_mode" id="modeRegionV" value="region_v" checked>
                Region V (Bicol)
              </label>
              <label class="mode-pill" id="pillForeign">
                <input type="radio" name="address_mode" id="modeForeign" value="foreign">
                Outside Region V / Foreign Country
              </label>
            </div>
          </div>

          <div id="regionVFields">
            <div class="grid-3">
              <div class="field">
                <label for="province">Province (Region V - Bicol)</label>
                <select id="province" required>
                  <option value="">Select Province...</option>
                  <option value="Albay">Albay</option>
                  <option value="Camarines Norte">Camarines Norte</option>
                  <option value="Camarines Sur">Camarines Sur</option>
                  <option value="Catanduanes">Catanduanes</option>
                  <option value="Masbate">Masbate</option>
                  <option value="Sorsogon">Sorsogon</option>
                </select>
              </div>
              <div class="field">
                <label for="city">City / Municipality</label>
                <select id="city" required disabled>
                  <option value="">Select Province First...</option>
                </select>
              </div>
              <div class="field">
                <label for="barangay">Barangay</label>
                <input
                  id="barangay"
                  list="barangayDatalist"
                  type="text"
                  placeholder="Select or type Barangay..."
                  required
                >
                <datalist id="barangayDatalist"></datalist>
              </div>
            </div>

            <div class="field" style="max-width:240px;margin-bottom:0;">
              <label for="zip_code">Zip Code</label>
              <input
                id="zip_code"
                type="text"
                inputmode="numeric"
                maxlength="4"
                placeholder="Auto-detected"
              >
              <span class="field-hint" id="zipHint">Auto-fills when City/Municipality is selected.</span>
            </div>
          </div>

          <div id="foreignAddressFields" style="display:none;">
            <div class="field" style="margin-bottom:0;">
              <label for="foreign_address">For foreign countries / Outside of Region V Address</label>
              <textarea
                id="foreign_address"
                rows="2"
                placeholder="Enter complete address (street, city, state/region, postal code, country)..."
              ></textarea>
              
            </div>
          </div>
        </section>

        <section class="section-card">
          <h2>Gadget</h2>

          <div class="grid-3">
            <div class="field">
              <label for="category">Category</label>
              <select id="category">
                <option value="Smartphone" selected>Smartphone</option>
                <option value="Tablet">Tablet</option>
                <option value="Laptop">Laptop</option>
                <option value="Desktop">Desktop</option>
                <option value="Console">Console</option>
                <option value="Accessory">Accessory</option>
                <option value="Other">Other</option>
              </select>
            </div>
            <div class="field">
              <label for="brand">Brand</label>
              <input
                id="brand"
                list="brandDatalist"
                type="text"
                placeholder="Choose or type Brand..."
                autocomplete="off"
                required
              >
              <datalist id="brandDatalist"></datalist>
              <span class="field-hint">Pick from dropdown or type another brand.</span>
            </div>
            <div class="field">
              <label for="model">Model</label>
              <input
                id="model"
                list="modelDatalist"
                type="text"
                placeholder="Choose or type Model..."
                autocomplete="off"
                required
              >
              <datalist id="modelDatalist"></datalist>
              <span class="field-hint">Recommended models update by brand.</span>
            </div>
          </div>

          <div class="field" style="margin-bottom:0;">
            <label for="serial_number">Serial Number Or IMEI</label>
            <input
              id="serial_number"
              type="text"
              maxlength="15"
              autocomplete="off"
              required
            >
            <span class="field-hint" id="serialCounter">0 / 15 characters</span>
          </div>
        </section>

        <section class="section-card">
          <h2>Repair Request</h2>

          <div class="field">
            <label for="reported_issue">Reported Issue</label>
            <textarea id="reported_issue" rows="3" required></textarea>
          </div>
          <div class="field">
            <label for="initial_condition">Initial Condition</label>
            <textarea id="initial_condition" rows="3"></textarea>
          </div>
          <div class="field">
            <label for="accessories_included">Accessories Included</label>
            <input id="accessories_included" type="text">
          </div>

          <div class="grid-3">
            <div class="field">
              <label for="assigned_tech_id">Assigned Technician</label>
              <select id="assigned_tech_id">
                <?php foreach ($techs as $t): ?>
                  <option value="<?= htmlspecialchars($t['user_id']) ?>">
                    <?= htmlspecialchars($t['full_name'] . ($t['specialization'] ? ' · ' . $t['specialization'] : '')) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="field">
              <label for="received_by">Received By</label>
              <input id="received_by" type="text" value="<?= htmlspecialchars($receivedBy) ?>" readonly>
            </div>
            <div class="field">
              <label for="date_submitted">Date Submitted</label>
              <input id="date_submitted" type="text" value="<?= htmlspecialchars($submittedDisplay) ?>" readonly>
            </div>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-green">Create Request</button>
            <a href="repair_requests.php" class="cancel-link">Cancel</a>
            <span id="reqErr" class="err-text"></span>
          </div>
        </section>
      </form>
    </main>
  </div>

  <script>
    const REGION_V_DATA = {
      "Albay": {
        "Bacacay": { zip: "4509", barangays: ["Hindi", "Poblacion", "San Pablo", "Sogod", "Bonga", "Cagbulacao", "Cagraray", "Pili"] },
        "Camalig": { zip: "4502", barangays: ["Ilawod", "Salugan", "Sumlang", "Tagaytay", "Tinago", "Cotmon", "Quirangay", "Bongabong"] },
        "Daraga": { zip: "4501", barangays: ["Bagumbayan", "Binitayan", "Busay", "Cullat", "Ilawod", "Kimantong", "Maroroy", "Sagpon", "San Roque", "Sipriano"] },
        "Guinobatan": { zip: "4503", barangays: ["Ilawod", "Iraya", "Calzada", "San Francisco", "San Rafael", "Travesia", "Mauraro", "Muladbucad Grande"] },
        "Jovellar": { zip: "4515", barangays: ["Aurora Poblacion", "Plaza Poblacion", "Rizal Poblacion", "San Isidro", "San Roque", "White Deer Poblacion"] },
        "Legazpi City": { zip: "4500", barangays: ["Albay District", "Bagumbayan", "Banquerohan", "Bigaa", "Bitano", "Bonot", "Bogtong", "Cabangan", "Cruzada", "Dap-dap", "EM's Barrio", "Gogon", "Ilawod", "Imperial Court", "Lapu-lapu", "Maoyod", "Old Albay", "Oro Site", "Pinaric", "Rawis", "Sagpon", "San Roque", "Tahao", "Taysan", "Washington Drive"] },
        "Libon": { zip: "4507", barangays: ["Zone I (Poblacion)", "Zone II (Poblacion)", "Zone III (Poblacion)", "San Jose", "San Isidro", "Buga", "Pantao"] },
        "Ligao City": { zip: "4504", barangays: ["Bagumbayan", "Binatagan", "Calzada", "Dunao", "Guilid", "Paulog", "Santa Cruz", "Tinampo", "Tuburan"] },
        "Malilipot": { zip: "4510", barangays: ["Barangay I (Poblacion)", "Barangay II (Poblacion)", "San Isidro Ilawod", "San Isidro Iraya", "San Roque", "Santa Cruz"] },
        "Malinao": { zip: "4512", barangays: ["Poblacion", "Bagatangki", "Balading", "Estancia", "Jonop", "Labnig", "Ogob", "Tagoytoy"] },
        "Manito": { zip: "4514", barangays: ["It-ba (Poblacion)", "Balabagon", "Buyo", "Cawit", "Nagotgot", "Tinapian"] },
        "Oas": { zip: "4505", barangays: ["Ilaor Norte", "Ilaor Sur", "Ilawod", "Maramba", "Obaliw-Rinas", "Rizal", "San Juan", "Tablon"] },
        "Pio Duran": { zip: "4516", barangays: ["Barangay I (Poblacion)", "Barangay II (Poblacion)", "Banawan", "Binodegahan", "Caratagan", "Marigondon"] },
        "Polangui": { zip: "4506", barangays: ["Basud", "Centro Occidental", "Centro Oriental", "Gabon", "Magurang", "Ponso", "Sugcad", "Ubaliw"] },
        "Rapu-Rapu": { zip: "4517", barangays: ["Poblacion", "Batan", "Bilbao", "Carogcog", "Mananao", "Santa Barbara"] },
        "Santo Domingo": { zip: "4508", barangays: ["Bagong San Roque", "Del Rosario", "Market Site", "Nagsiya", "Salvacion", "San Andres", "San Isidro", "San Juan", "Santa Misericordia"] },
        "Tabaco City": { zip: "4511", barangays: ["Bangkilingan", "Basud", "Bombon", "Divino Rostro", "Fatima", "Mariroc", "Pawa", "Quinale Cabasan", "San Carlos", "San Juan", "San Lorenzo", "Tayhi"] },
        "Tiwi": { zip: "4513", barangays: ["Tigbi (Poblacion)", "Libjo", "Naga", "Bariis", "Cale", "Joroan", "Matalibong", "Sugod"] }
      },
      "Camarines Norte": {
        "Basud": { zip: "4608", barangays: ["Poblacion 1", "Poblacion 2", "Bactas", "Mampili", "Matnog", "San Felipe", "Tuaca"] },
        "Capalonga": { zip: "4607", barangays: ["Poblacion", "Alayao", "Binawangan", "Calabaca", "Mactang", "San Isidro"] },
        "Daet": { zip: "4600", barangays: ["Alawihao", "Awitan", "Bagasbas", "Barangay I", "Barangay II", "Barangay III", "Barangay IV", "Barangay V", "Barangay VI", "Barangay VII", "Barangay VIII", "Borabod", "Camambugan", "Cobangbang", "Gahonon", "Lag-on", "Magang", "Mancruz", "Pamorangon"] },
        "Jose Panganiban": { zip: "4606", barangays: ["North Poblacion", "South Poblacion", "Larap", "Luklukan Norte", "Luklukan Sur", "Parang", "Santa Elena"] },
        "Labo": { zip: "4604", barangays: ["Kalamunding (Poblacion)", "Bagong Silang I", "Bagong Silang II", "Bautista", "Dalas", "Fundado", "Malasugui", "Talobatib", "Tulay na Lupa"] },
        "Mercedes": { zip: "4601", barangays: ["Barangay I (Poblacion)", "Barangay II (Poblacion)", "Apuao", "Caringo", "Del Rosario", "Manguisoc", "San Roque"] },
        "Paracale": { zip: "4605", barangays: ["Poblacion Norte", "Poblacion Sur", "Bagumbayan", "Batobalani", "Dalnac", "Gumaus", "Tugos"] },
        "San Lorenzo Ruiz": { zip: "4610", barangays: ["Daculang Bolo", "Dagotdotan", "Langga", "Laniton", "Maisog", "Mampurog", "Matacong (Poblacion)", "San Isidro"] },
        "San Vicente": { zip: "4609", barangays: ["Asdum", "Cabanbanan", "Calabagas", "Fabrica", "Iraya Sur", "Man-ogob", "Poblacion District I", "Poblacion District II"] },
        "Santa Elena": { zip: "4611", barangays: ["Santa Elena (Poblacion)", "Basiad", "Bulala", "Kabuluan", "Maulawin", "Rizal", "San Lorenzo"] },
        "Talisay": { zip: "4602", barangays: ["Poblacion", "Binanuaan", "Caawigan", "Calintaan", "Del Carmen", "Gabon", "Itomang", "San Isidro"] },
        "Vinzons": { zip: "4603", barangays: ["Barangay I (Poblacion)", "Barangay II (Poblacion)", "Barangay III (Poblacion)", "Calangcawan Norte", "Calangcawan Sur", "Mangcawayan", "Sabang"] }
      },
      "Camarines Sur": {
        "Baao": { zip: "4432", barangays: ["Agdangan Poblacion", "Del Rosario", "La Medalla", "San Francisco", "San Isidro", "San Jose", "San Juan", "San Nicolas", "Santa Cruz"] },
        "Balatan": { zip: "4436", barangays: ["Siramag (Poblacion)", "Cabanbanan", "Duran", "Laganac", "Luluasan", "Tapayas"] },
        "Bato": { zip: "4435", barangays: ["Divina Pastora (Poblacion)", "Del Rosario", "San Isidro", "San Juan", "San Miguel", "San Roque", "Santa Cruz", "Tres Reyes"] },
        "Bombon": { zip: "4404", barangays: ["San Antonio", "San Francisco", "San Isidro", "San Jose", "San Roque", "Santo Domingo", "Siembre"] },
        "Buhi": { zip: "4433", barangays: ["San Buenaventura (Poblacion)", "San Pascual (Poblacion)", "San Pedro (Poblacion)", "Santa Clara (Poblacion)", "Sagrada Familia", "San Isidro", "San Jose Baybayon", "Tambo"] },
        "Bula": { zip: "4430", barangays: ["Sagrada (Poblacion)", "Salvacion (Poblacion)", "Bagoladio", "Causip", "Fabrica", "Ombao Polpog", "Palsong", "San Isidro"] },
        "Cabusao": { zip: "4406", barangays: ["Barcelonita", "Biong", "Camagong", "Castillo", "New Poblacion", "Pandan", "Santa Cruz", "Santa Lutgarda"] },
        "Calabanga": { zip: "4405", barangays: ["San Antonio Poblacion", "San Francisco", "San Isidro", "San Lucas", "San Miguel", "San Pablo", "San Roque", "Santa Cruz Poblacion", "Sabang"] },
        "Camaligan": { zip: "4401", barangays: ["Dugcal", "Marupit", "San Francisco", "San Jose-San Pablo", "San Juan-San Ramon", "San Lucas", "San Marcos", "San Mateo", "San Roque", "Santo Domingo", "Sua", "Tarosanan"] },
        "Canaman": { zip: "4402", barangays: ["Baras (Poblacion)", "Del Rosario", "Dinaga (Poblacion)", "Haring", "Mangayawan", "Palo", "Pangpang (Poblacion)", "San Agustin", "San Francisco", "San Jose East", "San Jose West", "San Nicolas", "San Roque", "San Vicente", "Santa Cruz (Poblacion)", "Santa Teresita", "Sua", "Talidtid", "Tibgao (Poblacion)"] },
        "Caramoan": { zip: "4429", barangays: ["Bikal", "Gogon", "Ilawod", "Paniman", "Tabgon", "Tawog (Poblacion)"] },
        "Del Gallego": { zip: "4411", barangays: ["Poblacion Zone I", "Poblacion Zone II", "Comadaycaday", "Mabini", "Magais I", "Sinuknipan I"] },
        "Gainza": { zip: "4412", barangays: ["Cagbunga", "Dahilig", "District I (Poblacion)", "District II (Poblacion)", "Loob", "Malbong", "Namuat", "Sampaloc"] },
        "Garchitorena": { zip: "4428", barangays: ["Barangay I (Poblacion)", "Barangay II (Poblacion)", "Ason", "Bahi", "Harrison", "Tamban"] },
        "Goa": { zip: "4422", barangays: ["Bagumbayan Grande (Poblacion)", "Bagumbayan Pequeño (Poblacion)", "Belen (Poblacion)", "Gimaga", "La Purisima", "San Isidro", "San Jose (Poblacion)", "Tagongtong"] },
        "Iriga City": { zip: "4431", barangays: ["Francia", "La Anunciacion", "La Medalla", "La Purisima", "La Trinidad", "San Agustin", "San Antonio", "San Francisco (Poblacion)", "San Isidro", "San Jose", "San Juan", "San Miguel", "San Nicolas", "San Pedro", "San Roque (Poblacion)", "Santiago", "Santo Domingo", "Santo Niño"] },
        "Lagonoy": { zip: "4425", barangays: ["San Isidro", "San Rafael", "San Sebastian", "Santa Maria (Poblacion)", "Burabod", "Dahilig", "Presentacion"] },
        "Libmanan": { zip: "4407", barangays: ["Bagumbayan", "Bahi", "Concepcion", "Handong", "Mambulo Nuevo", "Mambulo Viejo", "Poblacion", "San Isidro", "San Juan", "Station Church Site"] },
        "Lupi": { zip: "4409", barangays: ["Poblacion", "Barrera Jr.", "Colacling", "Del Carmen", "San Pedro", "Tapi"] },
        "Magarao": { zip: "4403", barangays: ["Bell", "Carangcang", "Carigsa", "Casuray", "Monserrat (Poblacion)", "Ponong", "San Francisco (Poblacion)", "San Isidro (Poblacion)", "San Juan (Poblacion)", "San Miguel", "San Pantaleon (Poblacion)", "Santa Lucia (Poblacion)", "Santa Rosa", "Santo Tomas (Poblacion)"] },
        "Milaor": { zip: "4413", barangays: ["Alimbuyog", "Amparado (Poblacion)", "Balagbag", "Borongborongan", "Cabugao", "Capucnasan", "Dalipay", "Del Rosario (Poblacion)", "Flordeliz", "Lipot", "Mayaopayawan", "Maycatmon", "Maydaso", "San Antonio", "San Jose (Poblacion)", "San Miguel (Poblacion)", "San Roque (Poblacion)", "San Vicente (Poblacion)", "Santo Domingo (Poblacion)", "Tarusanan"] },
        "Minalabac": { zip: "4414", barangays: ["Antipolo", "Bagolatao", "Bagongbong", "Baliuag Nuevo", "Baliuag Viejo", "Catanusan", "Del Carmen-Del Rosario (Poblacion)", "Del Socorro", "Hamoraon", "Hobo", "Irayang Solong", "Magadap", "Manapao", "Mataoroc", "Sagrada (Sagrada Familia)", "San Antonio", "San Felipe-Santiago (Poblacion)", "San Francisco (Poblacion)", "San Jose", "San Juan-San Lorenzo (Poblacion)", "Taban", "Tariric", "Timbang"] },
        "Nabua": { zip: "4434", barangays: ["Angustia", "Antipolo Old", "Antipolo Young", "Aro-aldao", "Bustrac", "Dolorosa", "Duran", "Inapatan", "La Opinion", "La Purisima", "Lourdes Old", "Lourdes Young", "Malawag", "Paloyon", "San Antonio (Poblacion)", "San Esteban (Poblacion)", "San Francisco (Poblacion)", "San Isidro (Poblacion)", "San Jose", "San Juan (Poblacion)", "San Luis (Poblacion)", "San Miguel (Poblacion)", "San Nicolas (Poblacion)", "San Roque (Poblacion)", "San Vicente Gorong-Gorong", "San Vicente Ogbon", "Santa Barbara", "Santa Cruz", "Santa Elena", "Santa Lucia", "Santiago Old", "Santiago Young", "Santo Domingo", "Tandaay", "Topas Proper", "Topas Sogod"] },
        "Naga City": { zip: "4400", barangays: ["Abella", "Bagumbayan Norte", "Bagumbayan Sur", "Balatas", "Calauag", "Cararayan", "Carolina", "Concepcion Grande", "Concepcion Pequeña", "Dayangdang", "Del Rosario", "Dinaga", "Igualdad Interior", "Lerma", "Liboton", "Mabolo", "Pacol", "Panicuason", "Peñafrancia", "Sabang", "San Felipe", "San Francisco", "San Isidro", "Santa Cruz", "Tabuco", "Tinago", "Triangulo"] },
        "Ocampo": { zip: "4419", barangays: ["Ayugan", "Cabariwan", "Cagmanaba", "Del Rosario", "Gatbo", "Guinaban", "Hanawan", "Hibago", "La Purisima Nuevo", "La Purisima Viejo", "May-Ogob", "New Moriones", "Old Moriones", "Pinit", "Poblacion Central", "Poblacion East", "Poblacion West", "Salvacion", "San Antonio", "San Francisco", "San Jose Oras", "San Roque Commonal", "San Vicente", "Santa Cruz", "Santo Niño"] },
        "Pamplona": { zip: "4416", barangays: ["Batang", "Burabod", "Cagbibi", "Cagbunga", "Calawat", "Del Rosario", "Patong", "Poblacion", "Salvacion", "San Gabriel", "San Isidro", "San Rafael", "San Ramon", "San Vicente", "Tambo", "Tampadong", "Veneracion"] },
        "Pasacao": { zip: "4417", barangays: ["Antipolo", "Bagong Silang", "Bahay", "Balogo", "Caranan", "Cuco", "Dalupaon", "Hubo", "Itulan", "Odicon", "Quitang", "Salvacion", "San Cirilo (Poblacion)", "Santa Rosa del Norte (Poblacion)", "Santa Rosa del Sur (Poblacion)", "Tilnac", "Tinalmud"] },
        "Pili": { zip: "4418", barangays: ["Anayan", "Bagong Sirang", "Binanwaanan", "Binobong", "Cadlan", "Caroyroyan", "Curry", "Del Rosario", "Himaao", "La Purisima", "New San Roque", "Old San Roque (Poblacion)", "Palestina", "Pawili", "Sagrada", "Sagurong", "San Agustin", "San Antonio (Poblacion)", "San Isidro (Poblacion)", "San Jose", "San Juan (Poblacion)", "San Vicente (Poblacion)", "Santiago (Poblacion)", "Santo Niño", "Tagbong", "Tinangis"] },
        "Presentacion": { zip: "4424", barangays: ["Ayugao", "Bagong Sirang", "Baliguian", "Bantugan", "Bicalen", "Buenavista", "Bulalacao", "Cagnipa", "Lagha", "Lidong", "Liwacsa", "Maangas", "Pagsangahan", "Patrocinio", "Pili", "Santa Maria (Poblacion)", "Tanauan"] },
        "Ragay": { zip: "4410", barangays: ["Agao-ao", "Agrupacion", "Amomokpok", "Apad", "Apale", "Banga Caves", "Baya", "Binahan Proper", "Binahan Upper", "Buenavista", "Cabinitan", "Cabugao", "Caditaan", "Cale", "Godofredo Reyes Sr.", "Catabangan Proper", "Inandawa", "Laguio", "Lanipga-Cawayan", "Liboro", "Lohong", "Lower Omon", "Lower Santa Cruz", "Panaytayan", "Panaytayan Nuevo", "Patalunan", "Poblacion Ilaod", "Poblacion Iraya", "Port Junction Norte", "Port Junction Sur", "Salvacion", "Samay", "San Rafael", "Tagbac", "Upper Omon", "Upper Santa Cruz"] },
        "Sagñay": { zip: "4421", barangays: ["Aniog", "Atulayan", "Bongalon", "Buracan", "Catalotoan", "Del Carmen (Poblacion)", "Kilantaao", "Kilomaon", "Mabca", "Minadongjol", "Nato", "Patitinan", "San Antonio (Poblacion)", "San Isidro (Poblacion)", "San Roque (Poblacion)", "Santo Niño", "Sibaguan", "Tinorongan", "Turague"] },
        "San Fernando": { zip: "4415", barangays: ["Alianza", "Beberon", "Bical", "Bocal", "Bonifacio (Poblacion)", "Buenavista (Poblacion)", "Calascagas", "Cotmo", "Daculang Tubig", "Del Pilar (Poblacion)", "Gñaran", "Grijalvo", "Lupi", "Maragñi", "Pamukid", "Pinamasagan", "Pipian", "Planza", "Rizal (Poblacion)", "San Joaquin", "Santa Cruz", "Tagpocol"] },
        "San Jose": { zip: "4423", barangays: ["Adiangao", "Bagacay", "Bahay", "Boclod", "Calalahan", "Calawit", "Camagong", "Catalotoan", "Danlog", "Del Carmen (Poblacion)", "Dolo", "Kinalansan", "Mampirao", "Manzana", "Minoro", "Palale", "Ponglon", "Pugay", "Sabang", "Salogon", "San Antonio (Poblacion)", "San Juan (Poblacion)", "San Vicente (Poblacion)", "Santa Cruz (Poblacion)", "Soledad (Poblacion)", "Tagas", "Tambangan", "Telegrafo", "Tominawog"] },
        "Sipocot": { zip: "4408", barangays: ["Aldezar", "Alteza", "Anib", "Awayan", "Azucena", "Bagong Sirang", "Binahian", "Bolo Sur", "Bolo Norte", "Bulan", "Bulawan", "Cabuyao", "Caima", "Calagbangan", "Calampinay", "Carayrayan", "Cotmo", "Gabi", "Gaongan", "Impig", "Lipilip", "Lubigan Jr.", "Lubigan Sr.", "Malaguico", "Malubago", "Manangle", "Mangga", "Mangapo", "Manlubang", "Mantila", "North Centro (Poblacion)", "North Villazar", "Sagrada Familia", "Salanda", "Salvacion", "San Isidro", "San Vicente", "Serranzana", "South Centro (Poblacion)", "South Villazar", "Taisan", "Tara", "Tible", "Tula-tula", "Vigaan", "Yabo"] },
        "Siruma": { zip: "4427", barangays: ["Bagong Sirang", "Bahao", "Boboan", "Butawanan", "Cabugao", "Fundado", "Homestead", "La Purisima", "Mabuhay", "Malaconini", "Matandang Siruma", "Nalayahan", "Pinitan", "Poblacion", "Pamintan-Bantilan", "Salvacion", "San Andres", "San Ramon", "Sulpa", "Tandoc", "Tongo-Bantigue", "Vito"] },
        "Tigaon": { zip: "4420", barangays: ["Abo", "Cabalinadan", "Caraycayon", "Coyaoyao", "Gaao", "Ginaroronan", "Huyonhuyon", "Libod", "Mabalodbalod", "May-Anao", "Panagan", "Poblacion", "Salvacion", "San Antonio", "San Francisco", "San Miguel", "San Rafael", "Santa Cruz", "Talojongon", "Tinawagan", "Vinagre"] },
        "Tinambac": { zip: "4426", barangays: ["Agay-Ayan", "Antipolo", "Bagacay", "Banga", "Bolaobalite", "Bani", "Bataan", "Binalay", "Buenavista", "Buyo", "Cagliliog", "Caloco", "Camagong", "Canayonan", "Cawaynan", "Daligan", "Filarca (Poblacion)", "La Purisima", "Lupi", "Magsaysay (Camp 4)", "Magtang", "Mananao", "La Medalla (Mile 9)", "New Caaluan", "Olag Grande", "Olag Pequeño", "Old Caaluan", "Pantat", "Sagrada (Camp 6)", "Salvacion", "San Antonio", "San Isidro (Poblacion)", "San Jose (Tiltilan)", "San Pascual (Poblacion)", "San Ramon (Camp 7)", "San Roque", "San Vicente", "Santa Cruz (Poblacion)", "Sogod", "Tambang", "Tierra Nevada", "Union"] }
      },
      "Catanduanes": {
        "Bagamanoc": { zip: "4807", barangays: ["Antipolo", "Bacak", "Bagatabao", "Bugao", "Cahan", "Hinipaan", "Magsaysay", "Poblacion", "Quigaray", "Quezon (Pancayanan)", "Sagrada", "Salvacion (Panuto)", "San Isidro", "San Rafael (Mahayahay)", "San Vicente", "Santa Mesa", "Santa Teresa", "Suchan"] },
        "Baras": { zip: "4803", barangays: ["Abihao", "Agban", "Bagong Sirang", "Benticayan", "Buenavista", "Caragumihan", "Batolinao", "Danao", "Sagrada", "Ginitligan", "Guinsaanan", "J. M. Alberto", "Macutal", "Moning", "Nagbarorong", "Osmeña", "P. Teston", "Paniquihan", "Eastern Poblacion", "Western Poblacion", "Puraran", "Putsan", "Quezon", "Rizal", "Salvacion", "San Lorenzo", "San Miguel", "Santa Maria", "Tilod"] },
        "Bato": { zip: "4801", barangays: ["Aroyao Pequeño", "Aroyao Grande", "Bagumbayan", "Banawang (Poblacion)", "Batalay", "Binanuahan", "Bote", "Buenavista", "Cabugao", "Cagraray", "Carorian", "Guinobatan", "Libjo", "Marinawa", "Mintay", "Oguis", "Pananaogan", "Libod Poblacion", "San Andres", "San Pedro", "San Roque", "Santa Isabel", "Sibacungan", "Sipi", "Talisay", "Tamburan", "Tilis"] },
        "Caramoran": { zip: "4808", barangays: ["Baybay (Poblacion)", "Bocon", "Bothoan (Poblacion)", "Buenavista", "Bulalacao", "Camburo", "Dariao", "Datag East", "Datag West", "Guiamlong", "Hitoma", "Icanbato (Poblacion)", "Inalmasinan", "Iyao", "Mabini", "Macua", "Maysuram", "Milaviga", "Obi", "Panique", "Sabangan", "Sabloyon", "Salvacion", "Supang", "Toytoy (Poblacion)", "Tubli", "Tucao"] },
        "Gigmoto": { zip: "4804", barangays: ["Biong", "Dororian", "Poblacion District I", "Poblacion District II", "Poblacion District III", "San Pedro", "San Vicente", "Sicmil", "Sioron"] },
        "Pandan": { zip: "4809", barangays: ["Bagawang", "Balagñonan", "Baldoc", "Canlubi", "Santa Cruz (Catagbacan)", "Catamban", "Cobo", "Hiyop", "Libod (Poblacion)", "Lourdes", "Lumad", "Marambong", "Napo (Poblacion)", "Oga", "Pandan del Norte (Poblacion)", "Pandan del Sur (Poblacion)", "Panuto", "Salvacion (Tariwara)", "San Andres (Dinungsuran)", "San Isidro (Langob)", "Porot (San Jose)", "San Rafael (Bogtong)", "San Roque", "Tabugoc", "Tokio", "Wagdas"] },
        "Panganiban": { zip: "4806", barangays: ["Alinawan", "Babaguan", "Bagong Bayan", "Burabod", "Cabuyoan", "Cagdarao", "Mabini", "Maculiw", "Panay", "Taopon (Pangcayanan)", "Salvacion (Poblacion)", "San Antonio", "San Joaquin (Poblacion)", "San Jose (Poblacion)", "San Juan (Poblacion)", "San Miguel", "San Nicolas (Poblacion)", "San Pedro (Poblacion)", "San Vicente (Poblacion)", "Santa Ana (Poblacion)", "Santa Maria (Poblacion)", "Santo Santiago (Poblacion)", "Tibo"] },
        "San Andres": { zip: "4810", barangays: ["Agojo", "Alibuag", "Asgad", "Bagong Sirang", "Barihay", "Batong Paloway", "Belmonte (Poblacion)", "Bislig", "Bon-ot", "Cabungahan", "Cabcab", "Carangag", "Catagbacan", "Codon", "Comagaycay", "Datag", "Divino Rostro (Poblacion)", "Esperanza (Poblacion)", "Hilawan", "Lictin", "Lubas", "Manambrag", "Mayngaway", "Palawig", "Puting Baybay", "Rizal", "Salvacion (Poblacion)", "San Isidro", "San Jose", "San Roque", "San Vicente", "Santa Cruz (Poblacion)", "Sapang Palay (Poblacion)", "Tibang", "Timbaan", "Tominawog", "Wagdas (Poblacion)", "Yocti"] },
        "San Miguel": { zip: "4802", barangays: ["Balatohan", "Salvacion (Patagan)", "Boton", "Buhi", "Dayawa", "Atsan (District I)", "Poblacion District II", "Poblacion District III", "J. M. Alberto", "Katipunan", "Kilikilihan", "Mabato", "Obo", "Pacogon", "Pagsangahan", "Pangilao", "Paraiso", "Santa Elena (Patagan)", "Progreso", "San Juan (Aroyao)", "San Marcos", "Siay", "Solong", "Tobrehon"] },
        "Viga": { zip: "4805", barangays: ["Almojuela", "Ananong", "Asuncion (Poblacion)", "Batohonan", "Begonia", "Botinagan", "Buenavista", "Burgos", "Del Pilar", "Mabini", "Magsaysay", "Ogbong", "Osmeña", "Pedro Vera (Summit)", "Peñafrancia (Poblacion)", "Quezon", "Quirino (Abugan)", "Rizal", "Roxas", "Sagrada", "San Isidro (Poblacion)", "San Jose Oco", "San Jose Poblacion", "San Pedro (Poblacion)", "San Roque (Poblacion)", "San Vicente (Poblacion)", "Santa Rosa", "Soboc", "Tambongon", "Tinago", "Villa Aurora"] },
        "Virac": { zip: "4800", barangays: ["Antipolo del Norte", "Antipolo del Sur", "Balite", "Batag", "Bigaa", "Buenavista", "Buyo", "Cabihian", "Calabnigan", "Calampong", "Calatagan Proper", "Calatagan Tibang", "Capilihan", "Casoocan", "Cavinitan", "Gogon Sirangan", "Concepcion (Poblacion)", "Constantino (Poblacion)", "Danicop", "Dugui San Vicente", "Dugui San Isidro", "Dugui Too", "F. Tacorda Village", "Francia (Poblacion)", "Gogon Centro", "Hawan Grande", "Hawan Ilaya", "Hicming", "Igang", "Juan M. Alberto (Poniton)", "Lanao (Poblacion)", "Magnesia del Norte", "Magnesia del Sur", "Marcelo Alberto (Poblacion)", "Marilima", "Pajo Baguio", "Pajo San Isidro", "Palnab del Norte", "Palnab del Sur", "Palta Big", "Palta Salvacion", "Palta Small", "Rawis (Poblacion)", "Salvacion", "San Isidro Village", "San Jose (Poblacion)", "San Juan (Poblacion)", "San Pablo (Poblacion)", "San Pedro (Poblacion)", "San Roque (Poblacion)", "San Vicente", "Ibong Sapa (San Vicente Sur)", "Santa Cruz", "Santa Elena (Poblacion)", "Santo Cristo", "Santo Domingo", "Santo Niño", "Simamla", "Sogod-Simamla", "Talisoy", "Sogod-Tibgao", "Tubaon", "Valencia"] }
      },
      "Masbate": {
        "Aroroy": { zip: "5414", barangays: ["Ambolong", "Amoroy", "Amutag", "Bagauma", "Balawing", "Balete", "Bangon", "Cabangcalan", "Cabas-An", "Calanay", "Capsay", "Concepcion", "Dayhagan", "Don Pablo Dela Rosa", "Gumahang", "Jaboyoan", "Lanang", "Luy-a", "Macabug", "Malubi", "Managanaga", "Manamoc", "Mariposa", "Mataba", "Matalangtalang", "Matangog", "Nabongsoran", "Pangle", "Panique", "Pinanaan", "Poblacion", "Puro", "San Agustin", "San Isidro", "Sawang", "Syndicate", "Talabaan", "Talib", "Tigbao", "Tinago", "Tinigban"] },
        "Baleno": { zip: "5413", barangays: ["Baao", "Banase", "Batuila", "Cagara", "Cagpandan", "Cancahorao", "Canjunday", "Docol", "Eastern Capsay", "Gabi", "Gangao", "Lagta", "Lahong Interior", "Lahong Proper", "Lipata", "Madangcalan", "Magdalena", "Manoboc", "Obongon Diot", "Poblacion", "Polot", "Potoson", "Sog-ong", "Tinapian"] },
        "Balud": { zip: "5412", barangays: ["Bayanihan", "Bongcanaway", "Mabuhay (Bongcanaway III)", "Calumpang", "Cantil", "Casamongan", "Dao", "Danao", "Guinbanwahan", "Ilaya", "Jangan", "Jintotolo", "Mapili", "Mapitogo", "Pajo", "Palane", "Panguiranan", "Panubigan", "Poblacion (Balud)", "Pulanduta", "Quinayangan Diotay", "Quinayangan Tonga", "Salvacion", "Sampad", "San Andres (Quinayangan Daku)", "San Antonio", "Sapatos", "Talisay", "Tonga", "Ubo", "Victory (Victoria)", "Villa Alvarez"] },
        "Batuan": { zip: "5415", barangays: ["Burgos", "Cañares", "Cambaño", "Costa Rica", "Danao", "Gibraltar", "Mabuhay", "Matabao", "Nasandig", "Panisihan", "Poblacion", "Rizal", "Royroy", "Sawang"] },
        "Cataingan": { zip: "5405", barangays: ["Abaca", "Aguada", "Badiang", "Bagumbayan", "Cadulawan", "Cagbatang", "Chimenea", "Concepcion", "Curvada", "Divisoria", "Domorog", "Estampar", "Gahit", "Libtong", "Liong", "Maanahao", "Madamba", "Malobago", "Matayum", "Matubinao", "Mintac", "Nadawisan", "Osmeña", "Pawican", "Poblacion", "Quezon", "San Isidro", "San Jose", "San Pedro", "San Rafael", "Santa Teresita", "Santo Niño", "Tagboan", "Tuybo", "Villa Pogado"] },
        "Cawayan": { zip: "5409", barangays: ["Begia", "Cabayugan", "Cabungahan", "Calapayan", "Calumpang", "Dalipe", "Divisoria", "Guiom", "Gilutongan", "Itombato", "Libertad", "Looc", "Mactan", "Madbad", "Mahayahay", "Maihao", "Malbug", "Naro", "Pan-an", "Pena Island", "Pin-As", "Iraya", "Poblacion", "Pulot", "Recodo", "San Jose", "San Vicente", "Taberna", "Talisay", "Tubog", "Tuburan", "Villahermosa", "R.M. Magbalon (Bebinan)"] },
        "Claveria": { zip: "5419", barangays: ["Albasan", "Boca Engaño", "Buyo", "Calpi", "Canomay", "Cawayan", "Imelda", "Mababang Baybay", "Mabiton", "Manapao", "Nabasagan", "Nonoc", "Osmeña", "Pasig", "Peñafrancia", "Poblacion District I (Barangay 1)", "Poblacion District II (Barangay 2)", "Quezon", "San Isidro", "San Ramon", "San Vicente", "Taguilid"] },
        "Dimasalang": { zip: "5403", barangays: ["Balantay", "Balocawe", "Banahao", "Buenaflor", "Buracan", "Cabanoyoan", "Cabrera", "Cadulan", "Calabad", "Canomay", "Divisoria", "Gaid", "Gregorio Alino (Pia-ong)", "Magcaraguit", "Mambog", "Poblacion", "Rizal", "San Vicente", "Suba", "T.R. Yangco (Yanco)"] },
        "Esperanza": { zip: "5407", barangays: ["Agoho", "Almero", "Baras", "Domorog", "Guadalupe", "Iligan", "Labangtaytay", "Labrador", "Libertad", "Magsaysay", "Masbaranon", "Poblacion", "Potingbato", "Rizal", "San Roque", "Santiago", "Sorosimbajan", "Tawad", "Tunga", "Villa"] },
        "Mandaon": { zip: "5411", barangays: ["Alas", "Ayat", "Bat-Ongan", "Bugtong", "Buri", "Cabitan", "Cagmasuso", "Canomoy", "Centro", "Dayao", "Guincaiptan", "Laguinbanwa", "Lantangan", "Looc", "Mabatobato", "Maolingon", "Nailaban", "Nanipsan", "Pinamangcaan", "Poblacion", "Polo Daku", "San Juan", "San Pablo", "Santa Fe", "Tagpu", "Tumalaytay"] },
        "Masbate City": { zip: "5400", barangays: ["Anas", "Asid", "B. Titong", "Bagumbayan", "Bantigue", "Bapor (Poblacion)", "Batuhan", "Bayombon", "Biyong", "Bolo", "Cagay", "Cawayan Exterior", "Cawayan Interior", "Centro (Poblacion)", "Espinosa", "F. Magallanes", "Ibingay", "Igbo", "Kalipay (Poblacion)", "Kinamaligan", "Malinta", "Mapiña", "Maingaran", "Nursery", "Pating (Poblacion)", "Pawa", "Sinalongan", "Tugbo", "Ubongan Daku", "Usab"] },
        "Milagros": { zip: "5410", barangays: ["Bacolod", "Bangad", "Bara", "Bonbon", "Calasuche", "Calumpang (Taisan)", "Capaculan", "Cayabon", "Guinluthangan", "Jamorawon", "Magsalangi", "Matagbac", "Matanglad", "Matiporon", "Moises R. Espinosa", "Narangasan", "Pamangpangon", "Poblacion East", "Poblacion West", "Paraiso (Potot)", "San Antonio", "San Carlos", "San Jose", "San Pablo", "Sawang", "Tagbon", "Tawad", "Tigbao", "Tinaclipan (Bato)"] },
        "Mobo": { zip: "5401", barangays: ["Baang", "Bagacay", "Balatucan", "Barag", "Dacu", "Fabrica", "Guintorelan", "Holjogon", "Lalaguna", "Lomocloc", "Luyong Catungan", "Mabuhay", "Mandali", "Mapuyo", "Marintoc", "Nasunduan", "Pinamalatican", "Pinamarbuhan", "Poblacion Dist. I", "Poblacion Dist. II", "Polot", "Sambulawan", "Santa Maria", "Sawang", "Tabuc", "Tugawe", "Tugbo", "Umabay Exterior", "Umabay Interior"] },
        "Monreal": { zip: "5418", barangays: ["Cantorna", "Famosa", "Guinhadap", "Macarthur", "Maglambong", "Morocborocan", "Poblacion", "Real", "Rizal", "Santo Niño", "Togoron"] },
        "Palanas": { zip: "5404", barangays: ["Antipolo", "Banco", "Biga-a", "Bontod", "Buenavista", "Intusan", "Jose A. Abenir Sr. (Libtong)", "Maanaod", "Mabini", "Mabuhay", "Malibas", "Maravilla", "Matugnao", "Miabas", "Nabangig", "Nipa", "Parina", "Piña", "Poblacion", "Salvacion", "San Antonio", "San Carlos", "San Isidro", "Santa Cruz"] },
        "Pio V. Corpuz": { zip: "5406", barangays: ["Alegria", "Buenavista", "Bugang", "Bugtong", "Bunducan", "Cabangrayan", "Calongongan", "Casabangan", "Guindawahan", "Labigan", "Lampuyang", "Mabuhay", "Palho", "Poblacion", "Salvacion", "Tanque", "Tubigan", "Tubog"] },
        "Placer": { zip: "5408", barangays: ["Aguada", "Ban-Ao", "Burabod", "Cabangcalan", "Calumpang", "Camayabsan", "Daanlungsod", "Dangpanan", "Daraga", "Guin-Awayan", "Guintarcan", "Libas", "Locso-An", "Luna", "Mahayag", "Mahayahay", "Manlut-Od", "Matagantang", "Naboctot", "Nagarao", "Nainday", "Naocondiot", "Pasiagon", "Pili", "Poblacion", "Puro", "Quibrada", "San Marcos", "Santa Cruz", "Taboc", "Tan-Awan", "Taverna", "Tubod", "Villa Inocencio"] },
        "San Fernando": { zip: "5416", barangays: ["Altavista", "Benitinan", "Biong", "Baybaydagat Poblacion", "Silangan Poblacion", "Magsasaka Poblacion", "Bayang Poblacion", "Buenavista", "Buenasuerte", "Buyo", "Canjonday", "Corbada", "Daplian", "Del Rosario", "Ipil", "Lahong", "Lumbia", "Magkaipit", "Minio", "Pinamoghaan", "Progreso", "Resurreccion", "Salvacion", "Sowa", "Talisay", "Valparaiso"] },
        "San Jacinto": { zip: "5417", barangays: ["Almiñe", "Bagacay", "Bagahanglad", "Bartolabac", "Burgos", "Calipat-An", "Danao", "Dorong-An Daplian", "Interior", "Jagna-An", "Luna", "Mabini", "Piña", "District I (Poblacion)", "District II (Poblacion)", "District III (Poblacion)", "District IV (Poblacion)", "Roosevelt", "San Isidro", "Santa Rosa", "Washington"] },
        "San Pascual": { zip: "5420", barangays: ["Boca Chica", "Bolod (Poblacion)", "Busing", "Dangcalan", "Halabangbaybay", "Iniwaran", "Ki-Buaya (Rizal)", "Ki-Romero (Roxas)", "Laurente", "Mabini", "Mabuhay", "Mapanique", "Nazareno", "Pinamasingan", "Quintina", "San Jose", "San Pedro", "San Rafael", "Santa Cruz", "Terraplin (Poblacion)", "Cueva", "Malaking Ilog"] },
        "Uson": { zip: "5402", barangays: ["Arado", "Armenia", "Aurora", "Badling", "Bonifacio", "Buenavista", "Campana", "Candelaria", "Centro", "Crossing", "Dapdap", "Del Carmen", "Del Rosario", "Libertad", "Madao", "Mabini", "Magsaysay", "Marcella", "Miaga", "Mongahay", "Morocborocan", "Mabuhay", "Paguihaman", "Panicijan", "Poblacion", "Quezon", "San Isidro", "San Jose", "San Mateo", "San Ramon", "San Vicente", "Santo Cristo", "Sawang", "Simawa", "Taboc"] }
      },
      "Sorsogon": {
        "Barcelona": { zip: "4712", barangays: ["Alegria", "Bagacay", "Bangate", "Bugtong", "Cagbang", "Fabrica", "Jibong", "Lago", "Layog", "Luneta", "Macabari", "Mapapac", "Olandia", "Paghaluban", "Poblacion Central", "Poblacion Norte", "Poblacion Sur", "Putiao", "San Antonio", "San Isidro", "San Ramon", "San Vicente", "Santa Cruz", "Santa Lourdes", "Tagdon"] },
        "Bulan": { zip: "4706", barangays: ["A. Bonifacio (Tinurilan)", "Abad Santos (Kambal)", "Aguinaldo (Lipata Dako)", "Antipolo", "Aquino (Imelda)", "Bical", "Beguin", "Bonga", "Butag", "Cadandanan", "Calomagon", "Calpi", "Cocok-Cabitan", "Daganas", "Danao", "Dolos", "E. Quirino (Pinangomhan)", "Fabrica", "G. Del Pilar (Tanga)", "Gate", "Inararan", "J. Gerona (Biton)", "J.P. Laurel (Pon-od)", "Jamorawon", "Libertad (Calle Putol)", "Lajong", "Magsaysay (Bontog)", "Managa-naga", "Marinab", "Nasuje", "Montecalvario", "N. Roque (Rizal)", "Namo", "Obrero", "Osmeña (Lipata Saday)", "Otavi", "Padre Diaz", "Palale", "Quezon (Cabarawan)", "R. Gerona (Butag)", "Recto", "Roxas (Busay)", "Sagrada", "San Francisco (Polot)", "San Isidro (Cabugaan)", "San Juan Bag-o", "San Juan Daan", "San Rafael (Togbongon)", "San Ramon", "San Vicente", "Santa Remedios", "Santa Teresita (Trece)", "Sigad", "Somagongsong", "Taromata", "Zone 1 (Ilawod)", "Zone 2 (Sabang)", "Zone 3 (Central)", "Zone 4 (Central Business District)", "Zone 5 (Canipaan)", "Zone 6 (Baybay)", "Zone 7 (Iraya)", "Zone 8 (Loyo)"] },
        "Bulusan": { zip: "4704", barangays: ["Bagacay", "Central (Poblacion)", "Cogon", "Dancalan", "Dapdap (Poblacion)", "Lalud", "Looban (Poblacion)", "Mabuhay (Poblacion)", "Madlawon (Poblacion)", "Poctol (Poblacion)", "Porog", "Sabang (Poblacion)", "Salvacion", "San Antonio", "San Bernardo", "San Francisco", "San Isidro", "San Jose", "San Rafael", "San Roque", "San Vicente", "Santa Barbara", "Sapngan (Poblacion)", "Tinampo"] },
        "Casiguran": { zip: "4702", barangays: ["Adovis (Poblacion)", "Boton", "Burgos", "Casay", "Cawit", "Central (Poblacion)", "Colambis", "Escuala", "Cogon", "Inlagadian", "Lungib", "Mabini", "Ponong", "Rizal", "San Antonio (Buhang)", "San Isidro", "San Juan", "San Pascual", "Santa Cruz", "Somal-ot (Poblacion)", "Tigbao", "Timbayog (Poblacion)", "Tiris", "Trece Martirez", "Tulay"] },
        "Castilla": { zip: "4713", barangays: ["Amomonting", "Bagalayag", "Bagong Sirang", "Bonga", "Buenavista", "Burabod", "Caburihan", "Canjela", "Cogon", "Cumadcad", "Dangcalan", "Dinapa", "La Union", "Libtong", "Loreto", "Macalaya", "Maracabac", "Mayon", "Milagrosa", "Miluya", "Maypangi", "Monte Carmelo", "Oras", "Pandan", "Poblacion", "Quirapi", "Saclayan", "Salvacion", "San Isidro", "San Rafael", "San Roque", "San Vicente", "Sogoy", "Tomala"] },
        "Donsol": { zip: "4715", barangays: ["Alin", "Awai", "Banban", "Bandi", "Banuang Gurang", "Baras", "Bayawas", "Bororan Barangay 1 (Poblacion)", "Cabugao", "Central Barangay 2 (Poblacion)", "Cristo", "Dancalan", "De Vera", "Gimagaan", "Girawan", "Gogon", "Gura", "Juan Adre", "Lourdes", "Mabini", "Malapoc", "Malinao", "Market Site Barangay 3 (Poblacion)", "New Maguisa", "Ogod (Crossing)", "Old Maguisa", "Orange", "Pangpang", "Parina", "Pawala", "Pinamanaan", "Poso Poblacion (Barangay 4)", "Punta Waling-Waling Poblacion (Barangay 5)", "Quinabucasan", "San Antonio", "San Isidro", "San Jose", "San Rafael", "San Ramon", "San Vicente", "Santa Cruz", "Sevilla", "Sibago", "Suguian", "Tagbac", "Tinanogan", "Tongdol", "Tres Marias", "Tuba", "Tupas", "Vinisitahan"] },
        "Gubat": { zip: "4710", barangays: ["Ariman", "Bagacay", "Balud Del Norte (Poblacion)", "Balud Del Sur (Poblacion)", "Benguet", "Bentuco", "Beriran", "Buenavista", "Bulacao", "Cabigaan", "Cabiguhan", "Carriedo", "Casili", "Cogon", "Cota Na Daco (Poblacion)", "Dita", "Jupi", "Lapinig", "Luna-Candol (Poblacion)", "Manapao", "Manook (Poblacion)", "Naagtan", "Nato", "Nazareno", "Ogao", "Paco", "Panganiban (Poblacion)", "Paradijon (Poblacion)", "Patag", "Payawin", "Pinontingan (Poblacion)", "Rizal", "San Ignacio", "Sangat", "Santa Ana", "Tabi", "Tagaytay", "Tigkiw", "Tiris", "Togawe", "Union", "Villareal"] },
        "Irosin": { zip: "4707", barangays: ["Bagsangan", "Bacolod (Poblacion)", "Batang", "Bolos", "Buenavista", "Bulawan", "Carriedo", "Casini", "Cawayan", "Cogon", "Gabao", "Gulang-Gulang", "Gumapia", "Santo Domingo (Lamboon)", "Liang", "Macawayan", "Mapaso", "Monbon", "Patag", "Salvacion", "San Agustin (Poblacion)", "San Isidro", "San Juan (Poblacion)", "San Julian (Poblacion)", "San Pedro", "Tabon-Tabon", "Tinampo", "Tongdol"] },
        "Juban": { zip: "4703", barangays: ["Anog", "Aroroy", "Bacolod", "Binanuahan", "Biriran", "Buraburan", "Calateo", "Calmayon", "Carohayon", "Catanagan", "Catanusan", "Cogon", "Embarcadero", "Guruyan", "Lajong", "Maalo", "North Poblacion", "Putingsapa", "Rangasan", "Sablayan", "Sipaya", "South Poblacion", "Taboc", "Tinago", "Tughan"] },
        "Magallanes": { zip: "4705", barangays: ["Aguada Norte", "Aguada Sur", "Anibong", "Bacalon", "Bacolod", "Banacud", "Biga", "Behia", "Binisitahan del Norte", "Binisitahan del Sur", "Biton", "Bulala", "Busay", "Caditaan", "Cagbolo", "Cagtalaba", "Cawit Extension", "Cawit Proper", "Ginangra", "Hubo", "Incarizan", "Lapinig", "Magsaysay", "Malbog", "Pantalan", "Pawik", "Pili", "Poblacion", "Salvacion", "Santa Elena", "Siuton", "Tagas", "Tulatula Norte", "Tulatula Sur"] },
        "Matnog": { zip: "4708", barangays: ["Balocawe", "Banogao", "Banuangdaan", "Bariis", "Bolo", "Bon-Ot Big", "Bon-Ot Small", "Cabagahan", "Calayuan", "Calintaan", "Caloocan (Poblacion)", "Calpi", "Camachiles (Poblacion)", "Camcaman (Poblacion)", "Coron-coron", "Culasi", "Gadgaron", "Genablan Occidental", "Genablan Oriental", "Hidhid", "Laboy", "Lajong", "Mambajog", "Manjumlad", "Manurabi", "Naburacan", "Paghuliran", "Pange", "Pawa", "Poropandan", "Santa Isabel", "Sinalmacan", "Sinang-Atan", "Sinibaran", "Sisigon", "Sua", "Sulangan", "Tablac (Poblacion)", "Tabunan (Poblacion)", "Tugas"] },
        "Pilar": { zip: "4714", barangays: ["Abas", "Abucay", "Bantayan", "Banuyo (Poblacion)", "Bayasong", "Bayawas", "Binanuahan (Poblacion)", "Cabiguan", "Cagdongon", "Calongay", "Calpi", "Catamlangan", "Comapo-capo", "Danao", "Dao (Poblacion)", "Del Rosario", "Esmerada", "Esperanza", "Ginablan", "Guintinua", "Leigh", "Lourdes", "Lungib", "Lumbang", "Mabanate", "Malbog", "Marifosque (Poblacion)", "Mercedes", "Migabod", "Naspi", "Palayang", "Pangpang", "Pinagsalog", "Pineda", "Poctol", "Pudo", "Putiao", "Sacnangan", "Salvacion", "San Antonio (Millabas)", "San Antonio (Sapa)", "San Fernando", "San Isidro", "San Jose", "San Juan", "San Miguel", "San Rafael", "San Ramon", "San Vicente", "Santa Fe", "Sapa", "Tabiguian", "Tingco"] },
        "Prieto Diaz": { zip: "4711", barangays: ["Brillante (Poblacion)", "Bulawan", "Calao", "Carayat", "Diamante", "Gogon", "Lupi", "Manlabong", "Maningcay De Oro", "Perlas", "Quidolog", "Rizal", "San Antonio", "San Fernando", "San Isidro", "San Juan", "San Rafael", "San Ramon", "Santa Lourdes", "Santo Domingo", "Talisayan", "Tupaz", "Ulag"] },
        "Santa Magdalena": { zip: "4709", barangays: ["Barangay Poblacion I", "Barangay Poblacion II", "Barangay Poblacion III", "Barangay Poblacion IV", "La Esperanza", "Peñafrancia", "Salvacion", "San Antonio", "San Bartolome", "San Eugenio", "San Isidro", "San Rafael", "San Roque", "San Sebastian"] },
        "Sorsogon City": { zip: "4700", barangays: ["Abuyog", "Almendras-Cogon (Poblacion)", "Balete", "Balogo", "Barayong", "Basud", "Bibincahan", "Bitan-o/Dalipay (Poblacion)", "Bucalbucalan", "Buenavista", "Buhatan", "Bulabog", "Burabod (Poblacion)", "Cabid-An", "Cambulaga", "Capuy", "Caricaran", "Del Rosario", "Gimaloto", "Guinlajon", "Macabog", "Marinas", "Osorio", "Pamurayan", "Pangpang", "Panlayaan", "Peñafrancia", "Piot (Poblacion)", "Polvorista (Poblacion)", "Rizal", "Salog (Poblacion)", "Salvacion", "Sampaloc (Poblacion)", "San Isidro", "San Juan (Roro)", "Sirangan (Poblacion)", "Sulucan (Poblacion)", "Talisay (Poblacion)", "Ticol", "Tugos", "Gatbo", "Jamislagan", "Magsaysay", "Manapao", "Masac-sac", "San Pascual", "San Ramon", "San Roque", "San Vicente", "Santa Cruz", "Santa Lucia", "Santo Domingo", "Santo Niño", "Sawanga", "Sugod", "Bato", "Bon-ot", "Guinlajon", "Lidong", "Mabini", "Manlabong", "Rawis", "San Lorenzo", "Talaonga"] }
      }
    };

    const GADGET_CATALOG = {
      Smartphone: {
        "Apple": ["iPhone 16 Pro Max", "iPhone 16 Pro", "iPhone 16", "iPhone 15 Pro Max", "iPhone 15 Pro", "iPhone 15", "iPhone 14 Pro Max", "iPhone 14", "iPhone 13 Pro", "iPhone 13", "iPhone 12", "iPhone 11", "iPhone SE (3rd Gen)"],
        "Samsung": ["Galaxy S24 Ultra", "Galaxy S24+", "Galaxy S24", "Galaxy S23 Ultra", "Galaxy S22", "Galaxy S21 FE", "Galaxy A55 5G", "Galaxy A54 5G", "Galaxy A35 5G", "Galaxy A15", "Galaxy Z Flip 5", "Galaxy Z Fold 5"],
        "Xiaomi": ["Xiaomi 14 Ultra", "Xiaomi 14", "Xiaomi 13T Pro", "Redmi Note 13 Pro+ 5G", "Redmi Note 13", "Redmi Note 12", "Redmi 13C", "POCO X6 Pro", "POCO F6"],
        "OPPO": ["Reno 12 Pro 5G", "Reno 11 F 5G", "Reno 10 5G", "OPPO A98 5G", "OPPO A78", "OPPO A58", "OPPO A18", "Find N3 Flip"],
        "Vivo": ["Vivo V30 Pro", "Vivo V29 5G", "Vivo V27 5G", "Vivo Y36", "Vivo Y28", "Vivo Y17s", "Vivo X100 Pro"],
        "Realme": ["Realme 12 Pro+ 5G", "Realme 11 Pro", "Realme C67", "Realme C55", "Realme Narzo 70 Pro"],
        "Infinix": ["Infinix Note 40 Pro+ 5G", "Infinix Note 30 5G", "Infinix GT 20 Pro", "Infinix Hot 40 Pro", "Infinix Zero 30 5G"],
        "Tecno": ["Tecno Camon 30 Pro", "Tecno Pova 6 Pro 5G", "Tecno Spark 20 Pro+"],
        "Huawei": ["Huawei Pura 70 Pro", "Huawei Nova 12i", "Huawei Nova 11", "Huawei P60 Pro"],
        "Google": ["Pixel 9 Pro", "Pixel 8 Pro", "Pixel 8", "Pixel 7a", "Pixel 6a"],
        "OnePlus": ["OnePlus 12", "OnePlus 12R", "OnePlus Nord 3 5G", "OnePlus Nord CE 4"],
        "Honor": ["Honor Magic 6 Pro", "Honor X9b 5G", "Honor 90 5G", "Honor X7b"]
      },
      Tablet: {
        "Apple": ["iPad Pro 13-inch (M4)", "iPad Pro 11-inch (M4)", "iPad Air 11-inch (M2)", "iPad Air (5th Gen)", "iPad (10th Gen)", "iPad (9th Gen)", "iPad mini (6th Gen)"],
        "Samsung": ["Galaxy Tab S9 Ultra", "Galaxy Tab S9 FE", "Galaxy Tab S8", "Galaxy Tab A9+", "Galaxy Tab A8"],
        "Xiaomi": ["Xiaomi Pad 6", "Xiaomi Pad 5", "Redmi Pad Pro", "Redmi Pad SE"],
        "Huawei": ["MatePad Pro 13.2", "MatePad 11.5", "MatePad SE"],
        "Lenovo": ["Lenovo Tab P12", "Lenovo Tab M11", "Lenovo Legion Tab"],
        "Honor": ["Honor Pad 9", "Honor Pad X9"]
      },
      Laptop: {
        "Apple": ["MacBook Air 15-inch (M3)", "MacBook Air 13-inch (M2)", "MacBook Air (M1)", "MacBook Pro 16-inch (M3 Pro)", "MacBook Pro 14-inch (M3)", "MacBook Pro 13-inch (M1)"],
        "ASUS": ["ROG Zephyrus G14", "ROG Strix G16", "TUF Gaming A15", "TUF Gaming F15", "Zenbook 14 OLED", "Vivobook 15", "Vivobook Go 15"],
        "Lenovo": ["ThinkPad X1 Carbon", "ThinkPad T14", "IdeaPad Slim 5", "IdeaPad Gaming 3", "Legion Pro 5i", "LOQ 15"],
        "Acer": ["Nitro V 15", "Nitro 5", "Predator Helios Neo 16", "Aspire 5", "Aspire Lite 15", "Swift Go 14"],
        "HP": ["Victus 15", "Omen 16", "Pavilion 15", "Envy x360", "HP 15s", "EliteBook 840"],
        "Dell": ["XPS 13", "XPS 15", "Inspiron 15 3520", "Inspiron 14 5420", "Latitude 5440", "G15 Gaming"],
        "MSI": ["Katana 15", "Cyborg 15", "Thin GF63", "Modern 14", "Prestige 14"],
        "Huawei": ["MateBook D 16", "MateBook D 15", "MateBook 14"]
      },
      Desktop: {
        "Apple": ["iMac 24-inch (M3)", "Mac mini (M2)", "Mac Studio"],
        "ASUS": ["ROG Strix G16CH", "ExpertCenter D5", "Custom Build (ASUS Motherboard)"],
        "Dell": ["OptiPlex 7010", "Inspiron Desktop", "Alienware Aurora R16"],
        "HP": ["ProDesk 400", "Pavilion Desktop", "OMEN 25L"],
        "Lenovo": ["ThinkCentre M70q", "IdeaCentre AIO 3", "Legion Tower 5i"],
        "Custom PC": ["Custom Gaming Rig", "Custom Office Desktop", "Custom Workstation"]
      },
      Console: {
        "Sony": ["PlayStation 5 Slim", "PlayStation 5 Disc Edition", "PlayStation 5 Digital", "PlayStation 4 Pro", "PlayStation 4 Slim", "PlayStation Portal"],
        "Nintendo": ["Nintendo Switch OLED", "Nintendo Switch V2", "Nintendo Switch Lite"],
        "Microsoft": ["Xbox Series X", "Xbox Series S", "Xbox One S"],
        "Valve": ["Steam Deck OLED", "Steam Deck LCD"],
        "ASUS": ["ROG Ally X", "ROG Ally Z1 Extreme"],
        "Lenovo": ["Legion Go"]
      },
      Accessory: {
        "Apple": ["AirPods Pro (2nd Gen)", "AirPods (3rd Gen)", "Apple Watch Series 9", "Apple Watch SE", "Apple Pencil (2nd Gen)", "Magic Keyboard"],
        "Logitech": ["G Pro X Gaming Headset", "G Pro X Superlight", "G502 Hero", "MX Master 3S", "K380 Wireless Keyboard"],
        "Razer": ["DeathAdder V3 Pro", "BlackWidow V4", "Barracuda X"],
        "Sony": ["DualSense Wireless Controller", "WH-1000XM5", "WF-1000XM5"],
        "Samsung": ["Galaxy Watch 6", "Galaxy Buds2 Pro"],
        "JBL": ["Tune 720BT", "Flip 6", "Charge 5"]
      },
      Other: {
        "Canon": ["PIXMA G3010", "EOS M50 Mark II"],
        "Epson": ["EcoTank L3210", "EcoTank L210"],
        "DJI": ["Mini 4 Pro", "Osmo Pocket 3"],
        "GoPro": ["HERO12 Black", "HERO11 Black"]
      }
    };

    const retInput = document.getElementById('returningCustomerSelect');
    const existingIdInput = document.getElementById('existing_customer_id');
    const contactInput = document.getElementById('contact_number');
    const opts = Array.from(document.querySelectorAll('#customerDatalist option'));

    const modeRegionV = document.getElementById('modeRegionV');
    const modeForeign = document.getElementById('modeForeign');
    const pillRegionV = document.getElementById('pillRegionV');
    const pillForeign = document.getElementById('pillForeign');
    const regionVFields = document.getElementById('regionVFields');
    const foreignAddressFields = document.getElementById('foreignAddressFields');
    const foreignAddressInput = document.getElementById('foreign_address');

    const provinceSelect = document.getElementById('province');
    const citySelect = document.getElementById('city');
    const barangayInput = document.getElementById('barangay');
    const barangayDatalist = document.getElementById('barangayDatalist');
    const zipInput = document.getElementById('zip_code');
    const zipHint = document.getElementById('zipHint');

    const categorySelect = document.getElementById('category');
    const brandInput = document.getElementById('brand');
    const brandDatalist = document.getElementById('brandDatalist');
    const modelInput = document.getElementById('model');
    const modelDatalist = document.getElementById('modelDatalist');
    const serialInput = document.getElementById('serial_number');
    const serialCounter = document.getElementById('serialCounter');

    // Auto-capitalize first letter of every word, rest lowercase, while preserving caret position
    function capitalizeFirstLowerRest(str) {
      return str.replace(/([^\p{L}\p{N}]*)([\p{L}\p{N}])([\p{L}\p{N}]*)/gu, (_, prefix, first, rest) => {
        return prefix + first.toUpperCase() + rest.toLowerCase();
      });
    }

    const autoCapFields = [
      document.getElementById('first_name'),
      document.getElementById('middle_name'),
      document.getElementById('last_name'),
      barangayInput,
      foreignAddressInput,
      brandInput,
      modelInput,
      document.getElementById('reported_issue'),
      document.getElementById('initial_condition'),
      document.getElementById('accessories_included')
    ];

    autoCapFields.forEach((el) => {
      if (!el) return;
      el.addEventListener('input', () => {
        const start = el.selectionStart;
        const end = el.selectionEnd;
        const formatted = capitalizeFirstLowerRest(el.value);
        if (el.value !== formatted) {
          el.value = formatted;
          if (typeof start === 'number' && typeof end === 'number') {
            el.setSelectionRange(start, end);
          }
        }
      });
    });

    function isForeignAddressMode() {
      return modeForeign.checked;
    }

    function setAddressMode(foreign) {
      modeForeign.checked = foreign;
      modeRegionV.checked = !foreign;

      pillForeign.classList.toggle('active', foreign);
      pillRegionV.classList.toggle('active', !foreign);

      regionVFields.style.display = foreign ? 'none' : 'block';
      foreignAddressFields.style.display = foreign ? 'block' : 'none';

      provinceSelect.required = !foreign;
      citySelect.required = !foreign;
      barangayInput.required = !foreign;
      foreignAddressInput.required = foreign;
    }

    modeRegionV.addEventListener('change', () => setAddressMode(false));
    modeForeign.addEventListener('change', () => setAddressMode(true));

    contactInput.addEventListener('input', () => {
      const digitsOnly = contactInput.value.replace(/\D/g, '').slice(0, 11);
      if (contactInput.value !== digitsOnly) {
        contactInput.value = digitsOnly;
      }
    });

    // Serial / IMEI: Caps Lock (uppercase) + 15-character limit
    function updateSerialInput() {
      const start = serialInput.selectionStart;
      const end = serialInput.selectionEnd;
      const upperCapped = serialInput.value.toUpperCase().slice(0, 15);
      if (serialInput.value !== upperCapped) {
        serialInput.value = upperCapped;
        if (typeof start === 'number' && typeof end === 'number') {
          serialInput.setSelectionRange(start, end);
        }
      }
      serialCounter.textContent = `${serialInput.value.length} / 15 characters`;
    }
    serialInput.addEventListener('input', updateSerialInput);
    updateSerialInput();

    function populateCities(provinceName, selectedCity = '', preserveZip = false) {
      const citiesObj = REGION_V_DATA[provinceName] || null;
      citySelect.innerHTML = '';

      if (!provinceName) {
        citySelect.disabled = true;
        citySelect.innerHTML = '<option value="">Select Province First...</option>';
        barangayDatalist.innerHTML = '';
        if (!preserveZip) zipInput.value = '';
        return;
      }

      citySelect.disabled = false;
      const placeholder = document.createElement('option');
      placeholder.value = '';
      placeholder.textContent = 'Select City / Municipality...';
      citySelect.appendChild(placeholder);

      if (citiesObj) {
        Object.keys(citiesObj).sort().forEach(cityName => {
          const opt = document.createElement('option');
          opt.value = cityName;
          opt.textContent = cityName;
          citySelect.appendChild(opt);
        });
      }

      if (selectedCity) {
        const exists = Array.from(citySelect.options).some(o => o.value.toLowerCase() === selectedCity.toLowerCase());
        if (!exists) {
          const customOpt = document.createElement('option');
          customOpt.value = selectedCity;
          customOpt.textContent = selectedCity;
          citySelect.appendChild(customOpt);
        }
        const matchedOpt = Array.from(citySelect.options).find(o => o.value.toLowerCase() === selectedCity.toLowerCase());
        if (matchedOpt) citySelect.value = matchedOpt.value;
      }

      handleCityChange(preserveZip);
    }

    function handleCityChange(preserveZip = false) {
      const prov = provinceSelect.value;
      const city = citySelect.value;
      const entry = REGION_V_DATA[prov]?.[city] || null;

      barangayDatalist.innerHTML = '';
      if (entry) {
        entry.barangays.forEach(brgy => {
          const opt = document.createElement('option');
          opt.value = brgy;
          barangayDatalist.appendChild(opt);
        });
        if (!preserveZip || !zipInput.value) {
          zipInput.value = entry.zip;
        }
        zipHint.textContent = `Detected ZIP code for ${city}, ${prov}: ${entry.zip}`;
      } else {
        if (!preserveZip) zipInput.value = '';
        zipHint.textContent = 'Auto-fills when City/Municipality is selected.';
      }
    }

    provinceSelect.addEventListener('change', () => {
      barangayInput.value = '';
      populateCities(provinceSelect.value, '', false);
    });

    citySelect.addEventListener('change', () => {
      barangayInput.value = '';
      handleCityChange(false);
    });

    function updateBrandRecommendations() {
      const cat = categorySelect.value;
      const catBrands = GADGET_CATALOG[cat] || {};
      const allBrands = Object.keys(catBrands);

      brandDatalist.innerHTML = '';
      allBrands.forEach(b => {
        const opt = document.createElement('option');
        opt.value = b;
        brandDatalist.appendChild(opt);
      });

      updateModelRecommendations();
    }

    function updateModelRecommendations() {
      const cat = categorySelect.value;
      const typedBrand = brandInput.value.trim().toLowerCase();
      const catBrands = GADGET_CATALOG[cat] || {};

      let models = [];
      if (typedBrand) {
        const matchedKey = Object.keys(catBrands).find(k => k.toLowerCase() === typedBrand)
          || Object.keys(catBrands).find(k => k.toLowerCase().includes(typedBrand));
        if (matchedKey) {
          models = catBrands[matchedKey];
        }
      } else {
        Object.values(catBrands).forEach(list => {
          models = models.concat(list.slice(0, 3));
        });
      }

      modelDatalist.innerHTML = '';
      models.forEach(m => {
        const opt = document.createElement('option');
        opt.value = m;
        modelDatalist.appendChild(opt);
      });
    }

    categorySelect.addEventListener('change', updateBrandRecommendations);
    brandInput.addEventListener('input', updateModelRecommendations);
    updateBrandRecommendations();

    retInput.addEventListener('input', () => {
      const match = opts.find(o => o.value === retInput.value);
      if (match) {
        existingIdInput.value = match.dataset.id || '';
        document.getElementById('first_name').value = capitalizeFirstLowerRest(match.dataset.first || '');
        document.getElementById('middle_name').value = capitalizeFirstLowerRest(match.dataset.middle || '');
        document.getElementById('last_name').value = capitalizeFirstLowerRest(match.dataset.last || '');
        contactInput.value = (match.dataset.phone || '').replace(/\D/g, '').slice(0, 11);
        document.getElementById('email').value = match.dataset.email || '';

        const savedProv = (match.dataset.province || '').trim();
        const savedCity = (match.dataset.city || '').trim();
        const savedBrgy = (match.dataset.barangay || '').trim();
        const savedZip = (match.dataset.zip || '').trim();

        const isForeignSaved =
          savedProv.toLowerCase().includes('foreign') ||
          savedProv.toLowerCase().includes('outside region v') ||
          savedBrgy.toLowerCase().includes('foreign');

        if (isForeignSaved) {
          setAddressMode(true);
          foreignAddressInput.value = capitalizeFirstLowerRest(savedCity || '');
        } else {
          setAddressMode(false);
          foreignAddressInput.value = '';
          if (savedProv) {
            const provMatch = Array.from(provinceSelect.options).find(
              o => o.value.toLowerCase() === savedProv.toLowerCase()
            );
            if (!provMatch) {
              const extraProv = document.createElement('option');
              extraProv.value = savedProv;
              extraProv.textContent = savedProv;
              provinceSelect.appendChild(extraProv);
              provinceSelect.value = savedProv;
            } else {
              provinceSelect.value = provMatch.value;
            }
          } else {
            provinceSelect.value = '';
          }

          zipInput.value = savedZip;
          populateCities(provinceSelect.value, savedCity, Boolean(savedZip));
          barangayInput.value = capitalizeFirstLowerRest(savedBrgy);
        }
      } else {
        existingIdInput.value = '';
      }
    });

    document.getElementById('newReqForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      const errEl = document.getElementById('reqErr');
      errEl.textContent = '';

      const contactVal = contactInput.value.trim();
      if (contactVal.length !== 11) {
        errEl.textContent = 'Contact Number Must Be Exactly 11 Digits.';
        contactInput.focus();
        return;
      }

      const foreignMode = isForeignAddressMode();
      const foreignAddrVal = capitalizeFirstLowerRest(foreignAddressInput.value.trim());
      if (foreignMode && !foreignAddrVal) {
        errEl.textContent = 'Please Enter The Customer Address.';
        foreignAddressInput.focus();
        return;
      }

      const serialVal = serialInput.value.trim().toUpperCase();
      if (serialVal.length > 15) {
        errEl.textContent = 'Serial Number Or IMEI Must Be 15 Characters Or Less.';
        serialInput.focus();
        return;
      }

      const payload = foreignMode
        ? {
            existing_customer_id: existingIdInput.value,
            first_name: capitalizeFirstLowerRest(document.getElementById('first_name').value.trim()),
            middle_name: capitalizeFirstLowerRest(document.getElementById('middle_name').value.trim()),
            last_name: capitalizeFirstLowerRest(document.getElementById('last_name').value.trim()),
            contact_number: contactVal,
            email: document.getElementById('email').value.trim(),
            province: 'Outside Region V / Foreign',
            city: foreignAddrVal,
            barangay: 'Foreign / Outside Region V',
            zip_code: 'N/A'
          }
        : {
            existing_customer_id: existingIdInput.value,
            first_name: capitalizeFirstLowerRest(document.getElementById('first_name').value.trim()),
            middle_name: capitalizeFirstLowerRest(document.getElementById('middle_name').value.trim()),
            last_name: capitalizeFirstLowerRest(document.getElementById('last_name').value.trim()),
            contact_number: contactVal,
            email: document.getElementById('email').value.trim(),
            province: provinceSelect.value.trim(),
            city: citySelect.value.trim(),
            barangay: capitalizeFirstLowerRest(barangayInput.value.trim()),
            zip_code: zipInput.value.trim()
          };

      try {
        const res = await fetch('api/endpoints.php?action=create_request_full', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            ...payload,
            category: categorySelect.value,
            brand: capitalizeFirstLowerRest(brandInput.value.trim()),
            model: capitalizeFirstLowerRest(modelInput.value.trim()),
            serial_number: serialVal,
            reported_issue: capitalizeFirstLowerRest(document.getElementById('reported_issue').value.trim()),
            initial_condition: capitalizeFirstLowerRest(document.getElementById('initial_condition').value.trim()),
            accessories_included: capitalizeFirstLowerRest(document.getElementById('accessories_included').value.trim()),
            assigned_tech_id: document.getElementById('assigned_tech_id').value
          })
        });
        const data = await res.json();
        if (res.ok && data.ok) {
          window.location.href = `request_detail.php?id=${encodeURIComponent(data.request_id)}`;
        } else {
          errEl.textContent = data.error || data.message || 'Could Not Create Request.';
        }
      } catch (err) {
        errEl.textContent = 'Unable To Reach The Server. Check Apache And MySQL In XAMPP.';
      }
    });
  </script>
</body>
</html>
