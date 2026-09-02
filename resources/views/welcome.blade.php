<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Agreement') }} — Seed Agreement Management</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:   #182b4b;
            --teal:   #187b78;
            --teal-l: #60cdb9;
            --ink:    #172b4d;
            --muted:  #71809a;
            --line:   #e7ebf2;
            --bg:     #f6f8fb;
            --white:  #ffffff;
        }

        html, body {
            min-height: 100vh;
            font-family: 'Figtree', system-ui, sans-serif;
            background: var(--bg);
            color: var(--ink);
        }

        /* ── Top bar ─────────────────────────────────────── */
        .topbar {
            position: fixed;
            inset: 0 0 auto 0;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 48px;
            height: 64px;
            background: rgba(24,43,75,.97);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid rgba(255,255,255,.07);
        }
        .topbar-brand {
            display: flex;
            align-items: center;
            gap: 11px;
            text-decoration: none;
        }
        .topbar-brand__icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: var(--teal);
            display: grid;
            place-items: center;
            color: #fff;
            font-size: 18px;
        }
        .topbar-brand strong {
            font-size: 16px;
            font-weight: 700;
            color: #e9f0fb;
            letter-spacing: .2px;
        }
        .topbar-brand small {
            display: block;
            font-size: 9px;
            color: #8fa4c5;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .topbar-nav {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn-outline-white {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            border-radius: 7px;
            border: 1px solid rgba(255,255,255,.2);
            background: transparent;
            color: #dbe6f6;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: border-color .2s, background .2s;
        }
        .btn-outline-white:hover { border-color: var(--teal-l); background: rgba(96,205,185,.1); color: #fff; }
        .btn-teal {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 20px;
            border-radius: 7px;
            background: var(--teal);
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(24,123,120,.35);
            transition: background .2s, box-shadow .2s;
        }
        .btn-teal:hover { background: #115c5a; box-shadow: 0 6px 18px rgba(24,123,120,.45); }

        /* ── Hero ────────────────────────────────────────── */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 100px 24px 60px;
            background:
                radial-gradient(ellipse 80% 60% at 50% -10%, rgba(24,123,120,.18) 0%, transparent 70%),
                var(--bg);
            text-align: center;
        }
        .hero-inner { max-width: 680px; }

        .hero-pill {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 5px 14px;
            border-radius: 20px;
            border: 1px solid rgba(24,123,120,.25);
            background: rgba(24,123,120,.08);
            color: var(--teal);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .8px;
            text-transform: uppercase;
            margin-bottom: 24px;
        }
        .hero-pill i { font-size: 13px; }

        .hero h1 {
            font-size: clamp(2.2rem, 5vw, 3.4rem);
            font-weight: 800;
            color: var(--ink);
            line-height: 1.15;
            letter-spacing: -1px;
            margin-bottom: 20px;
        }
        .hero h1 span {
            color: var(--teal);
        }
        .hero p {
            font-size: 1.05rem;
            color: var(--muted);
            line-height: 1.7;
            margin-bottom: 36px;
            max-width: 520px;
            margin-left: auto;
            margin-right: auto;
        }
        .hero-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn-hero-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 28px;
            border-radius: 8px;
            background: var(--teal);
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 8px 24px rgba(24,123,120,.3);
            transition: background .2s, transform .15s;
        }
        .btn-hero-primary:hover { background: #115c5a; transform: translateY(-1px); }
        .btn-hero-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 28px;
            border-radius: 8px;
            border: 1px solid var(--line);
            background: var(--white);
            color: var(--ink);
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: border-color .2s, box-shadow .2s;
        }
        .btn-hero-secondary:hover { border-color: #b3dedd; box-shadow: 0 4px 14px rgba(24,123,120,.1); }

        /* ── Stats strip ─────────────────────────────────── */
        .stats-strip {
            background: var(--navy);
            padding: 36px 48px;
        }
        .stats-strip-inner {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            text-align: center;
        }
        .stat-item strong {
            display: block;
            font-size: 2rem;
            font-weight: 800;
            color: var(--teal-l);
            letter-spacing: -1px;
            line-height: 1;
        }
        .stat-item span {
            display: block;
            margin-top: 5px;
            font-size: 11px;
            color: #8fa4c5;
            font-weight: 600;
            letter-spacing: .8px;
            text-transform: uppercase;
        }

        /* ── Features ────────────────────────────────────── */
        .features {
            padding: 72px 48px;
            max-width: 1100px;
            margin: 0 auto;
        }
        .section-label {
            text-align: center;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--teal);
            margin-bottom: 10px;
        }
        .section-title {
            text-align: center;
            font-size: 1.9rem;
            font-weight: 800;
            color: var(--ink);
            letter-spacing: -.5px;
            margin-bottom: 10px;
        }
        .section-sub {
            text-align: center;
            color: var(--muted);
            font-size: 0.95rem;
            max-width: 500px;
            margin: 0 auto 52px;
            line-height: 1.65;
        }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        .feature-card {
            background: var(--white);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 28px 24px;
            transition: box-shadow .2s, transform .2s;
        }
        .feature-card:hover {
            box-shadow: 0 8px 30px rgba(23,43,77,.08);
            transform: translateY(-2px);
        }
        .feature-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: grid;
            place-items: center;
            font-size: 20px;
            margin-bottom: 16px;
        }
        .feature-icon--teal  { background: #e6f4f3; color: var(--teal); }
        .feature-icon--blue  { background: #e9f3ff; color: #3987d6; }
        .feature-icon--amber { background: #fff3dd; color: #d59130; }
        .feature-icon--green { background: #e5f7f0; color: #239b82; }
        .feature-icon--rose  { background: #ffeaeb; color: #d35e69; }
        .feature-icon--navy  { background: #e8eef6; color: #36577d; }
        .feature-card h3 {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 7px;
        }
        .feature-card p {
            font-size: 0.8rem;
            color: var(--muted);
            line-height: 1.6;
        }

        /* ── CTA banner ──────────────────────────────────── */
        .cta-banner {
            background: var(--navy);
            margin: 0 48px 72px;
            border-radius: 16px;
            padding: 52px 48px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 32px;
            flex-wrap: wrap;
            background-image: radial-gradient(ellipse 60% 120% at 90% 50%, rgba(96,205,185,.12) 0%, transparent 70%);
        }
        .cta-banner h2 {
            font-size: 1.6rem;
            font-weight: 800;
            color: #e9f0fb;
            letter-spacing: -.4px;
            margin-bottom: 8px;
        }
        .cta-banner p { font-size: 0.88rem; color: #8fa4c5; }

        /* ── Footer ──────────────────────────────────────── */
        .footer {
            border-top: 1px solid var(--line);
            padding: 24px 48px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }
        .footer p { font-size: 11px; color: #9aa7b8; }
        .footer-links { display: flex; gap: 20px; }
        .footer-links a { font-size: 11px; color: #9aa7b8; text-decoration: none; }
        .footer-links a:hover { color: var(--teal); }

        /* ── Responsive ──────────────────────────────────── */
        @media (max-width: 900px) {
            .topbar { padding: 0 20px; }
            .stats-strip { padding: 28px 20px; }
            .stats-strip-inner { grid-template-columns: repeat(2, 1fr); }
            .features { padding: 48px 20px; }
            .features-grid { grid-template-columns: 1fr; }
            .cta-banner { margin: 0 20px 48px; padding: 32px 24px; flex-direction: column; text-align: center; }
            .footer { padding: 20px; justify-content: center; text-align: center; }
            .footer-links { justify-content: center; }
        }
        @media (max-width: 600px) {
            .stats-strip-inner { grid-template-columns: 1fr 1fr; }
            .topbar-brand small { display: none; }
        }
    </style>
</head>
<body>

{{-- ── Top bar ─────────────────────────────────────────── --}}
<header class="topbar">
    <a href="/" class="topbar-brand">
        <div class="topbar-brand__icon"><i class="ri-leaf-line"></i></div>
        <span>
            <strong>{{ config('app.name', 'Agreement') }}</strong>
            <small>Seed Management</small>
        </span>
    </a>
    <nav class="topbar-nav">
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-teal">
                    <i class="ri-layout-grid-line"></i> Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-outline-white">
                    <i class="ri-login-box-line"></i> Sign In
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-teal">
                        <i class="ri-user-add-line"></i> Get Started
                    </a>
                @endif
            @endauth
        @endif
    </nav>
</header>

{{-- ── Hero ──────────────────────────────────────────────── --}}
<section class="hero">
    <div class="hero-inner">
        <div class="hero-pill">
            <i class="ri-verified-badge-line"></i>
            Seed Agreement Management Platform
        </div>
        <h1>
            Manage Seed Agreements<br>
            <span>Smarter & Faster</span>
        </h1>
        <p>
            A complete platform to manage farmer agreements, crop planning, variety tracking,
            and production workflows — all in one place.
        </p>
        <div class="hero-actions">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-hero-primary">
                    <i class="ri-layout-grid-line"></i> Go to Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-hero-primary">
                    <i class="ri-login-box-line"></i> Sign In
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-hero-secondary">
                        <i class="ri-user-add-line"></i> Create Account
                    </a>
                @endif
            @endauth
        </div>
    </div>
</section>

{{-- ── Stats strip ──────────────────────────────────────── --}}
<section class="stats-strip">
    <div class="stats-strip-inner">
        <div class="stat-item">
            <strong>1000+</strong>
            <span>Farmers Registered</span>
        </div>
        <div class="stat-item">
            <strong>50+</strong>
            <span>Crop Varieties</span>
        </div>
        <div class="stat-item">
            <strong>100%</strong>
            <span>Digital Records</span>
        </div>
        <div class="stat-item">
            <strong>Real-time</strong>
            <span>Data Sync</span>
        </div>
    </div>
</section>

{{-- ── Features ─────────────────────────────────────────── --}}
<section class="features">
    <p class="section-label">What's inside</p>
    <h2 class="section-title">Everything you need</h2>
    <p class="section-sub">
        From farmer onboarding to crop agreements and production tracking —
        all workflows in a single unified system.
    </p>
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon feature-icon--teal">
                <i class="ri-user-heart-line"></i>
            </div>
            <h3>Farmer Management</h3>
            <p>Register and manage all farmer records with contact details, land holdings, bank info, and KYC documents.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon feature-icon--green">
                <i class="ri-plant-line"></i>
            </div>
            <h3>Crop & Variety Master</h3>
            <p>Maintain a comprehensive crop master with scientific classification, agronomy data, and season mapping.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon feature-icon--blue">
                <i class="ri-file-list-3-line"></i>
            </div>
            <h3>Agreement Tracking</h3>
            <p>Create, track, and manage seed production agreements with real-time status updates and deadline alerts.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon feature-icon--amber">
                <i class="ri-map-pin-line"></i>
            </div>
            <h3>Location Master</h3>
            <p>Hierarchical location data — country, state, district, tahsil, block, and village — synced from core API.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon feature-icon--rose">
                <i class="ri-refresh-line"></i>
            </div>
            <h3>Core API Sync</h3>
            <p>Seamlessly import and sync data from the central core system with a single click.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon feature-icon--navy">
                <i class="ri-bar-chart-2-line"></i>
            </div>
            <h3>Reports & Analytics</h3>
            <p>Track agreement activity, production metrics, and field performance with visual dashboards.</p>
        </div>
    </div>
</section>

{{-- ── CTA Banner ───────────────────────────────────────── --}}
<div class="cta-banner">
    <div>
        <h2>Ready to get started?</h2>
        <p>Sign in to your account and manage all your seed agreements in one place.</p>
    </div>
    @auth
        <a href="{{ url('/dashboard') }}" class="btn-teal" style="padding:13px 30px;font-size:14px">
            <i class="ri-layout-grid-line"></i> Open Dashboard
        </a>
    @else
        <a href="{{ route('login') }}" class="btn-teal" style="padding:13px 30px;font-size:14px">
            <i class="ri-login-box-line"></i> Sign In Now
        </a>
    @endauth
</div>

{{-- ── Footer ───────────────────────────────────────────── --}}
<footer class="footer">
    <p>&copy; {{ date('Y') }} {{ config('app.name', 'Agreement') }}. All rights reserved.</p>
    <nav class="footer-links">
        <a href="{{ url('/dashboard') }}">Dashboard</a>
        @if (Route::has('login'))
            <a href="{{ route('login') }}">Sign In</a>
        @endif
    </nav>
</footer>

</body>
</html>
