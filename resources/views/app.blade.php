<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Peminjaman Ruang - Login</title>

  <!-- Vue 3 -->
  <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Inter Font (Modern & Professional) -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Toastify CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

  <style>
    /* =========================
       GLOBAL TOKENS (LIGHT)
    ========================== */
    :root {
      --bg: #f3f4f6;
      --bg-soft: #e5e7eb;
      --panel: #ffffff;
      --panel-soft: #f9fafb;
      --text: #0f172a;
      --muted: #6b7280;
      --line: #e5e7eb;

      --primary: #111827;
      --primary-soft: rgba(17, 24, 39, 0.06);
      --primary-strong: #020617;

      --accent: #2563eb;
      --accent-soft: rgba(37, 99, 235, 0.08);
      --accent-strong: #1d4ed8;

      --danger: #ef4444;
      --shadow-soft: 0 18px 40px rgba(15, 23, 42, 0.12);
      --radius-lg: 24px;
      --radius-xl: 32px;
      --transition-fast: 160ms ease-out;
      --transition-normal: 220ms cubic-bezier(0.22, 0.61, 0.36, 1);
    }

    * {
      box-sizing: border-box;
    }

    html, body {
      height: 100%;
    }

    body {
      margin: 0;
      font-family: "Inter", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      background: radial-gradient(circle at top left, #e5e7eb 0, #f9fafb 40%, #f3f4f6 80%);
      color: var(--text);
      overflow-x: hidden;
    }

    [v-cloak] {
      display: none;
    }

    /* =========================
       LAYOUT
    ========================== */

    .app-shell {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }



    /* =========================
       AUTH CARD
    ========================== */

    .auth-card {
      width: 100%;
      max-width: 480px;
      background: var(--panel);
      border-radius: var(--radius-lg);
      border: 1px solid var(--line);
      padding: 32px;
      box-shadow:
        0 20px 60px rgba(15, 23, 42, 0.15),
        0 0 0 1px rgba(148, 163, 184, 0.1);
    }

    .auth-header {
      text-align: center;
      margin-bottom: 24px;
    }

    .auth-logo {
      width: 48px;
      height: 48px;
      background: var(--primary);
      border-radius: 12px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 16px;
    }

    .auth-logo i {
      font-size: 24px;
      color: white;
    }

    .auth-title {
      font-size: 24px;
      font-weight: 700;
      color: var(--text);
      margin-bottom: 8px;
    }

    .auth-subtitle {
      font-size: 14px;
      color: var(--muted);
    }

    .auth-tabs {
      display: inline-flex;
      padding: 2px;
      border-radius: 999px;
      background: var(--bg-soft);
      border: 1px solid var(--line);
      margin-top: 12px;
      margin-bottom: 16px;
    }

    .auth-tab-btn {
      border: none;
      background: transparent;
      border-radius: 999px;
      padding: 6px 14px;
      font-size: 11px;
      font-weight: 500;
      letter-spacing: 0.04em;
      text-transform: uppercase;
      color: var(--muted);
      display: inline-flex;
      align-items: center;
      gap: 6px;
      cursor: pointer;
      transition: all var(--transition-fast);
    }

    .auth-tab-btn i {
      font-size: 13px;
    }

    .auth-tab-btn.active {
      background: var(--panel);
      color: var(--text);
      box-shadow: 0 8px 20px rgba(148, 163, 184, 0.35);
    }

    .auth-tab-btn.active i {
      color: var(--accent);
    }

    /* FORM CONTROLS */

    .form-label {
      font-size: 12px;
      font-weight: 500;
      color: var(--muted);
      margin-bottom: 4px;
    }

    .form-label span {
      font-weight: 600;
      color: var(--text);
    }

    .form-control {
      border-radius: 999px;
      border: 1px solid var(--line);
      background: var(--panel-soft);
      font-size: 13px;
      padding: 8px 12px;
      color: var(--text);
      transition: all var(--transition-normal);
      min-height: 44px;
    }

    .form-control::placeholder {
      color: #9ca3af;
    }

    .form-control:focus {
      border-color: var(--accent);
      background: #ffffff;
      box-shadow:
        0 0 0 1px rgba(37, 99, 235, 0.4),
        0 0 0 4px rgba(191, 219, 254, 0.5);
      outline: none;
    }

    .form-control.is-invalid {
      border-color: var(--danger);
      box-shadow:
        0 0 0 1px rgba(239, 68, 68, 0.4),
        0 0 0 4px rgba(254, 202, 202, 0.5);
    }

    .invalid-feedback {
      font-size: 11px;
      margin-top: 2px;
    }

    .input-with-icon {
      position: relative;
    }

    .input-with-icon .input-icon {
      position: absolute;
      inset-block: 0;
      left: 12px;
      display: flex;
      align-items: center;
      font-size: 14px;
      color: #9ca3af;
      pointer-events: none;
    }

    .input-with-icon .form-control {
      padding-left: 32px;
    }

    .password-toggle {
      position: absolute;
      inset-block: 0;
      right: 10px;
      display: flex;
      align-items: center;
      border: none;
      background: transparent;
      padding: 0 6px;
      cursor: pointer;
      color: #9ca3af;
      font-size: 15px;
      transition: color var(--transition-fast);
      width: 40px;
      height: 40px;
    }

    .password-toggle:hover {
      color: var(--accent);
    }

    .form-row-inline {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 10px;
      margin-bottom: 10px;
    }

    .form-check-label {
      font-size: 12px;
      color: var(--muted);
    }

    .link-muted {
      font-size: 12px;
      color: var(--accent);
      text-decoration: none;
    }

    .link-muted:hover {
      text-decoration: underline;
    }

    .role-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 12px;
      border-radius: 999px;
      background: var(--accent-soft);
      color: var(--accent);
      font-size: 12px;
      font-weight: 500;
      margin-bottom: 16px;
    }

    .role-badge i {
      font-size: 14px;
    }

    /* BUTTONS */

    .btn-primary-soft {
      border-radius: 999px;
      border: none;
      width: 100%;
      padding: 9px 14px;
      font-size: 13px;
      font-weight: 600;
      letter-spacing: 0.04em;
      text-transform: uppercase;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      background: radial-gradient(circle at 0 0, #0f172a, #020617);
      color: #f9fafb;
      box-shadow:
        0 18px 40px rgba(15, 23, 42, 0.65),
        0 0 0 1px rgba(15, 23, 42, 0.9);
      transition: transform 150ms ease, box-shadow 150ms ease, filter 150ms ease, background 150ms ease;
      min-height: 44px;
      touch-action: manipulation;
    }

    .btn-primary-soft:disabled {
      opacity: 0.8;
      cursor: wait;
      box-shadow: 0 12px 24px rgba(15, 23, 42, 0.45);
    }

    .btn-primary-soft:hover:not(:disabled) {
      transform: translateY(-1px) scale(1.01);
      box-shadow:
        0 24px 52px rgba(15, 23, 42, 0.85),
        0 0 0 1px rgba(15, 23, 42, 1);
      filter: brightness(1.02);
    }

    .btn-primary-soft i {
      font-size: 14px;
    }

    .btn-secondary-ghost {
      border-radius: 999px;
      border: 1px dashed var(--line);
      width: 100%;
      padding: 7px 12px;
      font-size: 12px;
      font-weight: 500;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      background: transparent;
      color: var(--muted);
      transition: all var(--transition-fast);
      margin-top: 8px;
      min-height: 40px;
      touch-action: manipulation;
    }

    .btn-secondary-ghost:hover {
      border-style: solid;
      border-color: var(--accent);
      background: var(--bg-soft);
      color: var(--accent-strong);
    }

    /* FOOTER TEXT */

    .auth-footer-text {
      margin-top: 10px;
      font-size: 11px;
      text-align: center;
      color: var(--muted);
    }

    .auth-footer-text a {
      color: var(--accent);
      text-decoration: none;
    }

    .auth-footer-text a:hover {
      text-decoration: underline;
    }

    /* TOAST (Bootstrap override) */

    .toast.custom-toast {
      border-radius: 16px;
      border: 1px solid rgba(148, 163, 184, 0.7);
      box-shadow: 0 16px 40px rgba(15, 23, 42, 0.35);
      background: #0f172a;
      color: #e5e7eb;
      padding-right: 10px;
    }

    .toast.custom-toast .toast-header {
      border-bottom: 1px solid rgba(148, 163, 184, 0.35);
      background: transparent;
      color: #e5e7eb;
      font-size: 12px;
    }

    .toast.custom-toast .toast-body {
      font-size: 12px;
      color: #e5e7eb;
    }

    .toast-indicator {
      width: 9px;
      height: 9px;
      border-radius: 999px;
      background: #22c55e;
      margin-right: 6px;
      box-shadow: 0 0 0 5px rgba(34, 197, 94, 0.24);
    }

    /* SMALL MICRO INTERACTION: FLOAT ANIM */

    .float-y {
      animation: floatY 6s ease-in-out infinite;
    }

    @keyframes floatY {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-6px); }
    }

    /* ================================
       TOASTIFY CUSTOM STYLES
    ================================ */
    .toastify {
      font-family: "Inter", sans-serif !important;
      border-radius: 12px !important;
      box-shadow: 0 10px 30px rgba(0,0,0,0.3) !important;
    }

    .toastify-close {
      opacity: 0.7;
    }

    .toastify-close:hover {
      opacity: 1;
    }

    /* ================================
       MOBILE RESPONSIVE ENHANCEMENTS
    ================================ */

    /* Tablet & Mobile */
    @media (max-width: 768px) {
      .app-shell {
        padding: 16px;
      }

      .auth-card {
        padding: 24px 20px;
        border-radius: 16px;
      }

      .auth-logo {
        width: 40px;
        height: 40px;
        margin-bottom: 12px;
      }

      .auth-logo i {
        font-size: 20px;
      }

      .auth-title {
        font-size: 20px;
      }

      .auth-subtitle {
        font-size: 13px;
      }

      .auth-tabs {
        margin-top: 10px;
        margin-bottom: 12px;
      }

      .auth-tab-btn {
        padding: 6px 10px;
        font-size: 10px;
      }

      .role-badge {
        font-size: 11px;
        padding: 5px 10px;
      }

      .form-control {
        font-size: 16px !important; /* Prevent iOS zoom */
        padding: 10px 14px;
      }

      .input-with-icon .form-control {
        padding-left: 36px;
      }

      .btn-primary-soft,
      .btn-secondary-ghost {
        font-size: 14px;
      }

      .auth-footer-text {
        font-size: 10px;
      }

      /* Toast mobile optimization */
      .position-fixed.bottom-0.end-0 {
        left: 0;
        right: 0;
        bottom: 0;
        padding: 12px !important;
      }

      .toast.custom-toast {
        width: 100%;
        max-width: 100%;
        border-radius: 12px 12px 0 0;
      }
    }

    /* Small Mobile - iPhone XR Optimized (414px) */
    @media (max-width: 480px) {
      .app-shell {
        padding: 12px;
        min-height: -webkit-fill-available; /* iOS Safari fix */
      }

      .auth-card {
        padding: 24px 20px;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.12);
      }

      .auth-logo {
        width: 48px;
        height: 48px;
        margin-bottom: 16px;
      }

      .auth-logo i {
        font-size: 24px;
      }

      .auth-title {
        font-size: 22px;
        margin-bottom: 6px;
      }

      .auth-subtitle {
        font-size: 13px;
        margin-bottom: 20px;
      }

      .auth-tabs {
        margin-bottom: 20px;
        gap: 8px;
      }

      .auth-tab-btn {
        padding: 10px 16px;
        font-size: 13px;
        font-weight: 500;
        border-radius: 12px;
      }

      .role-badge {
        font-size: 12px;
        padding: 6px 12px;
        margin-top: 16px;
      }

      .form-label {
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 8px;
      }

      .form-control {
        font-size: 16px !important; /* Prevent iOS zoom */
        padding: 14px 16px;
        border-radius: 12px;
        height: 50px; /* Touch-friendly */
      }

      .input-with-icon .form-control {
        padding-left: 44px;
      }

      .input-icon {
        left: 14px;
        font-size: 18px;
      }

      .btn-toggle-password {
        padding: 12px;
        right: 8px;
      }

      .btn-primary-soft {
        font-size: 15px;
        font-weight: 600;
        padding: 14px 20px;
        height: 50px; /* Touch-friendly */
        border-radius: 12px;
      }

      .btn-secondary-ghost {
        font-size: 13px;
        padding: 10px 16px;
        height: 44px;
      }

      .form-row-inline {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 20px;
      }

      .form-check {
        margin-bottom: 0;
      }

      .form-check-label {
        font-size: 13px;
      }

      .link-muted {
        font-size: 13px;
        font-weight: 500;
      }

      .auth-footer {
        margin-top: 28px;
        padding-top: 20px;
      }

      .auth-footer-text {
        font-size: 11px;
        line-height: 1.6;
      }

      /* Toastify mobile optimization */
      .toastify {
        min-width: calc(100vw - 32px) !important;
        max-width: calc(100vw - 32px) !important;
        margin: 16px !important;
        border-radius: 16px !important;
        padding: 18px 20px !important;
        font-size: 14px !important;
      }

      .toastify .bi {
        font-size: 24px !important;
      }
    }

    /* Landscape mobile optimization */
    @media (max-height: 600px) and (orientation: landscape) {
      .app-shell {
        padding: 12px;
        align-items: flex-start;
      }

      .auth-card {
        padding: 16px;
        margin: 12px 0;
        max-height: 95vh;
        overflow-y: auto;
      }

      .auth-logo {
        width: 32px;
        height: 32px;
        margin-bottom: 8px;
      }

      .auth-logo i {
        font-size: 18px;
      }

      .auth-title {
        font-size: 16px;
        margin-bottom: 4px;
      }

      .auth-subtitle {
        font-size: 11px;
      }

      .auth-tabs {
        margin-top: 8px;
        margin-bottom: 8px;
      }

      .mb-2 {
        margin-bottom: 0.5rem !important;
      }

      .mb-3 {
        margin-bottom: 0.75rem !important;
      }
    }

    /* Prevent zoom on all form inputs (iOS) */
    @media (max-width: 768px) {
      select,
      textarea,
      input[type="text"],
      input[type="email"],
      input[type="password"],
      input[type="datetime"],
      input[type="datetime-local"],
      input[type="date"],
      input[type="month"],
      input[type="time"],
      input[type="week"],
      input[type="number"],
      input[type="tel"],
      input[type="url"] {
        font-size: 16px !important;
      }
    }

    /* High DPI / Retina displays */
    @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
      .auth-card {
        border-width: 0.5px;
      }
    }

    /* Reduced motion preference */
    @media (prefers-reduced-motion: reduce) {
      *,
      *::before,
      *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
      }
    }
  </style>
</head>

<body>
  <div id="app" v-cloak>
    <div class="app-shell">
      <!-- AUTH CARD -->
      <div class="auth-card">
        <header class="auth-header">
          <div class="auth-logo">
            <i class="bi bi-grid-3x3-gap-fill"></i>
          </div>
          <h1 class="auth-title">
            @{{ activeTab === "login" ? "Masuk ke Sistem" : "Buat Akun Baru" }}
          </h1>
          <p class="auth-subtitle">
            Sistem Peminjaman Ruangan Terpusat
          </p>
          
          <!-- Auto-detected role badge -->
          <div v-if="detectedRole" class="role-badge">
            <i :class="getRoleIcon(detectedRole)"></i>
            <span>@{{ getRoleLabel(detectedRole) }}</span>
          </div>
        </header>

        <!-- Tabs -->
        <div style="display: flex; justify-content: center; width: 100%;">
          <div class="auth-tabs">
            <button
              class="auth-tab-btn"
              :class="{ active: activeTab === 'login' }"
              type="button"
              @click="activeTab = 'login'"
            >
              <i class="bi bi-box-arrow-in-right"></i>
              Login
            </button>
            <button
              class="auth-tab-btn"
              :class="{ active: activeTab === 'register' }"
            type="button"
            @click="activeTab = 'register'"
          >
            <i class="bi bi-person-plus"></i>
            Register
          </button>
          </div>
        </div>

        <form @submit.prevent="handleSubmit">
                <!-- Email -->
                <div class="mb-2">
                  <label class="form-label">
                    <span>Email</span>
                  </label>
                  <div class="input-with-icon">
                    <span class="input-icon">
                      <i class="bi bi-envelope"></i>
                    </span>
                    <input
                      type="email"
                      class="form-control"
                      :class="{ 'is-invalid': errors.email }"
                      placeholder="nama@sekolah.sch.id"
                      v-model="form.email"
                    />
                  </div>
                  <div class="invalid-feedback" v-if="errors.email">
                    @{{ errors.email }}
                  </div>
                </div>

                <!-- Password -->
                <div class="mb-2">
                  <label class="form-label">
                    <span>Kata sandi</span>
                  </label>
                  <div class="input-with-icon">
                    <span class="input-icon">
                      <i class="bi bi-lock"></i>
                    </span>
                    <input
                      :type="showPassword ? 'text' : 'password'"
                      class="form-control"
                      :class="{ 'is-invalid': errors.password }"
                      placeholder="Minimal 6 karakter"
                      v-model="form.password"
                    />
                    <button
                      type="button"
                      class="password-toggle"
                      @click="showPassword = !showPassword"
                    >
                      <i :class="showPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                    </button>
                  </div>
                  <div class="invalid-feedback" v-if="errors.password">
                    @{{ errors.password }}
                  </div>
                </div>

                <!-- Extra fields for register -->
                <div v-if="activeTab === 'register'">
                  <div class="mb-2">
                    <label class="form-label">
                      <span>Nama lengkap</span>
                    </label>
                    <input
                      type="text"
                      class="form-control"
                      :class="{ 'is-invalid': errors.name }"
                      placeholder="Nama sesuai data sekolah"
                      v-model="form.name"
                    />
                    <div class="invalid-feedback" v-if="errors.name">
                      @{{ errors.name }}
                    </div>
                  </div>
                  <div class="mb-2">
                    <label class="form-label">
                      <span>NIS / NIP (opsional)</span>
                    </label>
                    <input
                      type="text"
                      class="form-control"
                      placeholder="Masukkan NIS atau NIP jika ada"
                      v-model="form.identity"
                    />
                  </div>
                </div>

                <!-- Remember / Lupa password -->
                <div class="form-row-inline">
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      id="rememberCheck"
                      v-model="rememberMe"
                    />
                    <label class="form-check-label" for="rememberCheck">
                      Ingat saya
                    </label>
                  </div>
                </div>

          <!-- Info role: Petugas contact -->
          <div class="mb-3" style="font-size: 11px; color: var(--muted);">
            <i class="bi bi-info-circle"></i>
            <span>
              Untuk akun Petugas, silakan hubungi pihak pengelola jika belum memiliki kredential.
            </span>
          </div>

                <!-- Primary button -->
                <button
                  type="submit"
                  class="btn-primary-soft"
                  :disabled="loading"
                >
                  <span v-if="!loading">
                    <i class="bi bi-door-open"></i>
                    @{{ activeTab === "login" ? "Masuk sekarang" : "Daftar sebagai peminjam" }}
                  </span>
                  <span v-else>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Memproses...
                  </span>
                </button>
              </form>

          <div class="auth-footer-text">
            Dengan melanjutkan, Anda menyetujui
            <a href="javascript:void(0)">kebijakan penggunaan</a> &amp;
            <a href="javascript:void(0)">privasi data</a>.
          </div>
        </div>
      </div>

      <!-- Toast container -->
      <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
        <div
          id="statusToast"
          class="toast align-items-center custom-toast"
          role="alert"
          aria-live="assertive"
          aria-atomic="true"
        >
          <div class="toast-header">
            <span class="toast-indicator"></span>
            <strong class="me-auto">@{{ toast.title }}</strong>
            <small>@{{ toast.subtitle }}</small>
            <button
              type="button"
              class="btn-close btn-close-white ms-2 mb-1"
              data-bs-dismiss="toast"
              aria-label="Close"
            ></button>
          </div>
          <div class="toast-body">
            @{{ toast.message }}
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS (Toast, dll) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Tunggu Vue loaded
    if (typeof Vue === 'undefined') {
      console.error('Vue.js not loaded!');
      document.body.innerHTML = '<h1 style="color:red;padding:20px;">Error: Vue.js gagal dimuat. Periksa koneksi internet Anda.</h1>';
    } else {
      document.addEventListener("DOMContentLoaded", () => {
        const { createApp, ref, reactive, computed, watch } = Vue;

        createApp({
          setup() {
            const activeTab = ref("login");
            const showPassword = ref(false);
            const rememberMe = ref(true);

          const form = reactive({
            role: "peminjam",
            email: "",
            password: "",
            name: "",
            identity: ""
          });

          // Auto-detect role from email
          const detectedRole = computed(() => {
            if (!form.email) return null;
            const email = form.email.toLowerCase();
            if (email.startsWith('admin@')) return 'admin';
            if (email.startsWith('petugas@')) return 'petugas';
            return 'peminjam';
          });

          // Auto-update form.role when email changes
          watch(() => form.email, () => {
            if (detectedRole.value) {
              form.role = detectedRole.value;
            }
          });

          const getRoleLabel = (role) => {
            const labels = {
              admin: 'Administrator',
              petugas: 'Petugas',
              peminjam: 'Peminjam'
            };
            return labels[role] || role;
          };

          const getRoleIcon = (role) => {
            const icons = {
              admin: 'bi bi-shield-lock',
              petugas: 'bi bi-clipboard-check',
              peminjam: 'bi bi-person-badge'
            };
            return icons[role] || 'bi bi-person';
          };

          const errors = reactive({
            email: "",
            password: "",
            name: ""
          });

          const loading = ref(false);

          const toast = reactive({
            title: "Berhasil",
            subtitle: "Baru saja",
            message: "Operasi berhasil dijalankan."
          });

          let toastInstance = null;

          const validate = () => {
            errors.email = "";
            errors.password = "";
            errors.name = "";

            let valid = true;

            if (!form.email) {
              errors.email = "Email wajib diisi.";
              valid = false;
            } else if (!form.email.includes("@")) {
              errors.email = "Format email tidak valid.";
              valid = false;
            }

            if (!form.password) {
              errors.password = "Kata sandi wajib diisi.";
              valid = false;
            }

            if (activeTab.value === "register" && !form.name) {
              errors.name = "Nama lengkap wajib diisi untuk registrasi.";
              valid = false;
            }

            console.log("Validation result:", valid, "Errors:", errors);
            return valid;
          };

          const showToast = (title, message, type = "success") => {
            Toastify({
              text: `<div style="display: flex; align-items: center; gap: 12px;">
                <div style="
                  width: 36px;
                  height: 36px;
                  display: flex;
                  align-items: center;
                  justify-content: center;
                  background: rgba(255, 255, 255, 0.25);
                  border-radius: 50%;
                  flex-shrink: 0;
                ">
                  <i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'exclamation-circle-fill'}" style="font-size: 20px; color: white;"></i>
                </div>
                <div style="flex: 1;">
                  <div style="font-weight: 600; font-size: 14px; margin-bottom: 2px; color: white;">${title}</div>
                  <div style="font-size: 12px; color: rgba(255, 255, 255, 0.9);">${message}</div>
                </div>
              </div>`,
              duration: 3000,
              close: true,
              gravity: "top",
              position: "center",
              stopOnFocus: true,
              escapeMarkup: false,
              style: {
                background: type === "success" ? "linear-gradient(135deg, #10b981 0%, #059669 100%)" : "linear-gradient(135deg, #ef4444 0%, #dc2626 100%)",
                borderRadius: "16px",
                padding: "14px 18px",
                fontFamily: "'Inter', sans-serif",
                boxShadow: "0 10px 40px rgba(0,0,0,0.25)",
                border: "1px solid rgba(255, 255, 255, 0.2)",
              },
              offset: {
                x: 0,
                y: 20
              },
              onClick: function(){}
            }).showToast();
          };

          const handleSubmit = async () => {
            console.log("handleSubmit called");
            console.log("Form data:", form);
            console.log("Active tab:", activeTab.value);
            
            if (!validate()) {
              console.log("Validation failed");
              return;
            }

            loading.value = true;

            const url = activeTab.value === "login" ? "/login" : "/register";
            const payload = {
              email: form.email,
              password: form.password,
              ...(activeTab.value === "register" && {
                name: form.name,
                identity: form.identity,
              }),
            };

            console.log("Sending request to:", url);
            console.log("Payload:", payload);

            try {
              const response = await fetch(url, {
                method: "POST",
                headers: {
                  "Content-Type": "application/json",
                  "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(payload),
              });

              console.log("Response status:", response.status);
              const data = await response.json();
              console.log("Response data:", data);

              if (response.ok && data.success) {
                showToast(
                  activeTab.value === "login" ? "Login berhasil" : "Registrasi berhasil",
                  data.message
                );
                
                // Redirect ke dashboard
                setTimeout(() => {
                  window.location.href = data.redirect || "/dashboard";
                }, 1000);
              } else {
                showToast("Error", data.message || "Terjadi kesalahan", "error");
                loading.value = false;
              }
            } catch (error) {
              console.error("Submit error:", error);
              showToast("Error", "Terjadi kesalahan pada server", "error");
              loading.value = false;
            }
          };

          return {
            activeTab,
            showPassword,
            rememberMe,
            form,
            errors,
            loading,
            toast,
            detectedRole,
            getRoleLabel,
            getRoleIcon,
            validate,
            handleSubmit
          };
        }
      }).mount("#app");
      });
    }
  </script>

  <!-- Toastify JS -->
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</body>
</html>
