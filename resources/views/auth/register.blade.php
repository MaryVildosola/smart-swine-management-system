<x-guest-layout>
<style>
    /* Unified Pro Layout */
    .auth-container { 
        max-width: 850px !important; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        gap: 50px !important; 
    }

    .form-card.register-card { 
        max-width: 400px !important; 
        padding: 35px !important; 
        background: rgba(255, 255, 255, 0.03) !important;
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 24px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
    
    .form-card.register-card h2 { 
        font-size: 1.6rem; 
        font-weight: 800;
        letter-spacing: -0.025em;
        margin-bottom: 4px; 
        background: linear-gradient(to right, #fff, rgba(255,255,255,0.5));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .register-subtitle { 
        font-size: 0.8rem; 
        color: rgba(255,255,255,0.35); 
        margin-bottom: 24px; 
    }

    /* Refined Input Groups */
    .input-group { position: relative; margin-bottom: 20px; }
    
    .field-label { 
        font-size: 0.65rem; 
        color: rgba(255,255,255,0.4); 
        margin-bottom: 6px; 
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .input-group input, .input-group select { 
        width: 100%;
        background: transparent !important;
        border: none !important;
        border-bottom: 1px solid rgba(255,255,255,0.1) !important;
        color: #fff !important;
        font-size: 0.9rem !important; 
        padding: 8px 0 !important; 
        transition: all 0.3s ease;
    }

    .input-group input:focus, .input-group select:focus { 
        border-bottom-color: #66bb6a !important;
        outline: none;
    }

    /* Split Row Logic */
    .field-row { 
        display: grid; 
        grid-template-columns: 1fr 1fr; 
        gap: 20px; 
        margin-bottom: 10px;
    }

    /* Photo Upload Button - Pro Style */
    .file-upload-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 38px;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 12px;
        color: rgba(255,255,255,0.6);
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .file-upload-btn:hover {
        background: rgba(102, 187, 106, 0.1);
        border-color: #66bb6a;
        color: #fff;
    }

    /* Social Section */
    .compact-divider { 
        display: flex; 
        align-items: center; 
        gap: 15px; 
        margin: 25px 0 15px; 
    }

    .compact-divider div { height: 1px; flex: 1; background: linear-gradient(to right, transparent, rgba(255,255,255,0.1), transparent); }
    
    .google-icon-btn {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 14px;
        margin: 0 auto;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .google-icon-btn:hover { 
        background: rgba(255,255,255,0.07); 
        transform: scale(1.1) rotate(5deg);
        border-color: rgba(255,255,255,0.2);
    }
</style>

<div class="auth-container">
    <div class="logo-card">
        <img src="{{ asset('assets/images/pig-logo.png') }}" alt="PorciTrack Logo" style="filter: drop-shadow(0 0 20px rgba(102,187,106,0.2));">
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
                <div class="strength-bar-wrap" style="height: 2px; margin-top: 8px;"><div class="strength-bar" id="strengthBar"></div></div>
            </div>

            <div class="input-group">
                <label class="field-label">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required oninput="checkMatch()" placeholder="••••••••">
                <div id="matchLabel" style="font-size: 10px; margin-top: 5px;"></div>
            </div>

            <button type="submit" class="btn-register" style="margin-top: 10px;">Sign Up</button>

            <div class="compact-divider">
                <div></div><span style="font-size: 9px; color: rgba(255,255,255,0.2);">OR REGISTER WITH</span><div></div>
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
                <a href="{{ route('login') }}" style="color: rgba(255,255,255,0.4); font-size: 0.8rem; text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='#66bb6a'" onmouseout="this.style.color='rgba(255,255,255,0.4)'">
                    Already have an account? <span style="color: #66bb6a; font-weight: 600;">Sign in</span>
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