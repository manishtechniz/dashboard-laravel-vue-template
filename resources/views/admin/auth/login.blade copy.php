<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Sign In — AdminPanel</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/primeicons/primeicons.css" />

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --font-display: 'Syne', sans-serif;
            --font-body:    'DM Sans', sans-serif;

            /* Light */
            --bg:           #f0f2f9;
            --panel-bg:     #ffffff;
            --border:       #e4e8f0;
            --text:         #0e1422;
            --text-muted:   #6b7694;
            --accent:       #4f46e5;
            --accent-2:     #7c3aed;
            --accent-light: rgba(79,70,229,.08);
            --input-bg:     #f7f8fc;
            --shadow:       0 8px 40px rgba(0,0,0,.08);
            --shadow-lg:    0 20px 60px rgba(0,0,0,.12);
            --error:        #ef4444;
            --success:      #10b981;

            --orb-1: radial-gradient(circle, rgba(99,102,241,.35) 0%, transparent 70%);
            --orb-2: radial-gradient(circle, rgba(124,58,237,.25) 0%, transparent 70%);
            --orb-3: radial-gradient(circle, rgba(6,182,212,.2)  0%, transparent 70%);
        }

        html.dark {
            --bg:           #080c18;
            --panel-bg:     #0f1525;
            --border:       #1c2540;
            --text:         #e8ecf8;
            --text-muted:   #5a6892;
            --accent:       #6366f1;
            --accent-2:     #8b5cf6;
            --accent-light: rgba(99,102,241,.12);
            --input-bg:     #121930;
            --shadow:       0 8px 40px rgba(0,0,0,.4);
            --shadow-lg:    0 20px 60px rgba(0,0,0,.6);
            --orb-1: radial-gradient(circle, rgba(99,102,241,.2) 0%, transparent 70%);
            --orb-2: radial-gradient(circle, rgba(124,58,237,.15) 0%, transparent 70%);
            --orb-3: radial-gradient(circle, rgba(6,182,212,.12) 0%, transparent 70%);
        }

        body {
            font-family: var(--font-body);
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            overflow-x: hidden;
            transition: background 0.4s, color 0.4s;
        }

        /* ── BACKGROUND ── */
        .bg-scene {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
        }

        /* Grid lines */
        .bg-scene::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(var(--border) 1px, transparent 1px),
                linear-gradient(90deg, var(--border) 1px, transparent 1px);
            background-size: 60px 60px;
            opacity: 0.4;
        }

        /* Orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            animation: float 8s ease-in-out infinite;
        }
        .orb-1 { width: 500px; height: 500px; top: -100px; right: -50px;  background: var(--orb-1); animation-delay: 0s; }
        .orb-2 { width: 400px; height: 400px; bottom: -80px; left: 5%;    background: var(--orb-2); animation-delay: -3s; }
        .orb-3 { width: 300px; height: 300px; top: 40%;    left: 45%;     background: var(--orb-3); animation-delay: -5s; }

        @keyframes float {
            0%,100% { transform: translateY(0) scale(1); }
            50%      { transform: translateY(-30px) scale(1.05); }
        }

        /* ── OUTER WRAPPER ── */
        .auth-outer {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        /* ── LEFT PANEL (branding) ── */
        .auth-left {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px;
            position: relative;
            overflow: hidden;
        }

        .auth-left-logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-mark {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-family: var(--font-display);
            font-weight: 800; color: #fff; font-size: 16px;
            box-shadow: 0 4px 12px rgba(99,102,241,.4);
        }

        .logo-name {
            font-family: var(--font-display);
            font-weight: 700; font-size: 18px;
            color: var(--text);
            letter-spacing: -0.3px;
        }

        .auth-left-hero {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 48px 0;
        }

        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--accent-light);
            border: 1px solid rgba(99,102,241,.2);
            border-radius: 99px;
            padding: 5px 14px;
            font-size: 11px;
            font-weight: 600;
            color: var(--accent);
            letter-spacing: 0.5px;
            text-transform: uppercase;
            width: fit-content;
            margin-bottom: 24px;
        }

        .hero-headline {
            font-family: var(--font-display);
            font-size: clamp(36px, 4vw, 52px);
            font-weight: 800;
            line-height: 1.05;
            letter-spacing: -2px;
            color: var(--text);
            margin-bottom: 20px;
        }

        .hero-headline .gradient-text {
            background: linear-gradient(135deg, var(--accent), var(--accent-2), #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-sub {
            font-size: 15px;
            color: var(--text-muted);
            line-height: 1.7;
            max-width: 360px;
            margin-bottom: 40px;
        }

        /* Feature list */
        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13.5px;
            color: var(--text-muted);
        }

        .feature-dot {
            width: 28px; height: 28px;
            border-radius: 8px;
            background: var(--accent-light);
            border: 1px solid rgba(99,102,241,.2);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            font-size: 12px;
            color: var(--accent);
        }

        /* Testimonial */
        .auth-left-footer {
            background: var(--accent-light);
            border: 1px solid rgba(99,102,241,.15);
            border-radius: 16px;
            padding: 20px 24px;
        }

        .testimonial-quote {
            font-size: 13.5px;
            color: var(--text);
            line-height: 1.6;
            font-style: italic;
            margin-bottom: 12px;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .t-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700; color: #fff;
        }

        .t-name  { font-size: 12.5px; font-weight: 600; color: var(--text); }
        .t-role  { font-size: 11px; color: var(--text-muted); }

        /* Stars */
        .stars {
            color: #f59e0b;
            font-size: 12px;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }

        /* ── RIGHT PANEL (form) ── */
        .auth-right {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 32px;
        }

        .auth-card {
            width: 100%;
            max-width: 420px;
            background: var(--panel-bg);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 40px;
            box-shadow: var(--shadow-lg);
            position: relative;
            overflow: hidden;
            animation: cardIn 0.5s cubic-bezier(.22,1,.36,1) both;
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(24px) scale(.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Glow top border */
        .auth-card::before {
            content: '';
            position: absolute;
            top: 0; left: 10%; right: 10%;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--accent), transparent);
        }

        /* ── Theme Toggle ── */
        .theme-toggle {
            position: absolute;
            top: 20px; right: 20px;
            width: 34px; height: 34px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: var(--input-bg);
            color: var(--text-muted);
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px;
            transition: all 0.2s;
        }
        .theme-toggle:hover { color: var(--text); background: var(--accent-light); }

        /* ── View switcher (Login / Register / Forgot) ── */
        .auth-tabs {
            display: flex;
            gap: 0;
            background: var(--input-bg);
            border-radius: 10px;
            padding: 4px;
            margin-bottom: 28px;
            border: 1px solid var(--border);
        }

        .auth-tab {
            flex: 1;
            padding: 8px;
            border: none;
            border-radius: 7px;
            background: transparent;
            font-family: var(--font-body);
            font-size: 12.5px;
            font-weight: 600;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.2s;
        }

        .auth-tab.active {
            background: var(--panel-bg);
            color: var(--text);
            box-shadow: 0 1px 4px rgba(0,0,0,.1);
        }

        /* ── Form card header ── */
        .card-eyebrow {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 8px;
        }

        .card-title {
            font-family: var(--font-display);
            font-size: 24px;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.5px;
            margin-bottom: 6px;
        }

        .card-sub {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 28px;
            line-height: 1.5;
        }

        /* ── Social login ── */
        .social-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 24px;
        }

        .social-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: var(--input-bg);
            color: var(--text);
            font-size: 13px;
            font-weight: 500;
            font-family: var(--font-body);
            cursor: pointer;
            transition: all 0.15s;
        }

        .social-btn:hover {
            background: var(--accent-light);
            border-color: rgba(99,102,241,.3);
        }

        .social-icon { font-size: 15px; }

        /* ── Divider ── */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 22px;
            font-size: 11.5px;
            color: var(--text-muted);
            font-weight: 500;
        }
        .divider::before, .divider::after {
            content: ''; flex: 1; height: 1px; background: var(--border);
        }

        /* ── Fields ── */
        .field {
            margin-bottom: 16px;
        }

        .field-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 7px;
        }

        .field-label a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
            font-size: 11.5px;
            cursor: pointer;
        }
        .field-label a:hover { text-decoration: underline; }

        .input-wrap {
            position: relative;
        }

        .field-input {
            width: 100%;
            padding: 11px 14px 11px 42px;
            border-radius: 10px;
            border: 1.5px solid var(--border);
            background: var(--input-bg);
            color: var(--text);
            font-family: var(--font-body);
            font-size: 13.5px;
            outline: none;
            transition: all 0.2s;
            -webkit-appearance: none;
        }

        .field-input::placeholder { color: var(--text-muted); opacity: 0.7; }

        .field-input:focus {
            border-color: var(--accent);
            background: var(--panel-bg);
            box-shadow: 0 0 0 3px rgba(99,102,241,.1);
        }

        .field-input.error {
            border-color: var(--error);
            box-shadow: 0 0 0 3px rgba(239,68,68,.1);
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 14px;
            pointer-events: none;
            transition: color 0.2s;
        }

        .field-input:focus ~ .input-icon { color: var(--accent); }

        .input-icon-right {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 14px;
            cursor: pointer;
            transition: color 0.15s;
        }
        .input-icon-right:hover { color: var(--text); }

        /* Input no-left-icon variant */
        .field-input.no-icon { padding-left: 14px; }

        /* Error message */
        .field-error {
            font-size: 11.5px;
            color: var(--error);
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* ── Remember row ── */
        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        .remember-row input[type="checkbox"] {
            width: 16px; height: 16px;
            border-radius: 4px;
            accent-color: var(--accent);
            cursor: pointer;
        }

        .remember-row label {
            font-size: 12.5px;
            color: var(--text-muted);
            cursor: pointer;
        }

        /* ── Submit button ── */
        .btn-primary {
            width: 100%;
            padding: 13px;
            border-radius: 10px;
            border: none;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            color: #fff;
            font-family: var(--font-body);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.2px;
        }

        .btn-primary::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(rgba(255,255,255,.15), transparent);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(99,102,241,.4);
        }

        .btn-primary:active { transform: translateY(0); }

        .btn-primary:disabled {
            opacity: 0.6; cursor: not-allowed; transform: none;
        }

        /* Loading spinner inside button */
        .btn-spinner {
            display: inline-block;
            width: 16px; height: 16px;
            border: 2px solid rgba(255,255,255,.4);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            vertical-align: middle;
            margin-right: 8px;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Footer links ── */
        .auth-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: var(--text-muted);
        }
        .auth-footer a, .auth-footer span {
            color: var(--accent);
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }
        .auth-footer a:hover { text-decoration: underline; }

        /* ── Password strength bar ── */
        .strength-bar {
            display: flex;
            gap: 4px;
            margin-top: 8px;
        }

        .strength-segment {
            flex: 1; height: 3px; border-radius: 99px;
            background: var(--border);
            transition: background 0.3s;
        }

        .strength-label {
            font-size: 11px;
            margin-top: 5px;
            font-weight: 600;
        }

        /* ── Alert ── */
        .alert {
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 12.5px;
            margin-bottom: 18px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
            line-height: 1.5;
        }

        .alert-error   { background: rgba(239,68,68,.08); border: 1px solid rgba(239,68,68,.2);  color: #ef4444; }
        .alert-success { background: rgba(16,185,129,.08); border: 1px solid rgba(16,185,129,.2); color: #10b981; }
        .alert-info    { background: var(--accent-light); border: 1px solid rgba(99,102,241,.2); color: var(--accent); }

        /* ── OTP boxes (2FA) ── */
        .otp-row {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin: 20px 0;
        }

        .otp-box {
            width: 48px; height: 54px;
            border-radius: 10px;
            border: 1.5px solid var(--border);
            background: var(--input-bg);
            color: var(--text);
            font-family: var(--font-display);
            font-size: 22px;
            font-weight: 700;
            text-align: center;
            outline: none;
            transition: all 0.2s;
        }

        .otp-box:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(99,102,241,.12);
        }

        /* ── Responsive ── */
        @media (max-width: 900px) {
            .auth-outer { grid-template-columns: 1fr; }
            .auth-left  { display: none; }
            .auth-right { padding: 24px 16px; min-height: 100vh; }
        }

        /* ── Animations ── */
        .view { animation: fadeUp 0.3s ease both; }
        @keyframes fadeUp {
            from { opacity:0; transform: translateY(10px); }
            to   { opacity:1; transform: translateY(0); }
        }

        /* ── MFA notice ── */
        .mfa-notice {
            text-align: center;
            margin-bottom: 8px;
        }
        .mfa-icon {
            width: 56px; height: 56px;
            border-radius: 16px;
            background: var(--accent-light);
            border: 1px solid rgba(99,102,241,.2);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 14px;
            font-size: 22px;
            color: var(--accent);
        }
    </style>
</head>
<body>

<!-- Background scene -->
<div class="bg-scene">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
</div>

<div class="auth-outer">

    <!-- ══ LEFT BRANDING PANEL ══ -->
    <div class="auth-left">
        <!-- Logo -->
        <div class="auth-left-logo">
            <div class="logo-mark">A</div>
            <span class="logo-name">AdminPanel</span>
        </div>

        <!-- Hero -->
        <div class="auth-left-hero">
            <div class="hero-tag">
                <i class="pi pi-verified"></i>
                Trusted by 2,400+ teams
            </div>
            <h1 class="hero-headline">
                The admin panel<br/>
                that <span class="gradient-text">scales with you</span>
            </h1>
            <p class="hero-sub">
                Multi-tenant, multi-theme, role-based access control — everything your platform needs in one elegant dashboard.
            </p>

            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-dot"><i class="pi pi-shield"></i></div>
                    Fine-grained roles &amp; permissions per tenant
                </div>
                <div class="feature-item">
                    <div class="feature-dot"><i class="pi pi-palette"></i></div>
                    Four built-in themes with per-user overrides
                </div>
                <div class="feature-item">
                    <div class="feature-dot"><i class="pi pi-table"></i></div>
                    Powerful server-side DataGrid with export
                </div>
                <div class="feature-item">
                    <div class="feature-dot"><i class="pi pi-building"></i></div>
                    Global feature flags for every tenant
                </div>
            </div>
        </div>

        <!-- Testimonial -->
        <div class="auth-left-footer">
            <div class="stars">★★★★★</div>
            <div class="testimonial-quote">
                "We migrated 7 tenants to AdminPanel in a week. The role system and theme engine saved us months of work."
            </div>
            <div class="testimonial-author">
                <div class="t-avatar">AS</div>
                <div>
                    <div class="t-name">Arjun Sharma</div>
                    <div class="t-role">CTO, TechStart India</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ══ RIGHT FORM PANEL ══ -->
    <div class="auth-right">
        <div class="auth-card" id="auth-card">

            <!-- Theme toggle -->
            <button class="theme-toggle" onclick="toggleTheme()" id="theme-btn" title="Toggle theme">
                <i class="pi pi-moon" id="theme-icon"></i>
            </button>

            <!-- View: LOGIN -->
            <div id="view-login" class="view">
                {{-- <!-- Tabs -->
                <div class="auth-tabs">
                    <button class="auth-tab active" onclick="showView('login', this)">Sign In</button>
                    <button class="auth-tab" onclick="showView('register', this)">Register</button>
                </div> --}}

                <div class="card-eyebrow">Welcome back</div>
                <h2 class="card-title">Sign in to your account</h2>
                <p class="card-sub">Enter your credentials to access the admin panel.</p>

                <!-- Server error placeholder -->
                @if(session('error'))
                <div class="alert alert-error">
                    <i class="pi pi-times-circle"></i>
                    {{ session('error') }}
                </div>
                @endif
                @if(session('success'))
                <div class="alert alert-success">
                    <i class="pi pi-check-circle"></i>
                    {{ session('success') }}
                </div>
                @endif

                <!-- Social -->
                {{-- <div class="social-row">
                    <button class="social-btn" onclick="socialLogin('google')">
                        <svg class="social-icon" viewBox="0 0 24 24" width="16" height="16">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Google
                    </button>
                    <button class="social-btn" onclick="socialLogin('github')">
                        <svg class="social-icon" viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                            <path d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/>
                        </svg>
                        GitHub
                    </button>
                </div> --}}

                <div class="divider">or continue with email</div>

                <form id="login-form" onsubmit="handleLogin(event)">
                    @csrf
                    <div class="field">
                        <div class="field-label">Email address</div>
                        <div class="input-wrap">
                            <input type="email" name="email" id="login-email"
                                class="field-input" placeholder="you@example.com"
                                value="{{ old('email') }}" autocomplete="email" required />
                            <i class="pi pi-envelope input-icon"></i>
                        </div>
                        @error('email')
                        <div class="field-error"><i class="pi pi-times-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field">
                        <div class="field-label">
                            Password
                            <a onclick="showView('forgot')">Forgot password?</a>
                        </div>
                        <div class="input-wrap">
                            <input type="password" name="password" id="login-pass"
                                class="field-input" placeholder="Enter your password"
                                autocomplete="current-password" required />
                            <i class="pi pi-lock input-icon"></i>
                            <i class="pi pi-eye input-icon-right" id="pass-toggle"
                                onclick="togglePassword('login-pass', 'pass-toggle')"></i>
                        </div>
                        @error('password')
                        <div class="field-error"><i class="pi pi-times-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="remember-row">
                        <input type="checkbox" id="remember" name="remember" />
                        <label for="remember">Remember me for 30 days</label>
                    </div>

                    <button type="submit" class="btn-primary" id="login-btn">
                        Sign In to Dashboard
                    </button>
                </form>

                <div class="auth-footer">
                    Don't have an account? <span onclick="showView('register')">Create one →</span>
                </div>
            </div>

            <!-- View: REGISTER -->
            {{-- <div id="view-register" class="view" style="display:none;">
                <div class="auth-tabs">
                    <button class="auth-tab" onclick="showView('login', this)">Sign In</button>
                    <button class="auth-tab active" onclick="showView('register', this)">Register</button>
                </div>

                <div class="card-eyebrow">Get started</div>
                <h2 class="card-title">Create your account</h2>
                <p class="card-sub">Join thousands of teams managing their platforms with AdminPanel.</p>

                <form id="register-form" onsubmit="handleRegister(event)">
                    @csrf
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                        <div class="field">
                            <div class="field-label">First name</div>
                            <div class="input-wrap">
                                <input type="text" name="first_name" class="field-input"
                                    placeholder="Arjun" required />
                                <i class="pi pi-user input-icon"></i>
                            </div>
                        </div>
                        <div class="field">
                            <div class="field-label">Last name</div>
                            <div class="input-wrap">
                                <input type="text" name="last_name" class="field-input"
                                    placeholder="Sharma" required />
                                <i class="pi pi-user input-icon"></i>
                            </div>
                        </div>
                    </div>

                    <div class="field">
                        <div class="field-label">Email address</div>
                        <div class="input-wrap">
                            <input type="email" name="email" class="field-input"
                                placeholder="you@example.com" required autocomplete="email" />
                            <i class="pi pi-envelope input-icon"></i>
                        </div>
                    </div>

                    <div class="field">
                        <div class="field-label">Organisation (optional)</div>
                        <div class="input-wrap">
                            <input type="text" name="organisation" class="field-input"
                                placeholder="Acme Corp" />
                            <i class="pi pi-building input-icon"></i>
                        </div>
                    </div>

                    <div class="field">
                        <div class="field-label">Password</div>
                        <div class="input-wrap">
                            <input type="password" name="password" id="reg-pass"
                                class="field-input" placeholder="Min. 8 characters"
                                oninput="checkStrength(this.value)"
                                required autocomplete="new-password" />
                            <i class="pi pi-lock input-icon"></i>
                            <i class="pi pi-eye input-icon-right"
                                onclick="togglePassword('reg-pass', this)" id="reg-eye"></i>
                        </div>
                        <!-- Strength bar -->
                        <div class="strength-bar" id="strength-bar">
                            <div class="strength-segment" id="s1"></div>
                            <div class="strength-segment" id="s2"></div>
                            <div class="strength-segment" id="s3"></div>
                            <div class="strength-segment" id="s4"></div>
                        </div>
                        <div class="strength-label" id="strength-label" style="color:var(--text-muted);"></div>
                    </div>

                    <div class="field">
                        <div class="field-label">Confirm password</div>
                        <div class="input-wrap">
                            <input type="password" name="password_confirmation" id="reg-confirm"
                                class="field-input" placeholder="Re-enter password"
                                required autocomplete="new-password" />
                            <i class="pi pi-lock input-icon"></i>
                        </div>
                    </div>

                    <div class="remember-row" style="margin-bottom:16px;">
                        <input type="checkbox" id="terms" name="terms" required />
                        <label for="terms">
                            I agree to the <a href="#" style="color:var(--accent);">Terms of Service</a> and
                            <a href="#" style="color:var(--accent);">Privacy Policy</a>
                        </label>
                    </div>

                    <button type="submit" class="btn-primary" id="register-btn">
                        Create Account
                    </button>
                </form>

                <div class="auth-footer">
                    Already have an account? <span onclick="showView('login')">Sign in →</span>
                </div>
            </div> --}}

            <!-- View: FORGOT PASSWORD -->
            {{-- <div id="view-forgot" class="view" style="display:none;">

                <div style="margin-bottom:20px;">
                    <button onclick="showView('login')"
                        style="border:none; background:none; color:var(--text-muted); font-size:12.5px; cursor:pointer; display:flex; align-items:center; gap:5px; padding:0; font-family:var(--font-body);">
                        <i class="pi pi-arrow-left"></i> Back to sign in
                    </button>
                </div>

                <div class="card-eyebrow">Password recovery</div>
                <h2 class="card-title">Forgot password?</h2>
                <p class="card-sub">No worries! Enter your email and we'll send a secure reset link.</p>

                <div id="forgot-form-wrap">
                    <form id="forgot-form" onsubmit="handleForgot(event)">
                        @csrf
                        <div class="field">
                            <div class="field-label">Email address</div>
                            <div class="input-wrap">
                                <input type="email" name="email" id="forgot-email"
                                    class="field-input" placeholder="you@example.com" required />
                                <i class="pi pi-envelope input-icon"></i>
                            </div>
                        </div>
                        <button type="submit" class="btn-primary" id="forgot-btn" style="margin-top:4px;">
                            Send Reset Link
                        </button>
                    </form>
                </div>

                <div id="forgot-sent" style="display:none; text-align:center;">
                    <div style="width:56px; height:56px; border-radius:16px; background:rgba(16,185,129,.1); border:1px solid rgba(16,185,129,.2); display:flex; align-items:center; justify-content:center; margin:0 auto 16px; font-size:24px; color:#10b981;">
                        <i class="pi pi-check"></i>
                    </div>
                    <div style="font-size:15px; font-weight:600; color:var(--text); margin-bottom:8px;">Check your inbox</div>
                    <div style="font-size:13px; color:var(--text-muted); line-height:1.6; margin-bottom:24px;">
                        We've sent a password reset link to <strong id="sent-email" style="color:var(--text);"></strong>. It expires in 60 minutes.
                    </div>
                    <button class="btn-primary" onclick="showView('login')">Back to Sign In</button>
                </div>

                <div class="auth-footer" style="margin-top:18px;">
                    Remembered it? <span onclick="showView('login')">Sign in →</span>
                </div>
            </div> --}}

            <!-- View: TWO-FACTOR AUTH -->
            {{-- <div id="view-2fa" class="view" style="display:none;">
                <div class="mfa-notice">
                    <div class="mfa-icon"><i class="pi pi-mobile"></i></div>
                    <div class="card-eyebrow" style="text-align:center;">Two-Factor Auth</div>
                    <h2 class="card-title" style="text-align:center;">Check your device</h2>
                    <p class="card-sub" style="text-align:center;">
                        Enter the 6-digit code from your authenticator app.
                    </p>
                </div>

                <form onsubmit="handle2FA(event)">
                    @csrf
                    <div class="otp-row">
                        <input type="text" maxlength="1" class="otp-box" oninput="otpNext(this)" onkeydown="otpBack(event, this)" />
                        <input type="text" maxlength="1" class="otp-box" oninput="otpNext(this)" onkeydown="otpBack(event, this)" />
                        <input type="text" maxlength="1" class="otp-box" oninput="otpNext(this)" onkeydown="otpBack(event, this)" />
                        <input type="text" maxlength="1" class="otp-box" oninput="otpNext(this)" onkeydown="otpBack(event, this)" />
                        <input type="text" maxlength="1" class="otp-box" oninput="otpNext(this)" onkeydown="otpBack(event, this)" />
                        <input type="text" maxlength="1" class="otp-box" oninput="otpNext(this)" onkeydown="otpBack(event, this)" />
                    </div>

                    <div class="alert alert-info" style="margin-bottom:16px;">
                        <i class="pi pi-info-circle"></i>
                        Code expires in <strong id="otp-timer">05:00</strong>
                    </div>

                    <button type="submit" class="btn-primary">Verify &amp; Continue</button>
                </form>

                <div class="auth-footer" style="margin-top:16px;">
                    Lost access? <a href="#">Use a recovery code</a>
                </div>
                <div class="auth-footer" style="margin-top:8px;">
                    <span onclick="showView('login')" style="color:var(--text-muted); font-weight:400;">← Back to sign in</span>
                </div>
            </div> --}}

        </div><!-- /auth-card -->
    </div><!-- /auth-right -->
</div><!-- /auth-outer -->

<script>
    // ── Theme ──────────────────────────────────────────────────────
    let currentTheme = localStorage.getItem('admin-theme') || 'light';

    function applyTheme(t) {
        document.documentElement.className = t;
        currentTheme = t;
        localStorage.setItem('admin-theme', t);
        const icon = document.getElementById('theme-icon');
        if (icon) icon.className = t === 'dark' ? 'pi pi-sun' : 'pi pi-moon';
    }

    function toggleTheme() {
        applyTheme(currentTheme === 'dark' ? 'light' : 'dark');
    }

    // Init theme on load
    applyTheme(currentTheme);

    // ── View switching ─────────────────────────────────────────────
    const views = ['login', 'register', 'forgot', '2fa'];

    function showView(name, tabBtn) {
        views.forEach(v => {
            const el = document.getElementById('view-' + v);
            if (el) el.style.display = (v === name) ? 'block' : 'none';
        });

        // Update tab active states if triggered from a tab button
        if (tabBtn) {
            document.querySelectorAll('.auth-tab').forEach(t => t.classList.remove('active'));
            tabBtn.classList.add('active');
        }

        // Re-trigger animation
        const active = document.getElementById('view-' + name);
        if (active) {
            active.style.animation = 'none';
            void active.offsetWidth;
            active.style.animation = 'fadeUp 0.3s ease both';
        }
    }

    // ── Password visibility toggle ─────────────────────────────────
    function togglePassword(inputId, iconEl) {
        const input = document.getElementById(inputId);
        const icon  = typeof iconEl === 'string' ? document.getElementById(iconEl) : iconEl;
        if (input.type === 'password') {
            input.type = 'text';
            if (icon) icon.className = icon.className.replace('pi-eye', 'pi-eye-slash');
        } else {
            input.type = 'password';
            if (icon) icon.className = icon.className.replace('pi-eye-slash', 'pi-eye');
        }
    }

    // ── Password strength ──────────────────────────────────────────
    const strengthColors = ['#ef4444', '#f97316', '#f59e0b', '#22c55e'];
    const strengthLabels = ['Weak', 'Fair', 'Good', 'Strong'];

    function checkStrength(val) {
        let score = 0;
        if (val.length >= 8) score++;
        if (/[A-Z]/.test(val)) score++;
        if (/\d/.test(val)) score++;
        if (/[^a-zA-Z0-9]/.test(val)) score++;

        for (let i = 1; i <= 4; i++) {
            const seg = document.getElementById('s' + i);
            if (seg) seg.style.background = i <= score ? strengthColors[score - 1] : 'var(--border)';
        }

        const label = document.getElementById('strength-label');
        if (label) {
            label.textContent = val.length > 0 ? strengthLabels[score - 1] || '' : '';
            label.style.color = val.length > 0 ? strengthColors[score - 1] : 'var(--text-muted)';
        }
    }

    // ── OTP input navigation ───────────────────────────────────────
    function otpNext(el) {
        el.value = el.value.replace(/\D/, '');
        if (el.value && el.nextElementSibling) el.nextElementSibling.focus();
    }

    function otpBack(e, el) {
        if (e.key === 'Backspace' && !el.value && el.previousElementSibling) {
            el.previousElementSibling.focus();
        }
    }

    // ── OTP countdown ─────────────────────────────────────────────
    function startOtpTimer() {
        let seconds = 300;
        const el = document.getElementById('otp-timer');
        const t = setInterval(() => {
            if (!el || seconds <= 0) { clearInterval(t); return; }
            const m = String(Math.floor(seconds / 60)).padStart(2, '0');
            const s = String(seconds % 60).padStart(2, '0');
            el.textContent = `${m}:${s}`;
            seconds--;
        }, 1000);
    }

    // ── Form handlers ──────────────────────────────────────────────
    function setLoading(btnId, loading, label) {
        const btn = document.getElementById(btnId);
        if (!btn) return;
        btn.disabled = loading;
        btn.innerHTML = loading
            ? `<span class="btn-spinner"></span>${label}`
            : label;
    }

    function handleLogin(e) {
        e.preventDefault();
        setLoading('login-btn', true, 'Signing in…');

        // Simulate — replace with real fetch/axios POST
        setTimeout(() => {
            // Demo: show 2FA after login
            // In production: redirect to dashboard or show 2FA if enabled
            setLoading('login-btn', false, 'Sign In to Dashboard');

            // Uncomment to show 2FA view:
            // showView('2fa'); startOtpTimer(); return;

            // Or navigate:
            // window.location.href = '/admin';

            // For demo: just show an alert
            alert('✅ Login successful! Redirecting to /admin …\n\n(Wire the form action to POST /admin/login in Laravel)');
        }, 1400);
    }

    function handleRegister(e) {
        e.preventDefault();
        const pass    = document.getElementById('reg-pass').value;
        const confirm = document.getElementById('reg-confirm').value;
        if (pass !== confirm) {
            alert('Passwords do not match.');
            return;
        }
        setLoading('register-btn', true, 'Creating account…');
        setTimeout(() => {
            setLoading('register-btn', false, 'Create Account');
            alert('✅ Account created! Check your email for verification.\n\n(Wire to POST /admin/register in Laravel)');
        }, 1400);
    }

    function handleForgot(e) {
        e.preventDefault();
        const email = document.getElementById('forgot-email').value;
        setLoading('forgot-btn', true, 'Sending link…');
        setTimeout(() => {
            setLoading('forgot-btn', false, 'Send Reset Link');
            document.getElementById('sent-email').textContent = email;
            document.getElementById('forgot-form-wrap').style.display = 'none';
            document.getElementById('forgot-sent').style.display = 'block';
        }, 1200);
    }

    function handle2FA(e) {
        e.preventDefault();
        const boxes = document.querySelectorAll('.otp-box');
        const code  = [...boxes].map(b => b.value).join('');
        if (code.length < 6) { alert('Please enter the full 6-digit code.'); return; }
        alert(`✅ Code ${code} verified! Redirecting…\n\n(Wire to POST /admin/2fa in Laravel)`);
    }

    function socialLogin(provider) {
        // Redirect to Laravel Socialite
        window.location.href = `/auth/${provider}/redirect`;
    }
</script>
</body>
</html>
