<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PorciTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(ellipse at 30% 40%, #4caf50 0%, #2e7d32 35%, #1a3a1a 65%, #0d1f0d 100%);
            padding: 20px;
        }
        .auth-container { display: flex; align-items: center; justify-content: center; gap: 40px; width: 100%; max-width: 900px; }
        .logo-card { width: 320px; height: 320px; background: linear-gradient(145deg, #1a2e1a 0%, #0d1a0d 100%); border-radius: 40px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 25px 50px rgba(0,0,0,0.5); }
        .logo-card img { width: 240px; height: 240px; object-fit: contain; }
        .form-card { background: rgba(255, 255, 255, 0.08); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.15); border-radius: 24px; padding: 40px; width: 100%; max-width: 360px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
        .form-card h2 { font-size: 2rem; color: #fff; text-align: center; margin-bottom: 30px; }
        .input-group { position: relative; margin-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.3); }
        .input-group input { width: 100%; background: transparent; border: none; color: #fff; padding: 10px 5px; outline: none; font-size: 1rem; }
        .btn-login { width: 100%; padding: 14px; background: #2e7d32; color: #fff; border: none; border-radius: 12px; font-weight: 700; cursor: pointer; margin-top: 10px; }
        .form-links { display: flex; justify-content: space-between; margin-top: 20px; }
        .form-links a { color: rgba(255,255,255,0.6); font-size: 0.8rem; text-decoration: none; }
        .login-divider { display: flex; align-items: center; gap: 15px; margin: 20px 0 15px; }
        .login-divider div { height: 1px; flex: 1; background: linear-gradient(to right, transparent, rgba(255,255,255,0.1), transparent); }
        .login-divider span { font-size: 9px; color: rgba(255,255,255,0.2); }
        .google-login-btn {
            width: 100%; padding: 12px; background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.8);
            border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; font-weight: 600; font-size: 0.85rem;
            cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px;
            transition: all 0.3s ease;
        }
        .google-login-btn:hover { background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2); }
        @media (max-width: 768px) { .auth-container { flex-direction: column; } }
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