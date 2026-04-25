<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Kirish') — Xprinter.uz</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@400;600;700&family=Manrope:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg:        #FFFFFF;
      --bg-soft:   #F4F8FE;
      --surface:   #FFFFFF;
      --line:      #E3EBF5;
      --ink:       #0A1B3D;
      --ink-soft:  #2C3E5C;
      --muted:     #6B7B95;
      --faint:     #A5B0C4;
      --blue:      #0066FF;
      --blue-hi:   #1A75FF;
      --blue-deep: #0040B8;
      --blue-soft: #DCE9FB;
      --cyan:      #00B6E8;
      --green:     #00C896;
      --red:       #FF3B5C;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Manrope', sans-serif;
      background: var(--bg-soft);
      color: var(--ink);
      min-height: 100vh;
    }

    /* ── Wrap ──────────────────────────────────── */
    .auth-wrap {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* ── Brand panel (hidden on mobile) ────────── */
    .auth-brand {
      display: none;
    }

    /* ── Form panel ────────────────────────────── */
    .auth-panel {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 40px 20px;
      min-height: 100vh;
    }

    /* ── Mobile logo ───────────────────────────── */
    .mobile-logo {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 32px;
    }

    .mobile-logomark {
      width: 38px; height: 38px;
      background: linear-gradient(135deg, var(--blue), var(--cyan));
      border-radius: 11px;
      display: flex; align-items: center; justify-content: center;
    }

    .mobile-logomark svg {
      width: 20px; height: 20px;
      stroke: #fff; fill: none;
      stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
    }

    .mobile-brand-name {
      font-family: 'Unbounded', sans-serif;
      font-size: 15px;
      font-weight: 700;
      letter-spacing: -0.02em;
      color: var(--ink);
    }

    /* ── Auth card ─────────────────────────────── */
    .auth-card {
      width: 100%;
      max-width: 420px;
      background: var(--surface);
      border: 1px solid var(--line);
      border-radius: 20px;
      padding: 36px 32px;
      box-shadow: 0 4px 24px rgba(0, 40, 120, 0.06);
    }

    .auth-heading {
      font-family: 'Unbounded', sans-serif;
      font-size: 22px;
      font-weight: 700;
      letter-spacing: -0.02em;
      color: var(--ink);
      margin-bottom: 6px;
    }

    .auth-subheading {
      font-size: 13.5px;
      color: var(--muted);
      margin-bottom: 28px;
      line-height: 1.5;
    }

    /* ── Form elements ─────────────────────────── */
    .form-group {
      margin-bottom: 18px;
    }

    .form-label {
      display: block;
      font-size: 13px;
      font-weight: 600;
      color: var(--ink-soft);
      margin-bottom: 7px;
    }

    .form-input {
      width: 100%;
      height: 46px;
      padding: 0 16px;
      border: 1.5px solid var(--line);
      border-radius: 12px;
      font-family: 'Manrope', sans-serif;
      font-size: 14px;
      color: var(--ink);
      background: var(--bg-soft);
      outline: none;
      transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
    }

    .form-input:focus {
      border-color: var(--blue);
      background: var(--surface);
      box-shadow: 0 0 0 3px rgba(0, 102, 255, 0.10);
    }

    .form-input::placeholder { color: var(--faint); }

    .form-input.is-invalid {
      border-color: var(--red);
    }

    .form-input.is-invalid:focus {
      box-shadow: 0 0 0 3px rgba(255, 59, 92, 0.10);
    }

    .invalid-feedback {
      display: block;
      margin-top: 6px;
      font-size: 12px;
      color: var(--red);
      font-weight: 500;
    }

    /* ── Remember + forgot row ─────────────────── */
    .form-row-between {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 24px;
      flex-wrap: wrap;
      gap: 8px;
    }

    .form-check {
      display: flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
    }

    .form-check-input {
      width: 16px; height: 16px;
      border: 1.5px solid var(--line);
      border-radius: 4px;
      accent-color: var(--blue);
      cursor: pointer;
    }

    .form-check-label {
      font-size: 13px;
      color: var(--muted);
      cursor: pointer;
      user-select: none;
    }

    .auth-link {
      font-size: 13px;
      color: var(--blue);
      text-decoration: none;
      font-weight: 600;
    }

    .auth-link:hover { color: var(--blue-hi); }

    /* ── Submit button ─────────────────────────── */
    .btn-auth {
      width: 100%;
      height: 50px;
      background: var(--blue);
      color: #fff;
      border: none;
      border-radius: 14px;
      font-family: 'Manrope', sans-serif;
      font-size: 15px;
      font-weight: 700;
      cursor: pointer;
      box-shadow: 0 8px 24px rgba(0, 102, 255, 0.28);
      transition: background 0.15s, box-shadow 0.15s, transform 0.1s;
      letter-spacing: 0.01em;
    }

    .btn-auth:hover {
      background: var(--blue-hi);
      box-shadow: 0 10px 30px rgba(0, 102, 255, 0.36);
      transform: translateY(-1px);
    }

    .btn-auth:active { transform: translateY(0); }

    /* ── Footer link ───────────────────────────── */
    .auth-footer {
      margin-top: 24px;
      text-align: center;
      font-size: 13.5px;
      color: var(--muted);
    }

    /* ── Desktop ───────────────────────────────── */
    @media (min-width: 1024px) {
      .auth-wrap {
        flex-direction: row;
      }

      .mobile-logo {
        display: none;
      }

      /* Brand panel */
      .auth-brand {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        width: 44%;
        min-height: 100vh;
        position: sticky;
        top: 0;
        height: 100vh;
        background: linear-gradient(160deg, #001F7A 0%, var(--blue) 55%, var(--cyan) 100%);
        padding: 48px 52px;
        overflow: hidden;
      }

      .auth-brand::before {
        content: '';
        position: absolute;
        top: -120px; right: -120px;
        width: 400px; height: 400px;
        border-radius: 50%;
        background: rgba(255,255,255,0.04);
      }

      .auth-brand::after {
        content: '';
        position: absolute;
        bottom: -80px; left: -80px;
        width: 320px; height: 320px;
        border-radius: 50%;
        background: rgba(0, 182, 232, 0.12);
      }

      .brand-logo {
        display: flex;
        align-items: center;
        gap: 12px;
        position: relative; z-index: 1;
      }

      .brand-logomark {
        width: 42px; height: 42px;
        background: rgba(255,255,255,0.18);
        border: 1px solid rgba(255,255,255,0.25);
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        backdrop-filter: blur(8px);
      }

      .brand-logomark svg {
        width: 22px; height: 22px;
        stroke: #fff; fill: none;
        stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
      }

      .brand-wordmark {
        font-family: 'Unbounded', sans-serif;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: -0.01em;
        color: #fff;
        line-height: 1.2;
      }

      .brand-tagline-small {
        font-family: 'JetBrains Mono', monospace;
        font-size: 9px;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: rgba(255,255,255,0.55);
      }

      .brand-body {
        position: relative; z-index: 1;
      }

      .brand-headline {
        font-family: 'Unbounded', sans-serif;
        font-size: 28px;
        font-weight: 700;
        letter-spacing: -0.03em;
        color: #fff;
        line-height: 1.2;
        margin-bottom: 16px;
      }

      .brand-desc {
        font-size: 15px;
        color: rgba(255,255,255,0.7);
        line-height: 1.6;
        margin-bottom: 36px;
        max-width: 320px;
      }

      .brand-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
      }

      .brand-stat {
        background: rgba(255,255,255,0.10);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 14px;
        padding: 16px 14px;
        backdrop-filter: blur(8px);
      }

      .brand-stat-num {
        font-family: 'Unbounded', sans-serif;
        font-size: 22px;
        font-weight: 700;
        color: #fff;
        letter-spacing: -0.02em;
        margin-bottom: 4px;
      }

      .brand-stat-label {
        font-family: 'JetBrains Mono', monospace;
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: rgba(255,255,255,0.55);
      }

      .brand-footer-text {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        letter-spacing: 0.08em;
        color: rgba(255,255,255,0.4);
        position: relative; z-index: 1;
      }

      /* Form panel */
      .auth-panel {
        width: 56%;
        padding: 60px 64px;
        min-height: 100vh;
      }
    }

    @media (min-width: 1280px) {
      .auth-brand { width: 42%; }
      .auth-panel { width: 58%; }
      .brand-headline { font-size: 32px; }
    }
  </style>
</head>

<body>
<div class="auth-wrap">

  <!-- ═══ Brand panel (desktop only) ═══════════ -->
  <div class="auth-brand">
    <div class="brand-logo">
      <div class="brand-logomark">
        <svg viewBox="0 0 24 24"><path d="M5 7h14M5 12h14M5 17h8"/></svg>
      </div>
      <div>
        <div class="brand-wordmark">XPRINTER</div>
        <div class="brand-tagline-small">xprinter.uz</div>
      </div>
    </div>

    <div class="brand-body">
      <div class="brand-headline">Dilerlar uchun&nbsp;platforma</div>
      <div class="brand-desc">
        O'zbekistondagi rasmiy Xprinter distribyutori. Termoprinterlar, kafolat va servis — bir joyda.
      </div>
      <div class="brand-stats">
        <div class="brand-stat">
          <div class="brand-stat-num">5+</div>
          <div class="brand-stat-label">Yil bozorda</div>
        </div>
        <div class="brand-stat">
          <div class="brand-stat-num">1000+</div>
          <div class="brand-stat-label">O'rnatishlar</div>
        </div>
        <div class="brand-stat">
          <div class="brand-stat-num">12</div>
          <div class="brand-stat-label">Oy kafolat</div>
        </div>
      </div>
    </div>

    <div class="brand-footer-text">ISO 9001:2015 · CE · FCC · RoHS</div>
  </div>

  <!-- ═══ Form panel ════════════════════════════ -->
  <div class="auth-panel">

    <div class="mobile-logo">
      <div class="mobile-logomark">
        <svg viewBox="0 0 24 24"><path d="M5 7h14M5 12h14M5 17h8"/></svg>
      </div>
      <div class="mobile-brand-name">XPRINTER</div>
    </div>

    @yield('form')

  </div>

</div>
</body>
</html>
