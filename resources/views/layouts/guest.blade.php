<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PorciTrack</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/images/pig-logo.png') }}" type="image/png">
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

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: linear-gradient(160deg, #ffffff 0%, #f1f8f1 50%, #e8f5e9 100%);
            padding: 40px 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Decorative radial blobs matching welcome page */
        body::before {
            content: '';
            position: fixed;
            top: -120px; right: -120px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(76,175,80,0.12) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }
        body::after {
            content: '';
            position: fixed;
            bottom: -80px; left: -80px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(46,125,50,0.08) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        /* ── AUTH CONTAINER ── */
        .auth-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 40px;
            width: 100%;
            max-width: 900px;
            margin: auto;
            position: relative;
            z-index: 1;
            animation: fadeUp 0.6s ease both;
        }

        /* ── LOGO CARD ── */
        .logo-card {
            width: 320px;
            height: 320px;
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 12px 40px var(--shadow);
            position: relative;
            overflow: hidden;
        }
        .logo-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--green-main), var(--green-soft));
        }
        .logo-card img {
            width: 220px;
            height: 220px;
            object-fit: contain;
            filter: drop-shadow(0 8px 24px rgba(46,125,50,0.15));
        }

        /* ── FORM CARD ── */
        .form-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 40px;
            width: 100%;
            max-width: 380px;
            box-shadow: 0 12px 40px var(--shadow);
            position: relative;
            overflow: hidden;
        }
        .form-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--green-main), var(--green-soft));
        }
        .form-card h2 {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--text-dark);
            text-align: center;
            margin-bottom: 30px;
        }

        /* ── INPUTS ── */
        .input-group {
            position: relative;
            margin-bottom: 20px;
        }
        .input-group input {
            width: 100%;
            background: var(--green-bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: var(--text-dark);
            padding: 12px 16px;
            outline: none;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
        }
        .input-group input::placeholder {
            color: var(--text-muted);
        }
        .input-group input:focus {
            border-color: var(--green-main);
            box-shadow: 0 0 0 3px rgba(46,125,50,0.12);
            background: var(--white);
        }

        /* ── BUTTONS ── */
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--green-main), var(--green-dark));
            color: var(--white);
            border: none;
            border-radius: 30px;
            font-weight: 700;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            margin-top: 10px;
            box-shadow: 0 6px 24px rgba(46,125,50,0.3);
            transition: all 0.25s ease;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(46,125,50,0.4);
        }
        .btn-login:active {
            transform: translateY(0);
        }

        .btn-register {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--green-main), var(--green-dark));
            color: var(--white);
            border: none;
            border-radius: 30px;
            font-weight: 700;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            box-shadow: 0 6px 24px rgba(46,125,50,0.3);
            transition: all 0.25s ease;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(46,125,50,0.4);
        }

        /* ── LINKS ── */
        .form-links {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .form-links a {
            color: var(--text-muted);
            font-size: 0.85rem;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        .form-links a:hover {
            color: var(--green-main);
        }

        /* ── DIVIDER ── */
        .login-divider {
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 22px 0 16px;
        }
        .login-divider div {
            height: 1px;
            flex: 1;
            background: var(--border);
        }
        .login-divider span {
            font-size: 0.7rem;
            color: var(--text-muted);
            font-weight: 600;
            letter-spacing: 0.05em;
        }

        /* ── GOOGLE BUTTON ── */
        .google-login-btn {
            width: 100%;
            padding: 12px;
            background: var(--white);
            color: var(--text-mid);
            border: 1px solid var(--border);
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.85rem;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
        }
        .google-login-btn:hover {
            background: var(--green-accent);
            border-color: var(--green-soft);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px var(--shadow);
        }

        /* ── ANIMATIONS ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .auth-container { flex-direction: column; gap: 24px; }
            .logo-card { width: 200px; height: 200px; border-radius: 24px; }
            .logo-card img { width: 140px; height: 140px; }
            .form-card { max-width: 100%; padding: 30px; }
        }
    </style>
</head>
<body>
    {{ $slot }}

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-app.js";
        import { getAuth, signInWithEmailAndPassword, signInWithPopup, GoogleAuthProvider } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-auth.js";

        const firebaseConfig = {
            apiKey: "AIzaSyAhpOY61Jtap1SM_SSSns6S8LjBxZiR76k",
            authDomain: "porcitrack-3aaa2.firebaseapp.com",
            projectId: "porcitrack-3aaa2",
            storageBucket: "porcitrack-3aaa2.firebasestorage.app",
            messagingSenderId: "901038528980",
            appId: "1:901038528980:web:4364e93fbc22f3a01f9e41"
        };

        const app = initializeApp(firebaseConfig);
        const auth = getAuth(app);
        const provider = new GoogleAuthProvider();

        window.addEventListener('DOMContentLoaded', () => {
            const loginForm = document.getElementById('login-form');
            const submitBtn = document.getElementById('submit-btn');

            if (loginForm) {
                // ── Email/Password Login ──
                loginForm.addEventListener('submit', async (e) => {
                    e.preventDefault();

                    submitBtn.disabled = true;
                    submitBtn.innerText = "Authenticating...";

                    const email = document.getElementById('email').value;
                    const password = document.getElementById('password').value;

                    // Try Firebase first (optional), then always submit to Laravel
                    try {
                        await signInWithEmailAndPassword(auth, email, password);
                    } catch (firebaseError) {
                        // Firebase failed — that's okay, Laravel will handle it
                        console.log("Firebase auth skipped:", firebaseError.message);
                    }

                    // Always submit the form natively to Laravel (includes @csrf token)
                    loginForm.submit();
                });

                // ── Google Sign-In on Login Page ──
                const googleBtn = document.getElementById('google-login-btn');
                if (googleBtn) {
                    googleBtn.addEventListener('click', async () => {
                        googleBtn.disabled = true;
                        googleBtn.innerText = "Signing in...";

                        try {
                            const result = await signInWithPopup(auth, provider);
                            const user = result.user;

                            // Fill hidden form fields and submit to Laravel
                            document.getElementById('email').value = user.email;
                            document.getElementById('password').value = 'GoogleUser_' + user.uid;

                            // Add a hidden field to tell Laravel this is a Google login
                            let googleFlag = document.createElement('input');
                            googleFlag.type = 'hidden';
                            googleFlag.name = 'google_login';
                            googleFlag.value = '1';
                            loginForm.appendChild(googleFlag);

                            let nameField = document.createElement('input');
                            nameField.type = 'hidden';
                            nameField.name = 'name';
                            nameField.value = user.displayName || 'Google User';
                            loginForm.appendChild(nameField);

                            loginForm.submit();
                        } catch (error) {
                            alert("Google Sign-In Failed: " + error.message);
                            googleBtn.disabled = false;
                            googleBtn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24"><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/></svg> Sign in with Google';
                        }
                    });
                }
            }
        });
    </script>
</body>
</html>