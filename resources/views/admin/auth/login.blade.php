<x-admin::layouts.anonymous :title="'Admin Login'">

    @pushOnce('styles')
        <style>
            *,
            *::before,
            *::after {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }

            :root {
                --font-display: 'Syne', sans-serif;
                --font-body: 'DM Sans', sans-serif;

                /* Light */
                --bg: #f0f2f9;
                --panel-bg: #ffffff;
                --border: #e4e8f0;
                --text: #0e1422;
                --text-muted: #6b7694;
                --accent: #4f46e5;
                --accent-2: #7c3aed;
                --accent-light: rgba(79, 70, 229, .08);
                --input-bg: #f7f8fc;
                --shadow: 0 8px 40px rgba(0, 0, 0, .08);
                --shadow-lg: 0 20px 60px rgba(0, 0, 0, .12);
                --error: #ef4444;
                --success: #10b981;

                --orb-1: radial-gradient(circle, rgba(99, 102, 241, .35) 0%, transparent 70%);
                --orb-2: radial-gradient(circle, rgba(124, 58, 237, .25) 0%, transparent 70%);
                --orb-3: radial-gradient(circle, rgba(6, 182, 212, .2) 0%, transparent 70%);
            }

            html.dark {
                --bg: #080c18;
                --panel-bg: #0f1525;
                --border: #1c2540;
                --text: #e8ecf8;
                --text-muted: #5a6892;
                --accent: #6366f1;
                --accent-2: #8b5cf6;
                --accent-light: rgba(99, 102, 241, .12);
                --input-bg: #121930;
                --shadow: 0 8px 40px rgba(0, 0, 0, .4);
                --shadow-lg: 0 20px 60px rgba(0, 0, 0, .6);
                --orb-1: radial-gradient(circle, rgba(99, 102, 241, .2) 0%, transparent 70%);
                --orb-2: radial-gradient(circle, rgba(124, 58, 237, .15) 0%, transparent 70%);
                --orb-3: radial-gradient(circle, rgba(6, 182, 212, .12) 0%, transparent 70%);
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

            .orb-1 {
                width: 500px;
                height: 500px;
                top: -100px;
                right: -50px;
                background: var(--orb-1);
                animation-delay: 0s;
            }

            .orb-2 {
                width: 400px;
                height: 400px;
                bottom: -80px;
                left: 5%;
                background: var(--orb-2);
                animation-delay: -3s;
            }

            .orb-3 {
                width: 300px;
                height: 300px;
                top: 40%;
                left: 45%;
                background: var(--orb-3);
                animation-delay: -5s;
            }

            @keyframes float {

                0%,
                100% {
                    transform: translateY(0) scale(1);
                }

                50% {
                    transform: translateY(-30px) scale(1.05);
                }
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
                width: 36px;
                height: 36px;
                background: linear-gradient(135deg, var(--accent), var(--accent-2));
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-family: var(--font-display);
                font-weight: 800;
                color: #fff;
                font-size: 16px;
                box-shadow: 0 4px 12px rgba(99, 102, 241, .4);
            }

            .logo-name {
                font-family: var(--font-display);
                font-weight: 700;
                font-size: 18px;
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
                border: 1px solid rgba(99, 102, 241, .2);
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
                width: 28px;
                height: 28px;
                border-radius: 8px;
                background: var(--accent-light);
                border: 1px solid rgba(99, 102, 241, .2);
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                font-size: 12px;
                color: var(--accent);
            }

            /* Testimonial */
            .auth-left-footer {
                background: var(--accent-light);
                border: 1px solid rgba(99, 102, 241, .15);
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
                width: 32px;
                height: 32px;
                border-radius: 50%;
                background: linear-gradient(135deg, var(--accent), var(--accent-2));
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 12px;
                font-weight: 700;
                color: #fff;
            }

            .t-name {
                font-size: 12.5px;
                font-weight: 600;
                color: var(--text);
            }

            .t-role {
                font-size: 11px;
                color: var(--text-muted);
            }

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
                animation: cardIn 0.5s cubic-bezier(.22, 1, .36, 1) both;
            }

            @keyframes cardIn {
                from {
                    opacity: 0;
                    transform: translateY(24px) scale(.97);
                }

                to {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
            }

            /* Glow top border */
            .auth-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 10%;
                right: 10%;
                height: 1px;
                background: linear-gradient(90deg, transparent, var(--accent), transparent);
            }

            /* ── Theme Toggle ── */
            .theme-toggle {
                position: absolute;
                top: 20px;
                right: 20px;
                width: 34px;
                height: 34px;
                border-radius: 8px;
                border: 1px solid var(--border);
                background: var(--input-bg);
                color: var(--text-muted);
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 13px;
                transition: all 0.2s;
            }

            .theme-toggle:hover {
                color: var(--text);
                background: var(--accent-light);
            }

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
                box-shadow: 0 1px 4px rgba(0, 0, 0, .1);
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
                border-color: rgba(99, 102, 241, .3);
            }

            .social-icon {
                font-size: 15px;
            }

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

            .divider::before,
            .divider::after {
                content: '';
                flex: 1;
                height: 1px;
                background: var(--border);
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

            .field-label a:hover {
                text-decoration: underline;
            }

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

            .field-input::placeholder {
                color: var(--text-muted);
                opacity: 0.7;
            }

            .field-input:focus {
                border-color: var(--accent);
                background: var(--panel-bg);
                box-shadow: 0 0 0 3px rgba(99, 102, 241, .1);
            }

            .field-input.error {
                border-color: var(--error);
                box-shadow: 0 0 0 3px rgba(239, 68, 68, .1);
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

            .field-input:focus~.input-icon {
                color: var(--accent);
            }

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

            .input-icon-right:hover {
                color: var(--text);
            }

            /* Input no-left-icon variant */
            .field-input.no-icon {
                padding-left: 14px;
            }

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
                width: 16px;
                height: 16px;
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
                background: linear-gradient(rgba(255, 255, 255, .15), transparent);
            }

            .btn-primary:hover {
                transform: translateY(-1px);
                box-shadow: 0 8px 24px rgba(99, 102, 241, .4);
            }

            .btn-primary:active {
                transform: translateY(0);
            }

            .btn-primary:disabled {
                opacity: 0.6;
                cursor: not-allowed;
                transform: none;
            }

            /* Loading spinner inside button */
            .btn-spinner {
                display: inline-block;
                width: 16px;
                height: 16px;
                border: 2px solid rgba(255, 255, 255, .4);
                border-top-color: #fff;
                border-radius: 50%;
                animation: spin 0.7s linear infinite;
                vertical-align: middle;
                margin-right: 8px;
            }

            @keyframes spin {
                to {
                    transform: rotate(360deg);
                }
            }

            /* ── Footer links ── */
            .auth-footer {
                text-align: center;
                margin-top: 20px;
                font-size: 13px;
                color: var(--text-muted);
            }

            .auth-footer a,
            .auth-footer span {
                color: var(--accent);
                font-weight: 600;
                cursor: pointer;
                text-decoration: none;
            }

            .auth-footer a:hover {
                text-decoration: underline;
            }

            /* ── Password strength bar ── */
            .strength-bar {
                display: flex;
                gap: 4px;
                margin-top: 8px;
            }

            .strength-segment {
                flex: 1;
                height: 3px;
                border-radius: 99px;
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

            .alert-error {
                background: rgba(239, 68, 68, .08);
                border: 1px solid rgba(239, 68, 68, .2);
                color: #ef4444;
            }

            .alert-success {
                background: rgba(16, 185, 129, .08);
                border: 1px solid rgba(16, 185, 129, .2);
                color: #10b981;
            }

            .alert-info {
                background: var(--accent-light);
                border: 1px solid rgba(99, 102, 241, .2);
                color: var(--accent);
            }

            /* ── OTP boxes (2FA) ── */
            .otp-row {
                display: flex;
                gap: 8px;
                justify-content: center;
                margin: 20px 0;
            }

            .otp-box {
                width: 48px;
                height: 54px;
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
                box-shadow: 0 0 0 3px rgba(99, 102, 241, .12);
            }

            /* ── Responsive ── */
            @media (max-width: 900px) {
                .auth-outer {
                    grid-template-columns: 1fr;
                }

                .auth-left {
                    display: none;
                }

                .auth-right {
                    padding: 24px 16px;
                    min-height: 100vh;
                }
            }

            /* ── Animations ── */
            .view {
                animation: fadeUp 0.3s ease both;
            }

            @keyframes fadeUp {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* ── MFA notice ── */
            .mfa-notice {
                text-align: center;
                margin-bottom: 8px;
            }

            .mfa-icon {
                width: 56px;
                height: 56px;
                border-radius: 16px;
                background: var(--accent-light);
                border: 1px solid rgba(99, 102, 241, .2);
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 14px;
                font-size: 22px;
                color: var(--accent);
            }
        </style>
    @endPushOnce

    <v-login></v-login>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-login-template">

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
                        The admin panel<br />
                        that <span class="gradient-text">scales with you</span>
                    </h1>
                    <p class="hero-sub">
                        Multi-tenant, multi-theme, role-based access control — everything your platform needs in one elegant
                        dashboard.
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
                        "We migrated 7 tenants to AdminPanel in a week. The role system and theme engine saved us months of
                        work."
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

                        <form id="login-form" @submit.prevent="handleLogin">
                            @csrf
                            <div class="field">
                                <div class="field-label">Email address</div>
                                <div class="input-wrap">
                                    <input type="email" name="email" id="login-email" class="field-input" v-model="email"
                                        placeholder="you@example.com" value="{{ old('email') }}" autocomplete="email" />
                                    <i class="pi pi-envelope input-icon"></i>
                                </div>
                            </div>

                            <div class="field">
                                {{-- <div class="field-label">
                                    Password
                                    <a onclick="showView('forgot')">Forgot password?</a>
                                </div> --}}
                                <div class="input-wrap">
                                    <input :type="togglePassword ? 'text' : 'password'" name="password" id="login-pass"
                                        class="field-input" v-model="password" placeholder="Enter your password"
                                        autocomplete="current-password" />
                                    <i class="pi pi-lock input-icon"></i>
                                    <i class="pi input-icon-right" :class="togglePassword ? 'pi pi-eye' : 'pi pi-eye-slash'"
                                        @click="togglePassword = !togglePassword"></i>
                                </div>
                            </div>

                            <div class="remember-row">
                                <input type="checkbox" id="remember" name="remember" v-model="remember_me" />
                                <label for="remember">Remember me</label>
                            </div>

                            <button type="submit" class="btn-primary" id="login-btn" v-if="!isLoading">
                                Sign In to Dashboard
                            </button>
                            <button type="submit" class="btn-primary" id="login-btn" v-else disabled>
                                Signing In...
                            </button>
                        </form>
                    </div>

                </div><!-- /auth-card -->
            </div><!-- /auth-right -->
        </div>

        </script>

        <script type="module">
            adminVueApp.component('v-login', {
                template: '#v-login-template',

                data() {
                    return {
                        isLoading: false,
                        togglePassword: false,
                        email: 'admin@admin.com',
                        password: 'password',
                        remember_me: '',
                    }
                },

                mounted() {
                   
                },
                methods: {
                    handleLogin() {
                        // return;
                        this.isLoading = true;

                        this.$axios.post("{{ route('admin.verify_login') }}", {
                            email: this.email,
                            password: this.password,
                            remember_me: this.remember_me,
                        } 
                    )
                            .then((response) => {
                                console.log(response);

                                response = response?.data; 

                                window.location.href = '{{ route('admin.dashboard') }}'
                            })
                            .catch((error) => {
                                this.isLoading = false;

                                if (error.response?.status == 422) {
                                    this.$emitter.emit('add-flash', { type: 'error', message: error?.response?.data?.errors[0] || 'Login failed!' });

                                    return;
                                }

                                this.$emitter.emit('add-flash', { type: 'error', message: error?.response?.data?.message || 'Login failed!' });
                            });
                    }

                }
            });
        </script>
 
    @endPushOnce
</x-admin::layouts.anonymous>
