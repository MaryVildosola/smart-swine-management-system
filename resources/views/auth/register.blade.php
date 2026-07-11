<x-guest-layout>
<style>
    /* Unified Pro Layout */
    .auth-container { 
        max-width: 850px; 
        gap: 50px; 
    }

    .form-card.register-card { 
        max-width: 420px !important; 
        padding: 35px !important; 
        background: var(--card-bg) !important;
        border: 1px solid var(--border);
        border-radius: 24px;
        box-shadow: 0 12px 40px var(--shadow);
    }
    .form-card.register-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--green-main), var(--green-soft));
    }
    
    .form-card.register-card h2 { 
        font-size: 1.6rem; 
        font-weight: 800;
        letter-spacing: -0.025em;
        margin-bottom: 4px; 
        color: var(--text-dark);
        background: none;
        -webkit-background-clip: unset;
        -webkit-text-fill-color: unset;
    }

    .register-subtitle { 
        font-size: 0.8rem; 
        color: var(--text-muted); 
        margin-bottom: 24px;
        text-align: center;
    }

    /* Refined Input Groups */
    .input-group { position: relative; margin-bottom: 20px; }
    
    .field-label { 
        font-size: 0.7rem; 
        color: var(--text-muted); 
        margin-bottom: 6px; 
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: block;
    }

    .input-group input, .input-group select { 
        width: 100%;
        background: var(--green-bg) !important;
        border: 1px solid var(--border) !important;
        border-radius: 12px !important;
        color: var(--text-dark) !important;
        font-size: 0.9rem !important; 
        padding: 10px 14px !important; 
        font-family: 'Inter', sans-serif;
        transition: all 0.3s ease;
        outline: none;
    }

    .input-group input::placeholder {
        color: var(--text-muted) !important;
    }

    .input-group input:focus, .input-group select:focus { 
        border-color: var(--green-main) !important;
        box-shadow: 0 0 0 3px rgba(46,125,50,0.12);
        background: var(--white) !important;
        outline: none;
    }

    .input-group select option {
        background: var(--white);
        color: var(--text-dark);
    }

    /* Split Row Logic */
    .field-row { 
        display: grid; 
        grid-template-columns: 1fr 1fr; 
        gap: 20px; 
        margin-bottom: 10px;
    }

    /* Photo Upload Button */
    .file-upload-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 40px;
        background: var(--green-bg);
        border: 1px solid var(--border);
        border-radius: 12px;
        color: var(--text-muted);
        font-size: 0.78rem;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        transition: all 0.3s;
    }

    .file-upload-btn:hover {
        background: var(--green-accent);
        border-color: var(--green-soft);
        color: var(--green-main);
    }

    /* Strength bar */
    .strength-bar-wrap {
        height: 3px;
        background: var(--border);
        border-radius: 3px;
        overflow: hidden;
    }
    .strength-bar {
        height: 100%;
        border-radius: 3px;
        transition: width 0.3s, background 0.3s;
    }

    /* Social Section */
    .compact-divider { 
        display: flex; 
        align-items: center; 
        gap: 15px; 
        margin: 25px 0 15px; 
    }

    .compact-divider div { 
        height: 1px; 
        flex: 1; 
        background: var(--border); 
    }
    
    .google-icon-btn {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 14px;
        margin: 0 auto;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .google-icon-btn:hover { 
        background: var(--green-accent); 
        transform: scale(1.1) rotate(5deg);
        border-color: var(--green-soft);
        box-shadow: 0 4px 12px var(--shadow);
    }

    /* Sign-in link */
    .signin-link {
        color: var(--text-muted);
        font-size: 0.85rem;
        text-decoration: none;
        transition: color 0.3s;
    }
    .signin-link:hover {
        color: var(--green-main);
    }
    .signin-link span {
        color: var(--green-main);
        font-weight: 600;
    }
</style>

<div class="auth-container">
    <div class="logo-card">
        <img src="{{ asset('assets/images/pig-logo.png') }}" alt="SwineForge Logo">
    </div>

    <div class="form-card register-card">
        <h2>Create Account</h2>
        <p class="register-subtitle">Farm Management System</p>

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="registerForm">
            @csrf

            <div class="input-group">
                <label class="field-label">Full Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required placeholder="Juan dela Cruz">
            </div>

            <div class="input-group">
                <label class="field-label">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="you@example.com">
            </div>

            <div class="field-row">
                <div class="input-group">
                    <label class="field-label">Role</label>
                    <select id="role" name="role" required>
                        <option value="" disabled selected>Choose...</option>
                        <option value="farm_worker">Worker</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="input-group">
                    <label class="field-label">Profile Photo</label>
                    <input id="photo" type="file" name="photo" accept="image/*" class="hidden" onchange="handlePhotoChange(this)">
                    <button type="button" class="file-upload-btn" onclick="document.getElementById('photo').click()">
                        <span id="photo-label">Select File</span>
                    </button>
                </div>
            </div>

            <div class="input-group">
                <label class="field-label">Password</label>
                <input id="password" type="password" name="password" required oninput="checkStrength(this.value)" placeholder="••••••••">
                <div class="strength-bar-wrap" style="margin-top: 8px;"><div class="strength-bar" id="strengthBar"></div></div>
            </div>

            <div class="input-group">
                <label class="field-label">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required oninput="checkMatch()" placeholder="••••••••">
                <div id="matchLabel" style="font-size: 11px; margin-top: 5px;"></div>
            </div>

            <button type="submit" class="btn-register" style="margin-top: 10px;">Sign Up</button>

            <div class="compact-divider">
                <div></div><span style="font-size: 0.7rem; color: var(--text-muted); font-weight: 600; letter-spacing: 0.05em;">OR REGISTER WITH</span><div></div>
            </div>

            <button type="button" onclick="window.registerWithGoogle()" class="google-icon-btn">
                <svg width="20" height="20" viewBox="0 0 24 24">
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                </svg>
            </button>

            <div style="text-align: center; margin-top: 25px;">
                <a href="{{ route('login') }}" class="signin-link">
                    Already have an account? <span>Sign in</span>
                </a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-app.js";
    import { getAuth, createUserWithEmailAndPassword, signInWithPopup, GoogleAuthProvider } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-auth.js";

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
    const registerForm = document.getElementById('registerForm');

    // ── Google Registration ──
    window.registerWithGoogle = async () => {
        const role = document.getElementById('role').value;
        if (!role) { Swal.fire('Role Required', 'Please select a role before registering.', 'info'); return; }
        try {
            const result = await signInWithPopup(auth, provider);
            document.getElementById('name').value = result.user.displayName;
            document.getElementById('email').value = result.user.email;
            const dummy = "GoogleUser_" + result.user.uid;
            document.getElementById('password').value = dummy;
            document.getElementById('password_confirmation').value = dummy;
            registerForm.submit();
        } catch (e) { Swal.fire('Error', e.message, 'error'); }
    };

    // ── Helper Functions ──
    window.handlePhotoChange = (input) => {
        const label = document.getElementById('photo-label');
        if (input.files && input.files[0]) {
            label.innerText = input.files[0].name;
            label.style.color = 'var(--green-main)';
        } else {
            label.innerText = 'Select File';
            label.style.color = 'var(--text-muted)';
        }
    };

    window.checkStrength = (p) => {
        const bar = document.getElementById('strengthBar');
        let s = 0;
        if (p.length > 5) s++;
        if (p.length > 8) s++;
        if (/[A-Z]/.test(p)) s++;
        if (/[0-9]/.test(p)) s++;
        if (/[^A-Za-z0-9]/.test(p)) s++;

        const colors = ['#e57373', '#ffb74d', '#fff176', '#aed581', '#81c784'];
        const widths = ['20%', '40%', '60%', '80%', '100%'];
        
        if (p.length === 0) {
            bar.style.width = '0';
        } else {
            bar.style.width = widths[s-1] || '10%';
            bar.style.background = colors[s-1] || colors[0];
        }
    };

    window.checkMatch = () => {
        const p1 = document.getElementById('password').value;
        const p2 = document.getElementById('password_confirmation').value;
        const label = document.getElementById('matchLabel');
        if (!p2) { label.innerText = ''; return; }
        if (p1 === p2) {
            label.innerText = 'Passwords match';
            label.style.color = 'var(--green-main)';
        } else {
            label.innerText = 'Passwords do not match';
            label.style.color = '#e57373';
        }
    };

    // ── Email/Password Registration ──
    registerForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        Swal.fire({ title: 'Processing...', didOpen: () => Swal.showLoading(), allowOutsideClick: false });

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        // Try Firebase first (optional), then always submit to Laravel
        try {
            await createUserWithEmailAndPassword(auth, email, password);
        } catch (firebaseError) {
            // Firebase failed — that's okay, Laravel will handle local registration
            console.log("Firebase registration skipped:", firebaseError.message);
        }

        Swal.close();
        // Always submit the form natively to Laravel (includes @csrf token)
        registerForm.submit();
    });
</script>
</x-guest-layout>