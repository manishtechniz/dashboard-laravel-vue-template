<x-admin::layouts.anonymous :title="'Admin Login | Mid Night Club & Lounge'">

    @pushOnce('styles')
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: #080c16;
            color: var(--text-base, #e2e8f0);
            font-family: var(--font-sans, 'DM Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* ── FULLSCREEN FADING BACKGROUND CAROUSEL ── */
        .bg-slideshow-container {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
            background-color: #060913;
        }

        .bg-slide-item {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0;
            transform: scale(1.08);
            transition: opacity 1.4s cubic-bezier(0.4, 0, 0.2, 1), transform 9s cubic-bezier(0.25, 1, 0.5, 1);
            filter: brightness(0.92) contrast(1.05);
        }

        .bg-slide-item.active {
            opacity: 1;
            transform: scale(1);
        }

        /* Multi-layer atmospheric vignette overlay */
        .bg-gradient-overlay {
            position: absolute;
            inset: 0;
            z-index: 1;
            background: linear-gradient(135deg,
                    rgba(8, 12, 24, 0.88) 0%,
                    rgba(8, 12, 24, 0.65) 45%,
                    rgba(8, 12, 24, 0.85) 75%,
                    rgba(8, 12, 24, 0.96) 100%);
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
        }

        html.light .bg-gradient-overlay {
            background: linear-gradient(135deg,
                    rgba(244, 246, 251, 0.92) 0%,
                    rgba(244, 246, 251, 0.75) 45%,
                    rgba(244, 246, 251, 0.9) 75%,
                    rgba(244, 246, 251, 0.98) 100%);
        }

        /* Ambient accent illumination in corners */
        .ambient-glow-1 {
            position: absolute;
            top: -150px;
            left: -100px;
            width: 550px;
            height: 550px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.25) 0%, transparent 70%);
            z-index: 1;
            pointer-events: none;
            filter: blur(60px);
        }

        .ambient-glow-2 {
            position: absolute;
            bottom: -150px;
            right: 25%;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(168, 85, 247, 0.2) 0%, transparent 70%);
            z-index: 1;
            pointer-events: none;
            filter: blur(60px);
        }

        /* ── MAIN AUTH LAYOUT ── */
        .auth-layout-container {
            position: relative;
            z-index: 2;
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            padding: 40px 60px;
            gap: 40px;
            align-items: center;
        }

        /* ── LEFT SHOWCASE COLUMN ── */
        .showcase-column {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: calc(100vh - 80px);
            padding: 16px 0;
        }

        /* Top brand bar */
        .brand-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand-lockup {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .brand-crown-mark {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: 0 4px 18px rgba(99, 102, 241, 0.45);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .brand-crown-mark i {
            color: #fbbf24;
            filter: drop-shadow(0 2px 4px rgba(251, 191, 36, 0.5));
        }

        .brand-text-block h3 {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.4px;
            color: var(--text-base, #ffffff);
            line-height: 1.1;
        }

        .brand-text-block span {
            font-size: 11.5px;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            font-weight: 600;
            color: var(--accent, #818cf8);
        }

        .live-venue-status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(34, 197, 94, 0.12);
            border: 1px solid rgba(34, 197, 94, 0.3);
            padding: 6px 14px;
            border-radius: 99px;
            font-size: 12px;
            font-weight: 600;
            color: #4ade80;
            backdrop-filter: blur(8px);
        }

        .status-indicator-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 8px #22c55e;
            animation: pulseStatus 2s infinite;
        }

        @keyframes pulseStatus {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.5;
                transform: scale(1.2);
            }
        }

        /* Center Hero Info */
        .showcase-hero-body {
            margin: 40px 0;
            max-width: 580px;
        }

        .venue-badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(10px);
            padding: 6px 14px;
            border-radius: 99px;
            font-size: 12.5px;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 18px;
        }

        html.light .venue-badge-pill {
            background: rgba(0, 0, 0, 0.06);
            border-color: rgba(0, 0, 0, 0.12);
            color: #1e293b;
        }

        .venue-badge-pill i {
            color: var(--accent, #6366f1);
        }

        .hero-headline {
            font-size: clamp(32px, 3.2vw, 46px);
            font-weight: 800;
            line-height: 1.12;
            letter-spacing: -1px;
            color: #ffffff;
            margin-bottom: 16px;
            transition: opacity 0.4s ease;
        }

        html.light .hero-headline {
            color: #0f172a;
        }

        .hero-description {
            font-size: 15px;
            color: rgba(255, 255, 255, 0.82);
            line-height: 1.65;
            margin-bottom: 30px;
        }

        html.light .hero-description {
            color: #475569;
        }

        /* Live Venue KPI Stats Bar */
        .venue-kpi-bar {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            background: rgba(15, 23, 42, 0.55);
            border: 1px solid rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(16px);
            border-radius: 16px;
            padding: 16px 20px;
            margin-bottom: 24px;
        }

        html.light .venue-kpi-bar {
            background: rgba(255, 255, 255, 0.7);
            border-color: rgba(0, 0, 0, 0.08);
        }

        .kpi-unit {
            display: flex;
            flex-direction: column;
        }

        .kpi-unit-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #94a3b8;
            font-weight: 600;
        }

        .kpi-unit-value {
            font-size: 17px;
            font-weight: 700;
            color: #ffffff;
            margin-top: 3px;
        }

        html.light .kpi-unit-value {
            color: #0f172a;
        }

        .kpi-unit-value.green {
            color: #4ade80;
        }

        /* Interactive Carousel Controls / Area Pills */
        .carousel-controls-bottom {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .area-tabs-list {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        .area-tab-btn {
            background: rgba(15, 23, 42, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 10px 12px;
            cursor: pointer;
            text-align: left;
            position: relative;
            overflow: hidden;
            transition: all 0.25s ease;
            backdrop-filter: blur(10px);
        }

        html.light .area-tab-btn {
            background: rgba(255, 255, 255, 0.6);
            border-color: rgba(0, 0, 0, 0.08);
        }

        .area-tab-btn:hover {
            background: rgba(99, 102, 241, 0.15);
            border-color: rgba(99, 102, 241, 0.35);
        }

        .area-tab-btn.active {
            background: rgba(99, 102, 241, 0.25);
            border-color: var(--accent, #6366f1);
            box-shadow: 0 4px 16px rgba(99, 102, 241, 0.3);
        }

        .tab-index-tag {
            font-size: 10px;
            font-weight: 700;
            color: var(--accent, #818cf8);
            letter-spacing: 0.5px;
            display: block;
            margin-bottom: 2px;
        }

        .tab-title-text {
            font-size: 12px;
            font-weight: 600;
            color: #ffffff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
        }

        html.light .tab-title-text {
            color: #1e293b;
        }

        /* Animated progress line at bottom of active tab */
        .tab-progress-line {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 2.5px;
            background: var(--accent, #6366f1);
            width: 0%;
            transition: width 0.1s linear;
        }

        .area-tab-btn.active .tab-progress-line {
            width: 100%;
            animation: tabProgress 6s linear infinite;
        }

        @keyframes tabProgress {
            0% {
                width: 0%;
            }

            100% {
                width: 100%;
            }
        }

        /* ── RIGHT COLUMN: GLASSMORPHIC SIGN-IN CARD ── */
        .card-column {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
        }

        .glass-auth-card {
            width: 100%;
            max-width: 440px;
            background: rgba(19, 25, 41, 0.78);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 24px;
            padding: 38px 36px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.45), 0 0 0 1px rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(28px);
            -webkit-backdrop-filter: blur(28px);
            position: relative;
            overflow: hidden;
            animation: cardFadeIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        html.light .glass-auth-card {
            background: rgba(255, 255, 255, 0.85);
            border-color: rgba(0, 0, 0, 0.08);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12), 0 0 0 1px rgba(0, 0, 0, 0.04);
        }

        @keyframes cardFadeIn {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Glowing top accent strip */
        .glass-auth-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2.5px;
            background: linear-gradient(90deg, #6366f1, #a855f7, #ec4899);
        }

        /* Card Header Bar */
        .card-header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
        }

        .card-eyebrow-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--accent, #818cf8);
        }

        .theme-toggle-icon-btn {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(255, 255, 255, 0.06);
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 14px;
        }

        html.light .theme-toggle-icon-btn {
            border-color: rgba(0, 0, 0, 0.1);
            background: rgba(0, 0, 0, 0.04);
            color: #64748b;
        }

        .theme-toggle-icon-btn:hover {
            color: var(--accent, #6366f1);
            border-color: var(--accent, #6366f1);
            background: rgba(99, 102, 241, 0.12);
        }

        .card-main-title {
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: var(--text-base, #ffffff);
            margin-bottom: 6px;
        }

        html.light .card-main-title {
            color: #0f172a;
        }

        .card-main-subtitle {
            font-size: 13px;
            color: #94a3b8;
            line-height: 1.5;
            margin-bottom: 24px;
        }

        html.light .card-main-subtitle {
            color: #64748b;
        }

        /* Quick Demo Auto-fill Pill */
        .quick-fill-box {
            background: rgba(99, 102, 241, 0.08);
            border: 1px dashed rgba(99, 102, 241, 0.35);
            border-radius: 10px;
            padding: 9px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            margin-bottom: 20px;
            transition: all 0.2s ease;
        }

        .quick-fill-box:hover {
            background: rgba(99, 102, 241, 0.15);
            border-color: var(--accent, #6366f1);
        }

        .quick-fill-info {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 600;
            color: #e2e8f0;
        }

        html.light .quick-fill-info {
            color: #1e293b;
        }

        .quick-fill-info i {
            color: var(--accent, #818cf8);
        }

        .quick-fill-tag {
            font-size: 10.5px;
            font-weight: 700;
            background: var(--accent, #6366f1);
            color: #ffffff;
            padding: 2px 7px;
            border-radius: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Form Elements */
        .form-field-group {
            margin-bottom: 18px;
        }

        .form-field-label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            color: #e2e8f0;
            margin-bottom: 7px;
        }

        html.light .form-field-label {
            color: #1e293b;
        }

        .input-box-wrap {
            position: relative;
        }

        .form-text-input {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border-radius: 11px;
            border: 1.5px solid rgba(255, 255, 255, 0.12);
            background: rgba(11, 15, 26, 0.6);
            color: #ffffff;
            font-family: inherit;
            font-size: 13.5px;
            outline: none;
            transition: all 0.2s ease;
            -webkit-appearance: none;
        }

        html.light .form-text-input {
            background: #f8fafc;
            border-color: #e2e8f0;
            color: #0f172a;
        }

        .form-text-input:focus {
            border-color: var(--accent, #6366f1);
            background: rgba(11, 15, 26, 0.9);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }

        html.light .form-text-input:focus {
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }

        .field-icon-left {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 14px;
            pointer-events: none;
            transition: color 0.2s;
        }

        .form-text-input:focus~.field-icon-left {
            color: var(--accent, #818cf8);
        }

        .field-btn-right {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 14px;
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .field-btn-right:hover {
            color: #ffffff;
        }

        html.light .field-btn-right:hover {
            color: #0f172a;
        }

        /* Checkbox & Links */
        .remember-flex-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
        }

        .custom-check-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12.5px;
            color: #94a3b8;
            cursor: pointer;
            user-select: none;
        }

        .custom-check-label input[type="checkbox"] {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            accent-color: var(--accent, #6366f1);
            cursor: pointer;
        }

        /* Submit Button */
        .btn-auth-primary {
            width: 100%;
            padding: 13px 20px;
            border-radius: 11px;
            border: none;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: #ffffff;
            font-size: 14.5px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
            position: relative;
            overflow: hidden;
        }

        .btn-auth-primary:hover {
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            transform: translateY(-1.5px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.5);
        }

        .btn-auth-primary:active {
            transform: translateY(0);
        }

        .btn-auth-primary:disabled {
            opacity: 0.65;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .auth-spinner {
            width: 16px;
            height: 16px;
            border: 2.5px solid rgba(255, 255, 255, 0.35);
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spinSpinner 0.7s linear infinite;
        }

        @keyframes spinSpinner {
            to {
                transform: rotate(360deg);
            }
        }

        /* Alerts */
        .auth-alert-message {
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 12.5px;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
            line-height: 1.4;
        }

        .auth-alert-error {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #f87171;
        }

        .auth-alert-success {
            background: rgba(34, 197, 94, 0.12);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #4ade80;
        }

        /* Card Bottom Security Badge */
        .card-security-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 22px;
            font-size: 11.5px;
            color: #64748b;
            text-align: center;
        }

        .card-security-badge i {
            color: #22c55e;
            font-size: 12px;
        }

        @media (max-width: 768px) {
            .auth-layout-container {
                padding: 24px 2px !important;
            }
        }

        /* ── RESPONSIVENESS ── */
        @media (max-width: 1024px) {
            .auth-layout-container {
                grid-template-columns: 1fr;
                padding: 24px 18px;
                gap: 24px;
            }

            .showcase-column {
                min-height: auto;
                padding: 0;
            }

            .showcase-hero-body,
            .venue-kpi-bar,
            .carousel-controls-bottom {
                display: none;
            }

            .card-column {
                padding: 12px 0;
            }

            .glass-auth-card {
                padding: 30px 22px;
            }
        }
    </style>
    @endPushOnce

    <v-login></v-login>

    @pushOnce('scripts')
    <script type="text/x-template" id="v-login-template">
        <div>
            <!-- ══ FULLSCREEN FADING BACKGROUND IMAGE CAROUSEL ══ -->
            <div class="bg-slideshow-container">
                <div 
                    v-for="(img, idx) in venueImages" 
                    :key="'bg-slide-' + idx"
                    class="bg-slide-item"
                    :class="{ active: currentImageIndex === idx }"
                    :style="{ backgroundImage: 'url(' + img.src + ')' }"
                ></div>

                <!-- Multi-stop atmospheric darkness overlay for legibility -->
                <div class="bg-gradient-overlay"></div>
                <div class="ambient-glow-1"></div>
                <div class="ambient-glow-2"></div>
            </div>

            <!-- ══ MAIN INTERFACE LAYOUT ══ -->
            <div class="auth-layout-container">
                <!-- ══ LEFT SHOWCASE: VENUE INFORMATION & CAROUSEL TABS ══ -->
                <div class="showcase-column">
                    <!-- Brand Top Header -->
                    <div class="brand-header">
                        <div class="brand-lockup">
                            <div class="brand-crown-mark">
                                <i class="pi pi-crown"></i>
                            </div>
                            <div class="brand-text-block">
                                <h3>Mid Night</h3> 
                            </div>
                        </div> 
                    </div>

                    <!-- Center Showcase Hero with smooth dynamic text -->
                    <div class="showcase-hero-body">
                        <div class="venue-badge-pill">
                            <i class="pi pi-map-pin"></i>
                            <span>@{{ venueImages[currentImageIndex].badge }}</span>
                        </div>

                        <h1 class="hero-headline">
                            @{{ venueImages[currentImageIndex].title }}
                        </h1>

                        <p class="hero-description">
                            @{{ venueImages[currentImageIndex].description }}
                        </p>

                        <!-- Live KPI Status Metrics -->
                        <div class="venue-kpi-bar">
                            <div class="kpi-unit">
                                <span class="kpi-unit-label">VIP Tables</span>
                                <span class="kpi-unit-value green">94% Booked</span>
                            </div>
                            <div class="kpi-unit">
                                <span class="kpi-unit-label">Guestlist</span>
                                <span class="kpi-unit-value">1,280+ Checked</span>
                            </div>
                            <div class="kpi-unit">
                                <span class="kpi-unit-label">Live Floors</span>
                                <span class="kpi-unit-value">4 Active</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Interactive Carousel Area Tabs -->
                    <div class="carousel-controls-bottom">
                        <div class="area-tabs-list">
                            <button 
                                v-for="(img, idx) in venueImages" 
                                :key="'tab-' + idx"
                                type="button" 
                                class="area-tab-btn"
                                :class="{ active: currentImageIndex === idx }"
                                @click="selectImage(idx)"
                            >
                                <span class="tab-index-tag">0@{{ idx + 1 }} AREA</span>
                                <span class="tab-title-text">@{{ img.shortName }}</span>
                                <div class="tab-progress-line" v-if="currentImageIndex === idx"></div>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ══ RIGHT COLUMN: GLASSMORPHIC LOGIN CARD ══ -->
                <div class="card-column">
                    <div class="glass-auth-card" id="auth-card">
                        <!-- Card Header & Theme Switcher -->
                        <div class="card-header-row">
                            <div class="card-eyebrow-tag">
                                <i class="pi pi-shield"></i>
                                <span>Management Portal</span>
                            </div>

                            <!-- <button 
                                type="button" 
                                class="theme-toggle-icon-btn" 
                                @click="toggleTheme" 
                                id="theme-btn" 
                                :title="isDark ? 'Switch to Light Theme' : 'Switch to Dark Theme'"
                            >
                                <i :class="isDark ? 'pi pi-sun' : 'pi pi-moon'"></i>
                            </button> -->
                        </div>

                        <h2 class="card-main-title">Sign In</h2>
                        <p class="card-main-subtitle">Enter your credentials to access the venue management suite.</p>

                        <!-- Quick Demo Credentials Box -->
                        <div class="quick-fill-box" @click="fillAdminCredentials" title="Click to auto-fill default admin credentials">
                            <div class="quick-fill-info">
                                <i class="pi pi-user-plus"></i>
                                <span>Admin: admin@admin.com</span>
                            </div>
                            <span class="quick-fill-tag">Auto-Fill</span>
                        </div>

                        <!-- Server Session Alerts -->
                        @if(session('error'))
                            <div class="auth-alert-message auth-alert-error">
                                <i class="pi pi-times-circle"></i>
                                <span>{{ session('error') }}</span>
                            </div>
                        @endif
                        @if(session('success'))
                            <div class="auth-alert-message auth-alert-success">
                                <i class="pi pi-check-circle"></i>
                                <span>{{ session('success') }}</span>
                            </div>
                        @endif

                        <!-- Form -->
                        <form id="login-form" @submit.prevent="handleLogin">
                            @csrf

                            <!-- Email Field -->
                            <div class="form-field-group">
                                <label class="form-field-label" for="login-email">Email Address</label>
                                <div class="input-box-wrap">
                                    <input 
                                        type="email" 
                                        name="email" 
                                        id="login-email" 
                                        class="form-text-input" 
                                        v-model="email" 
                                        placeholder="admin@admin.com" 
                                        value="{{ old('email') }}" 
                                        autocomplete="email" 
                                        required 
                                    />
                                    <i class="pi pi-envelope field-icon-left"></i>
                                </div>
                            </div>

                            <!-- Password Field -->
                            <div class="form-field-group">
                                <label class="form-field-label" for="login-pass">Password</label>
                                <div class="input-box-wrap">
                                    <input 
                                        :type="togglePassword ? 'text' : 'password'" 
                                        name="password" 
                                        id="login-pass" 
                                        class="form-text-input" 
                                        v-model="password" 
                                        placeholder="••••••••••••" 
                                        autocomplete="current-password" 
                                        required 
                                    />
                                    <i class="pi pi-lock field-icon-left"></i>
                                    <button 
                                        type="button" 
                                        class="field-btn-right" 
                                        @click="togglePassword = !togglePassword"
                                        title="Toggle password visibility"
                                    >
                                        <i :class="togglePassword ? 'pi pi-eye' : 'pi pi-eye-slash'"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Remember Options -->
                            <div class="remember-flex-row">
                                <label class="custom-check-label" for="remember">
                                    <input type="checkbox" id="remember" name="remember" v-model="remember_me" />
                                    <span>Keep session active</span>
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn-auth-primary" id="login-btn" :disabled="isLoading">
                                <template v-if="!isLoading">
                                    <span>Sign In to Dashboard</span>
                                    <i class="pi pi-arrow-right"></i>
                                </template>
                                <template v-else>
                                    <div class="auth-spinner"></div>
                                    <span>Authenticating Access...</span>
                                </template>
                            </button>
                        </form>

                        <!-- Security Note -->
                        <div class="card-security-badge">
                            <i class="pi pi-shield"></i>
                            <span>256-Bit SSL Encrypted Terminal &bull; Mid Night Venue Suite</span>
                        </div>
                    </div>
                </div>
            </div>
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
                    isDark: true,
                    currentImageIndex: 0,
                    slideInterval: null,
                    venueImages: [{
                            src: "{{ asset('images/club3.webp') }}",
                            shortName: "Main Stage",
                            badge: "The Midnight Club & Lounge",
                            title: "Main Stage & VIP Party Arena",
                            description: "Interactive reservation controls for stage-side VIP tables, DJ sound booths, and custom celebrations."
                        },
                        {
                            src: "{{ asset('images/club1.jpg') }}",
                            shortName: "Dance Floor",
                            badge: "Central Arena & DJ Stage",
                            title: "Real-Time Venue Floor Plan",
                            description: "Visual table layout allocation with live availability status, guest count tracking, and minimum spend limits."
                        },
                        {
                            src: "{{ asset('images/club2.webp') }}",
                            shortName: "VIP Lounge",
                            badge: "Upper Level • VIP Section",
                            title: "Midnight VIP Lounge & Bottle Service",
                            description: "Dedicated guestlist concierge, bottle pre-orders, and personalized VIP hospitality service."
                        },
                        {
                            src: "{{ asset('images/club4.webp') }}",
                            shortName: "Booths & Seating",
                            badge: "Exclusive Guest Seating",
                            title: "Seamless Guest & Booking Operations",
                            description: "Fast door check-in, automated SMS/email booking confirmations, and real-time revenue analytics."
                        }
                    ]
                }
            },

            mounted() {
                // Check initial dark/light theme state
                this.isDark = document.documentElement.classList.contains('dark') || !document.documentElement.classList.contains('light');
                if (!document.documentElement.classList.contains('dark') && !document.documentElement.classList.contains('light')) {
                    document.documentElement.classList.add('dark');
                }

                // Start fading slideshow every 6 seconds
                this.startSlideShow();
            },

            beforeUnmount() {
                this.stopSlideShow();
            },

            methods: {
                startSlideShow() {
                    this.stopSlideShow();
                    this.slideInterval = setInterval(() => {
                        this.currentImageIndex = (this.currentImageIndex + 1) % this.venueImages.length;
                    }, 6000);
                },

                stopSlideShow() {
                    if (this.slideInterval) {
                        clearInterval(this.slideInterval);
                        this.slideInterval = null;
                    }
                },

                selectImage(index) {
                    this.currentImageIndex = index;
                    this.startSlideShow();
                },

                fillAdminCredentials() {
                    this.email = 'admin@admin.com';
                    this.password = 'password';
                },

                toggleTheme() {
                    this.isDark = !this.isDark;
                    if (this.isDark) {
                        document.documentElement.classList.remove('light');
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        document.documentElement.classList.add('light');
                    }
                },

                handleLogin() {
                    this.isLoading = true;

                    this.$axios.post("{{ route('admin.verify_login') }}", {
                            email: this.email,
                            password: this.password,
                            remember_me: this.remember_me,
                        })
                        .then((response) => {
                            window.location.href = "{{ route('admin.dashboard') }}";
                        })
                        .catch((error) => {
                            this.isLoading = false;

                            if (error.response?.status === 422) {
                                const errorMsg = error?.response?.data?.errors?.[0] || error?.response?.data?.message || 'Login failed. Please check your credentials.';
                                this.$emitter.emit('add-flash', {
                                    type: 'error',
                                    message: errorMsg
                                });
                                return;
                            }

                            const fallbackMsg = error?.response?.data?.message || 'Login failed. Please try again.';
                            this.$emitter.emit('add-flash', {
                                type: 'error',
                                message: fallbackMsg
                            });
                        });
                }
            }
        });
    </script>
    @endPushOnce
</x-admin::layouts.anonymous>