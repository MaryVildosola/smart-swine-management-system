<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SwineForge – Smart pig farm management system for tracking pigs, feed, health, and productivity.">
    <title>SwineForge – Smart Farm Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --green-main:   #2e7d32;
            --green-dark:   #1b5e20;
            --green-light:  #4caf50;
            --green-soft:   #66bb6a;
            --green-pale:   #c8e6c9;
            --green-bg:     #f1f8f1;
            --green-accent: #e8f5e9;
            --text-dark:    #1a2e1a;
            --text-mid:     #3d5c3d;
            --text-muted:   #6b8f6b;
            --white:        #ffffff;
            --border:       #d4e8d4;
            --card-bg:      #ffffff;
            --shadow:       rgba(46,125,50,0.10);
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--white);
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* ── NAVBAR ── */
        nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 60px;
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--border);
            box-shadow: 0 2px 16px rgba(46,125,50,0.07);
        }
        .nav-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 800;
            font-size: 1.2rem;
            color: var(--green-main);
            text-decoration: none;
        }
        .nav-logo img { width: 36px; height: 36px; object-fit: contain; }
        .nav-links { display: flex; gap: 32px; align-items: center; }
        .nav-links a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.2s;
        }
        .nav-links a:hover { color: var(--green-main); }
        .nav-cta {
            background: linear-gradient(135deg, var(--green-main), var(--green-dark));
            color: var(--white) !important;
            padding: 9px 24px;
            border-radius: 30px;
            font-weight: 600 !important;
            box-shadow: 0 4px 14px var(--shadow);
            transition: transform 0.2s, box-shadow 0.2s !important;
        }
        .nav-cta:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(46,125,50,0.25) !important; }

        /* ── HERO ── */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 120px 24px 80px;
            position: relative;
            overflow: hidden;
            background: linear-gradient(160deg, #ffffff 0%, #f1f8f1 50%, #e8f5e9 100%);
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -120px; right: -120px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(76,175,80,0.12) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero::after {
            content: '';
            position: absolute;
            bottom: -80px; left: -80px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(46,125,50,0.08) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--green-accent);
            border: 1px solid var(--border);
            border-radius: 30px;
            padding: 6px 18px;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--green-main);
            margin-bottom: 28px;
            animation: fadeUp 0.6s ease both;
        }
        .hero-badge-dot { width: 8px; height: 8px; background: var(--green-light); border-radius: 50%; display: inline-block; animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.5;transform:scale(0.85)} }

        .hero h1 {
            font-size: clamp(2.4rem, 6vw, 4rem);
            font-weight: 800;
            line-height: 1.15;
            color: var(--text-dark);
            margin-bottom: 24px;
            animation: fadeUp 0.7s 0.1s ease both;
        }
        .hero h1 em {
            font-style: normal;
            background: linear-gradient(90deg, var(--green-main), var(--green-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero p {
            font-size: 1.1rem;
            color: var(--text-muted);
            max-width: 560px;
            margin: 0 auto 40px;
            line-height: 1.7;
            animation: fadeUp 0.7s 0.2s ease both;
        }
        .hero-actions {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
            animation: fadeUp 0.7s 0.3s ease both;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--green-main), var(--green-dark));
            color: var(--white);
            padding: 14px 34px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            box-shadow: 0 6px 24px rgba(46,125,50,0.3);
            transition: all 0.25s;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(46,125,50,0.4); }
        .btn-secondary {
            background: var(--white);
            color: var(--green-main);
            padding: 14px 34px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            border: 2px solid var(--border);
            transition: all 0.25s;
        }
        .btn-secondary:hover { border-color: var(--green-soft); background: var(--green-accent); transform: translateY(-2px); }

        .hero-stats {
            display: flex;
            gap: 40px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 70px;
            animation: fadeUp 0.7s 0.4s ease both;
        }
        .stat { text-align: center; }
        .stat-num { font-size: 2rem; font-weight: 800; color: var(--green-main); }
        .stat-label { font-size: 0.8rem; color: var(--text-muted); margin-top: 4px; }

        /* ── SECTIONS ── */
        .section {
            padding: 100px 24px;
            max-width: 1100px;
            margin: 0 auto;
        }
        .section-wrap { background: var(--white); }
        .section-wrap-alt { background: var(--green-bg); }

        .section-tag {
            display: inline-block;
            background: var(--green-accent);
            border: 1px solid var(--border);
            color: var(--green-main);
            padding: 4px 16px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-bottom: 16px;
        }
        .section-title {
            font-size: clamp(1.8rem, 4vw, 2.6rem);
            font-weight: 800;
            line-height: 1.2;
            color: var(--text-dark);
            margin-bottom: 16px;
        }
        .section-sub {
            font-size: 1rem;
            color: var(--text-muted);
            max-width: 520px;
            line-height: 1.7;
        }

        /* ── FEATURES ── */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
            margin-top: 60px;
        }
        .feature-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 32px;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 12px var(--shadow);
        }
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--green-main), var(--green-soft));
            opacity: 0;
            transition: opacity 0.3s;
        }
        .feature-card:hover { transform: translateY(-6px); border-color: var(--green-soft); box-shadow: 0 12px 32px rgba(46,125,50,0.15); }
        .feature-card:hover::before { opacity: 1; }
        .feature-icon {
            width: 52px; height: 52px;
            background: var(--green-accent);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        .feature-icon svg { width: 26px; height: 26px; fill: var(--green-main); }
        .feature-card h3 { font-size: 1.1rem; font-weight: 700; color: var(--text-dark); margin-bottom: 10px; }
        .feature-card p { font-size: 0.9rem; color: var(--text-muted); line-height: 1.65; }

        /* ── HOW IT WORKS ── */
        .steps { display: flex; flex-direction: column; gap: 0; margin-top: 60px; position: relative; }
        .steps::before {
            content: '';
            position: absolute;
            left: 27px; top: 0; bottom: 0;
            width: 2px;
            background: linear-gradient(180deg, transparent, var(--green-soft), transparent);
        }
        .step { display: flex; gap: 28px; align-items: flex-start; padding-bottom: 48px; }
        .step-num {
            width: 54px; height: 54px; flex-shrink: 0;
            background: linear-gradient(135deg, var(--green-main), var(--green-dark));
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 1.1rem; color: var(--white);
            box-shadow: 0 0 0 6px var(--green-pale);
            position: relative; z-index: 1;
        }
        .step-content h3 { font-size: 1.1rem; font-weight: 700; color: var(--text-dark); margin-bottom: 8px; margin-top: 12px; }
        .step-content p { font-size: 0.9rem; color: var(--text-muted); line-height: 1.65; max-width: 520px; }

        /* ── ROLES ── */
        .roles-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 28px; margin-top: 60px; }
        .role-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 40px 36px;
            transition: all 0.3s;
            box-shadow: 0 2px 12px var(--shadow);
        }
        .role-card:hover { border-color: var(--green-soft); transform: translateY(-4px); box-shadow: 0 12px 32px rgba(46,125,50,0.15); }
        .role-badge {
            display: inline-block;
            padding: 5px 16px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .role-admin .role-badge { background: var(--green-accent); color: var(--green-main); border: 1px solid var(--border); }
        .role-worker .role-badge { background: #e3f2fd; color: #1565c0; border: 1px solid #bbdefb; }
        .role-card h3 { font-size: 1.25rem; font-weight: 700; color: var(--text-dark); margin-bottom: 14px; }
        .role-list { list-style: none; display: flex; flex-direction: column; gap: 10px; }
        .role-list li { display: flex; align-items: flex-start; gap: 10px; font-size: 0.9rem; color: var(--text-muted); }
        .role-list li::before { content: '✓'; color: var(--green-main); font-weight: 800; flex-shrink: 0; margin-top: 1px; }

        /* ── CTA ── */
        .cta-section {
            text-align: center;
            padding: 100px 24px;
            background: linear-gradient(135deg, var(--green-accent), var(--green-pale));
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }
        .cta-section h2 { font-size: clamp(1.8rem, 4vw, 2.8rem); font-weight: 800; color: var(--text-dark); margin-bottom: 16px; }
        .cta-section p { color: var(--text-muted); margin-bottom: 40px; font-size: 1rem; }

        /* ── FOOTER ── */
        footer {
            background: var(--text-dark);
            padding: 40px 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
        }
        .footer-logo { display: flex; align-items: center; gap: 10px; font-weight: 700; color: var(--green-pale); font-size: 1rem; }
        .footer-logo img { width: 28px; height: 28px; object-fit: contain; }
        footer p { font-size: 0.82rem; color: rgba(255,255,255,0.4); }

        /* ── ANIMATIONS ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .reveal { opacity: 0; transform: translateY(28px); transition: opacity 0.6s ease, transform 0.6s ease; }
        .reveal.visible { opacity: 1; transform: translateY(0); }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            nav { padding: 16px 24px; }
            .nav-links { gap: 16px; }
            .nav-links a:not(.nav-cta) { display: none; }
            .roles-grid { grid-template-columns: 1fr; }
            footer { flex-direction: column; text-align: center; padding: 32px 24px; }
            .steps::before { display: none; }
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav>
        <div class="nav-logo">
            <img src="{{ asset('assets/images/pig-logo.png') }}" alt="SwineForge">
            SwineForge
        </div>
        <div class="nav-links">
            <a href="#features">Features</a>
            <a href="#how">How It Works</a>
            <a href="#roles">Roles</a>
            <a href="{{ route('login') }}" class="nav-cta">Login</a>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div style="position:relative;z-index:1;">
            <div class="hero-badge">
                <span class="hero-badge-dot"></span> Smart Pig Farm Management
            </div>
            <h1>Manage Your Farm<br>with <em>Precision & Ease</em></h1>
            <p>SwineForge gives you complete visibility over your pig farm — from pens and health tracking to feed formulas, inventory, and worker tasks. All in one system.</p>
            <div class="hero-actions">
                <a href="{{ route('login') }}" class="btn-primary">Get Started</a>
                <a href="#features" class="btn-secondary">Learn More</a>
            </div>
            <div class="hero-stats">
                <div class="stat">
                    <div class="stat-num">360°</div>
                    <div class="stat-label">Farm Visibility</div>
                </div>
                <div class="stat">
                    <div class="stat-num">Real-time</div>
                    <div class="stat-label">Health Alerts</div>
                </div>
                <div class="stat">
                    <div class="stat-num">2 Roles</div>
                    <div class="stat-label">Admin & Worker</div>
                </div>
                <div class="stat">
                    <div class="stat-num">QR</div>
                    <div class="stat-label">Pig Tracking</div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <div class="section-wrap">
        <section id="features">
            <div class="section">
                <div class="reveal">
                    <div class="section-tag">Features</div>
                    <h2 class="section-title">Everything You Need<br>to Run a Smart Farm</h2>
                    <p class="section-sub">From daily operations to in-depth analytics, SwineForge covers every aspect of modern pig farm management.</p>
                </div>
                <div class="features-grid">
                    <div class="feature-card reveal">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z"/></svg>
                        </div>
                        <h3>Live Dashboard</h3>
                        <p>Get a real-time overview of your entire farm — active pigs, sick animals, pending tasks, and feed stock at a glance.</p>
                    </div>
                    <div class="feature-card reveal">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24"><path d="M12 2a5 5 0 1 1 0 10A5 5 0 0 1 12 2zm0 12c5.33 0 8 2.67 8 4v2H4v-2c0-1.33 2.67-4 8-4z"/></svg>
                        </div>
                        <h3>Pen & Pig Tracking</h3>
                        <p>Manage individual pens and pigs, log health activities, monitor weight, and scan QR labels for instant pig records.</p>
                    </div>
                    <div class="feature-card reveal">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24"><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-7v2h2v-2h-2zm0-8v6h2V7h-2z"/></svg>
                        </div>
                        <h3>Health Alerts</h3>
                        <p>Critical health events trigger instant alerts for admins. Acknowledge and resolve issues without missing a beat.</p>
                    </div>
                    <div class="feature-card reveal">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/></svg>
                        </div>
                        <h3>Feed & Inventory</h3>
                        <p>Track master stocks, deliveries, and consumption. Build custom feed formulas with full ingredient breakdowns.</p>
                    </div>
                    <div class="feature-card reveal">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/></svg>
                        </div>
                        <h3>Task Management</h3>
                        <p>Admins assign daily tasks to farm workers. Workers track, update, and complete tasks directly from their dashboard.</p>
                    </div>
                    <div class="feature-card reveal">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19a2 2 0 0 0 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
                        </div>
                        <h3>Reports & Analytics</h3>
                        <p>Workers submit weekly reports; admins review analytics on productivity, pig growth, and farm performance over time.</p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- HOW IT WORKS -->
    <div class="section-wrap-alt">
        <section id="how" class="section">
            <div class="reveal">
                <div class="section-tag">How It Works</div>
                <h2 class="section-title">Up and Running in Minutes</h2>
                <p class="section-sub">SwineForge is designed to be simple. No technical expertise required to get your farm connected.</p>
            </div>
            <div class="steps">
                <div class="step reveal">
                    <div class="step-num">1</div>
                    <div class="step-content">
                        <h3>Create Your Account</h3>
                        <p>Register as an administrator. Set up your farm profile and invite farm workers to join with role-based access.</p>
                    </div>
                </div>
                <div class="step reveal">
                    <div class="step-num">2</div>
                    <div class="step-content">
                        <h3>Set Up Pens & Add Pigs</h3>
                        <p>Create your pen layout, add your pigs with individual records, and print QR labels for instant field scanning.</p>
                    </div>
                </div>
                <div class="step reveal">
                    <div class="step-num">3</div>
                    <div class="step-content">
                        <h3>Assign Tasks & Monitor</h3>
                        <p>Assign daily tasks to your team, manage feed inventory, and monitor pig health — all from one dashboard.</p>
                    </div>
                </div>
                <div class="step reveal">
                    <div class="step-num">4</div>
                    <div class="step-content">
                        <h3>Review Reports & Grow</h3>
                        <p>Analyze weekly reports and live analytics to make data-driven decisions that improve your farm's productivity.</p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- ROLES -->
    <div class="section-wrap">
        <section id="roles" class="section">
            <div class="reveal">
                <div class="section-tag">User Roles</div>
                <h2 class="section-title">Built for Your Whole Team</h2>
                <p class="section-sub">Two purpose-built dashboards — one for farm administrators and one for field workers.</p>
            </div>
            <div class="roles-grid">
                <div class="role-card role-admin reveal">
                    <div class="role-badge">🛠 Administrator</div>
                    <h3>Full Farm Control</h3>
                    <ul class="role-list">
                        <li>Manage all pens, pigs, and farm operations</li>
                        <li>Assign and monitor worker tasks</li>
                        <li>Control feed stock and mixing formulas</li>
                        <li>Receive and acknowledge critical health alerts</li>
                        <li>View live analytics and weekly farm reports</li>
                        <li>Manage user accounts and roles</li>
                    </ul>
                </div>
                <div class="role-card role-worker reveal">
                    <div class="role-badge">👷 Farm Worker</div>
                    <h3>Daily Field Operations</h3>
                    <ul class="role-list">
                        <li>View and complete assigned daily tasks</li>
                        <li>Scan pig QR codes to log health activities</li>
                        <li>Access feed formulas and mixing instructions</li>
                        <li>Monitor pig health and swine details</li>
                        <li>Submit weekly operational reports</li>
                        <li>Offline-ready for field use</li>
                    </ul>
                </div>
            </div>
        </section>
    </div>

    <!-- CTA -->
    <div class="cta-section">
        <h2>Ready to Transform Your Farm?</h2>
        <p>Join SwineForge and bring modern management to your piggery today.</p>
        <a href="{{ route('login') }}" class="btn-primary">Get Started Now</a>
    </div>

    <!-- FOOTER -->
    <footer>
        <div class="footer-logo">
            <img src="{{ asset('assets/images/pig-logo.png') }}" alt="SwineForge">
            SwineForge
        </div>
        <p>© {{ date('Y') }} SwineForge. All rights reserved.</p>
    </footer>

    <script>
        const reveals = document.querySelectorAll('.reveal');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, i) => {
                if (entry.isIntersecting) {
                    setTimeout(() => entry.target.classList.add('visible'), i * 80);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });
        reveals.forEach(el => observer.observe(el));
    </script>
</body>
</html>
