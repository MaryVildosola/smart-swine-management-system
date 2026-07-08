@extends('layouts.master')

@section('contents')
<style>
    :root {
        --deep-slate: #0f172a;
        --accent-green: #22c55e;
        --soft-bg: #f8fafc;
        --border-color: #e2e8f0;
    }

    .page-wrap { 
        padding: 24px 32px; 
    }

    .premium-panel { 
        background: #ffffff; 
        border: 1px solid var(--border-color); 
        border-radius: 28px; 
        padding: 32px; 
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.02);
    }

    .form-input { 
        width: 100%; 
        padding: 14px 18px; 
        border: 1.5px solid #e2e8f0; 
        border-radius: 16px; 
        font-size: 0.9rem; 
        color: var(--deep-slate); 
        font-weight: 600;
        background-color: #f8fafc;
        transition: all 0.2s;
        outline: none;
    }

    .form-input:focus { 
        border-color: var(--accent-green); 
        background-color: #fff;
        box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1);
    }

    .form-label { 
        display: block; 
        font-size: 0.7rem; 
        font-weight: 900; 
        color: #64748b; 
        margin-bottom: 10px; 
        text-transform: uppercase; 
        letter-spacing: 0.05em; 
    }

    .btn-save { 
        padding: 16px 32px; 
        background: var(--deep-slate); 
        color: #fff; 
        border: none; 
        border-radius: 20px; 
        font-weight: 800; 
        font-size: 0.95rem;
        cursor: pointer; 
        transition: all 0.2s;
        box-shadow: 0 10px 20px rgba(15, 23, 42, 0.1);
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(15, 23, 42, 0.15);
        background: #1e293b;
    }

    .btn-cancel {
        padding: 16px 32px; 
        background: #f1f5f9; 
        color: #64748b; 
        border: none; 
        border-radius: 20px; 
        font-weight: 800; 
        font-size: 0.95rem;
        cursor: pointer; 
        transition: all 0.2s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-cancel:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 900;
        color: var(--deep-slate);
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-icon {
        background: #f0fdf4;
        color: #16a34a;
        width: 32px;
        height: 32px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .photo-upload-box {
        border: 2px dashed #e2e8f0;
        border-radius: 20px;
        padding: 32px;
        text-align: center;
        background: #f8fafc;
        transition: all 0.2s;
        cursor: pointer;
    }

    .photo-upload-box:hover {
        border-color: var(--accent-green);
        background: #f0fdf4;
    }
</style>

<div class="page-wrap">
    <div style="margin-bottom: 40px; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <h1 style="font-size: 1.8rem; font-weight: 900; color: var(--deep-slate); margin: 0; letter-spacing: -0.04em;">Create New User</h1>
            <p style="font-size: 0.9rem; color: #64748b; font-weight: 500; margin-top: 4px;">Provision a new account for a worker or administrator.</p>
        </div>
        <a href="{{ route('users.index') }}" class="btn-cancel" style="padding: 12px 24px;"><i class='bx bx-arrow-back'></i> Back</a>
    </div>

    @if ($errors->any())
        <div style="background: #fef2f2; border: 1.5px solid #fca5a5; color: #b91c1c; padding: 16px 24px; border-radius: 20px; margin-bottom: 32px; font-size: 0.9rem; font-weight: 600;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                <i class='bx bxs-error-circle' style="font-size: 1.2rem;"></i>
                <strong style="font-weight: 800;">Whoops! Something went wrong.</strong>
            </div>
            <ul style="margin: 0; padding-left: 24px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="create-user-form" method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="premium-panel" style="margin-bottom: 24px;">
            <div class="section-title">
                <div class="section-icon"><i class='bx bx-user'></i></div>
                Personal Information
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                <div>
                    <label for="name" class="form-label">Full Name <span style="color: #ef4444;">*</span></label>
                    <input type="text" class="form-input" id="name" name="name" value="{{ old('name') }}" placeholder="Enter full name" required>
                </div>
                <div>
                    <label for="email" class="form-label">Email Address <span style="color: #ef4444;">*</span></label>
                    <input type="email" class="form-input" id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                <div>
                    <label for="birthdate" class="form-label">Birth Date</label>
                    <input type="date" class="form-input" id="birthdate" name="birthdate" value="{{ old('birthdate') }}">
                </div>
                <div>
                    <label for="role" class="form-label">Account Role <span style="color: #ef4444;">*</span></label>
                    <select class="form-input" id="role" name="role" required>
                        <option value="farm_worker" {{ old('role') == 'farm_worker' ? 'selected' : '' }}>Farm Worker</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                </div>
                <div>
                    <label for="region" class="form-label">Farm Region</label>
                    <input type="text" class="form-input" id="region" name="region" value="{{ old('region') }}" placeholder="e.g. Batangas, Philippines">
                    <div style="font-size: 0.7rem; color: #94a3b8; margin-top: 8px; font-weight: 600;">Used for localized AI disease intelligence</div>
                </div>
            </div>
        </div>

        <div class="premium-panel" style="margin-bottom: 24px;">
            <div class="section-title">
                <div class="section-icon" style="background: #eff6ff; color: #3b82f6;"><i class='bx bx-lock-alt'></i></div>
                Account Security
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                <div style="position: relative;">
                    <label for="password" class="form-label">Password <span style="color: #ef4444;">*</span></label>
                    <input type="password" class="form-input" id="password" name="password" placeholder="Enter password" required>
                    <i class='bx bx-hide' id="toggle-password-icon-1" onclick="togglePassword('password', 'toggle-password-icon-1')" style="position: absolute; right: 18px; top: 42px; font-size: 1.2rem; color: #94a3b8; cursor: pointer;"></i>
                    <div style="font-size: 0.7rem; color: #94a3b8; margin-top: 8px; font-weight: 600;">Minimum 8 characters</div>
                </div>
                <div style="position: relative;">
                    <label for="password_confirmation" class="form-label">Confirm Password <span style="color: #ef4444;">*</span></label>
                    <input type="password" class="form-input" id="password_confirmation" name="password_confirmation" placeholder="Confirm password" required>
                    <i class='bx bx-hide' id="toggle-password-icon-2" onclick="togglePassword('password_confirmation', 'toggle-password-icon-2')" style="position: absolute; right: 18px; top: 42px; font-size: 1.2rem; color: #94a3b8; cursor: pointer;"></i>
                </div>
            </div>
        </div>

        <div class="premium-panel" style="margin-bottom: 24px;">
            <div class="section-title">
                <div class="section-icon" style="background: #fdf4ff; color: #d946ef;"><i class='bx bx-image'></i></div>
                Profile Photo (Optional)
            </div>

            <div style="display: flex; gap: 24px; align-items: flex-start;">
                <div style="flex: 1;">
                    <label for="photo" class="photo-upload-box" style="display: block;">
                        <i class='bx bx-cloud-upload' style="font-size: 3rem; color: #94a3b8; margin-bottom: 12px;"></i>
                        <div style="font-size: 1rem; font-weight: 800; color: var(--deep-slate);">Click to upload photo</div>
                        <div style="font-size: 0.8rem; color: #64748b; margin-top: 4px;">JPG, PNG up to 2MB</div>
                        <input type="file" id="photo" name="photo" accept="image/*" style="display: none;" onchange="previewImage(event)">
                    </label>
                </div>
                
                <div id="photo-preview" style="display: none; width: 140px; text-align: center;">
                    <div style="position: relative; display: inline-block;">
                        <img id="preview-img" src="" style="width: 120px; height: 120px; object-fit: cover; border-radius: 20px; border: 2px solid var(--border-color);">
                        <button type="button" onclick="removeImage()" style="position: absolute; top: -10px; right: -10px; background: #ef4444; color: #fff; border: none; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);">
                            <i class='bx bx-x'></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 16px; margin-top: 32px;">
            <a href="{{ route('users.index') }}" class="btn-cancel">Cancel</a>
            <button type="submit" class="btn-save">Create User</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bx-hide');
            icon.classList.add('bx-show');
        } else {
            input.type = 'password';
            icon.classList.remove('bx-show');
            icon.classList.add('bx-hide');
        }
    }

    function previewImage(event) {
        const file = event.target.files[0];
        const previewDiv = document.getElementById('photo-preview');
        const previewImg = document.getElementById('preview-img');

        if (file) {
            if (file.size > 2 * 1024 * 1024) {
                Swal.fire({
                    title: 'File too large',
                    text: 'Please select an image smaller than 2MB',
                    icon: 'error',
                    confirmButtonColor: '#0f172a'
                });
                event.target.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewDiv.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            previewDiv.style.display = 'none';
        }
    }

    function removeImage() {
        document.getElementById('photo').value = '';
        document.getElementById('photo-preview').style.display = 'none';
    }

    document.getElementById('create-user-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        Swal.fire({
            title: 'Create User?',
            text: 'Save and provision this new account?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#22c55e',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Yes, create it!',
            customClass: { popup: 'rounded-[28px]' }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
@endsection
