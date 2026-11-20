<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Create Staff Account — Gadget Repair Shop</title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    .auth-shell {
      min-height: 100vh;
      display: grid;
      grid-template-columns: 280px 1fr;
      background: #f8fafc;
    }
    .auth-aside {
      background: #0f172a;
      color: #cbd5e1;
      padding: 32px 24px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    .auth-aside h2 {
      color: #ffffff;
      font-size: 20px;
      margin-bottom: 8px;
    }
    .auth-aside p {
      font-size: 13px;
      color: #94a3b8;
      line-height: 1.5;
    }
    .auth-main {
      display: grid;
      place-items: center;
      padding: 28px;
    }
    .auth-card {
      width: 100%;
      max-width: 480px;
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 30px;
    }
    .brand-tag {
      color: #059669;
      font-weight: 700;
      font-size: 13px;
      margin-bottom: 6px;
    }
    .auth-card h1 {
      font-size: 24px;
      font-weight: 700;
      margin-bottom: 4px;
    }
    .auth-sub {
      color: #64748b;
      font-size: 13px;
      margin-bottom: 20px;
    }
    .grid-3 {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 12px;
    }
    .grid-2 {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
    }
    .field {
      margin-bottom: 14px;
    }
    .field label {
      display: block;
      font-size: 13px;
      font-weight: 600;
      margin-bottom: 6px;
    }
    .req-star {
      color: #dc2626;
      margin-left: 2px;
    }
    .field-hint {
      display: block;
      font-size: 11.5px;
      color: #64748b;
      margin-top: 4px;
    }
    .field input,
    .field select {
      width: 100%;
      padding: 10px 12px;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      font: inherit;
      background: #ffffff;
      color: #0f172a;
    }
    .pw-wrap {
      position: relative;
      display: flex;
      align-items: center;
    }
    .pw-wrap input {
      padding-right: 42px;
    }
    .eye-btn {
      position: absolute;
      right: 8px;
      width: 30px;
      height: 30px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: transparent;
      border: none;
      border-radius: 6px;
      color: #64748b;
      cursor: pointer;
      user-select: none;
      -webkit-user-select: none;
      touch-action: manipulation;
    }
    .eye-btn:hover,
    .eye-btn.active {
      color: #0f172a;
      background: #f1f5f9;
    }
    .msg-err {
      margin-top: 10px;
      color: #b91c1c;
      font-size: 13px;
      font-weight: 500;
    }
    .switch-link {
      margin-top: 16px;
      padding-top: 14px;
      border-top: 1px solid #e2e8f0;
      font-size: 13px;
      text-align: center;
    }
    .switch-link a {
      color: #059669;
      font-weight: 600;
      text-decoration: none;
    }
    @media (max-width: 820px) {
      .auth-shell {
        grid-template-columns: 1fr;
      }
      .auth-aside {
        display: none;
      }
      .grid-3,
      .grid-2 {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <div class="auth-shell">
    <aside class="auth-aside">
      <div>
        <h2>Gadget Repair Management System</h2>
        <p>Create a Staff Account to Access Repair Ticket Intake, Diagnostics, Billing, and Customer Records.</p>
      </div>
      <p style="font-size:12px;">Passwords are Hashed Securely before Saving to the Database.</p>
    </aside>

    <main class="auth-main">
      <div class="auth-card">
        <div class="brand-tag">Gadget Repair Management System</div>
        <h1>Create a Staff Account</h1>
        <p class="auth-sub">Register your Staff Profile to Sign In.</p>

        <form id="signupForm">
          <div class="grid-3">
            <div class="field">
              <label for="first_name">First Name <span class="req-star">*</span></label>
              <input id="first_name" type="text" required autofocus>
            </div>
            <div class="field">
              <label for="middle_name">Middle Name</label>
              <input id="middle_name" type="text">
            </div>
            <div class="field">
              <label for="last_name">Last Name <span class="req-star">*</span></label>
              <input id="last_name" type="text" required>
            </div>
          </div>

          <div class="grid-2">
            <div class="field">
              <label for="username">Username <span class="req-star">*</span></label>
              <input id="username" type="text" required>
            </div>
            <div class="field">
              <label for="password">Password <span class="req-star">*</span></label>
              <div class="pw-wrap">
                <input
                  id="password"
                  type="password"
                  minlength="8"
                  maxlength="12"
                  required
                >
                <button
                  type="button"
                  id="togglePwBtn"
                  class="eye-btn"
                  aria-label="Show Or Hold To Peek At Password"
                  title="Click To Toggle Or Press And Hold To Peek"
                >
                  <svg id="eyeOpenIcon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                  </svg>
                  <svg id="eyeOffIcon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                    <path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"></path>
                    <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"></path>
                    <line x1="1" y1="1" x2="23" y2="23"></line>
                  </svg>
                </button>
              </div>
              <span class="field-hint">Must Be 8 To 12 Characters Long.</span>
            </div>
          </div>

          <div class="field">
            <label for="email">Email <span class="req-star">*</span></label>
            <input id="email" type="email" placeholder="e.g., staff@gadgetfix.com" required>
          </div>

          <div class="grid-2">
            <div class="field">
              <label for="contact_number">Contact Number <span class="req-star">*</span></label>
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
              <span class="field-hint">11 Digits Only (Max 11 Characters).</span>
            </div>
            <div class="field">
              <label for="role">Role <span class="req-star">*</span></label>
              <select id="role" required>
                <option value="Technician" selected>Technician</option>
                <option value="Receptionist">Receptionist</option>
                <option value="Cashier">Cashier</option>
                <option value="Admin">Admin</option>
                <option value="Manager">Manager</option>
              </select>
            </div>
          </div>

          <div class="field" id="specializationField">
            <label for="specialization">Specialization <span class="req-star">*</span></label>
            <select id="specialization" required></select>
          </div>

          <button type="submit" class="btn btn-green" style="width:100%;">Create Account</button>
          <div id="signupErr" class="msg-err"></div>
        </form>

        <div class="switch-link">
          Already have an Account? <a href="login.php">Sign In</a>
        </div>
      </div>
    </main>
  </div>

  <script>
    const pwInput = document.getElementById('password');
    const togglePwBtn = document.getElementById('togglePwBtn');
    const eyeOpenIcon = document.getElementById('eyeOpenIcon');
    const eyeOffIcon = document.getElementById('eyeOffIcon');
    const contactInput = document.getElementById('contact_number');
    const roleSelect = document.getElementById('role');
    const specializationField = document.getElementById('specializationField');
    const specializationSelect = document.getElementById('specialization');

    // Enforce digits-only and stop typing at 11 characters
    contactInput.addEventListener('input', () => {
      const digitsOnly = contactInput.value.replace(/\D/g, '').slice(0, 11);
      if (contactInput.value !== digitsOnly) {
        contactInput.value = digitsOnly;
      }
    });

    const roleSpecializations = {
      'Technician': [
        'Mobile & Micro-soldering',
        'Laptop & Desktop Hardware',
        'Apple & Tablet Devices',
        'Motherboard & IC Repair',
        'Console & Peripherals'
      ],
      'Receptionist': [
        'Customer Intake',
        'Customer Intake & Billing'
      ],
      'Cashier': [
        'Payments & Release'
      ],
      'Admin': [
        'System Management',
        'Operations Supervisor'
      ],
      'Manager': []
    };

    let isVisible = false;
    let holdTimer = null;
    let wasLongPress = false;

    function updateSpecializationOptions() {
      const selectedRole = roleSelect.value;
      const options = roleSpecializations[selectedRole] || [];

      if (options.length === 0) {
        // Manager -> NULL (hide specialization dropdown)
        specializationField.style.display = 'none';
        specializationSelect.required = false;
        specializationSelect.innerHTML = '<option value="">None (NULL)</option>';
        specializationSelect.value = '';
        return;
      }

      specializationField.style.display = 'block';
      specializationSelect.required = true;
      specializationSelect.innerHTML = options
        .map(opt => `<option value="${opt}">${opt}</option>`)
        .join('');
    }

    roleSelect.addEventListener('change', updateSpecializationOptions);
    updateSpecializationOptions();

    function setPasswordVisibility(visible) {
      isVisible = visible;
      pwInput.type = visible ? 'text' : 'password';
      eyeOpenIcon.style.display = visible ? 'none' : 'inline';
      eyeOffIcon.style.display = visible ? 'inline' : 'none';
      togglePwBtn.classList.toggle('active', visible);
    }

    function startHoldPeek() {
      wasLongPress = false;
      clearTimeout(holdTimer);
      holdTimer = setTimeout(() => {
        wasLongPress = true;
        setPasswordVisibility(true);
      }, 220);
    }

    function endHoldPeek() {
      clearTimeout(holdTimer);
      if (wasLongPress) {
        setPasswordVisibility(false);
        setTimeout(() => { wasLongPress = false; }, 0);
      }
    }

    togglePwBtn.addEventListener('mousedown', startHoldPeek);
    togglePwBtn.addEventListener('touchstart', startHoldPeek, { passive: true });
    window.addEventListener('mouseup', endHoldPeek);
    window.addEventListener('touchend', endHoldPeek);
    togglePwBtn.addEventListener('mouseleave', () => {
      if (wasLongPress) endHoldPeek();
    });

    togglePwBtn.addEventListener('click', () => {
      if (wasLongPress) return;
      setPasswordVisibility(!isVisible);
    });

    document.getElementById('signupForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      const errEl = document.getElementById('signupErr');
      errEl.textContent = '';

      const pwVal = pwInput.value;
      if (pwVal.length < 8 || pwVal.length > 12) {
        errEl.textContent = 'Password Must Be Between 8 And 12 Characters.';
        pwInput.focus();
        return;
      }

      const contactVal = contactInput.value.trim();
      if (contactVal.length !== 11) {
        errEl.textContent = 'Contact Number Must Be Exactly 11 Digits.';
        contactInput.focus();
        return;
      }

      const selectedRole = roleSelect.value;
      const specValue = selectedRole === 'Manager' ? null : (specializationSelect.value || null);

      try {
        const res = await fetch('api/endpoints.php?action=create_staff', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            first_name: document.getElementById('first_name').value.trim(),
            middle_name: document.getElementById('middle_name').value.trim(),
            last_name: document.getElementById('last_name').value.trim(),
            username: document.getElementById('username').value.trim(),
            password: pwVal,
            email: document.getElementById('email').value.trim(),
            contact_number: contactVal,
            role: selectedRole,
            specialization: specValue
          })
        });
        const data = await res.json();
        if (res.ok && data.ok) {
          window.location.href = 'login.php?registered=1';
        } else {
          errEl.textContent = data.message || data.error || 'Could Not Create Account.';
        }
      } catch (err) {
        errEl.textContent = 'Unable To Reach The Server. Check Apache And MySQL In XAMPP.';
      }
    });
  </script>
</body>
</html>