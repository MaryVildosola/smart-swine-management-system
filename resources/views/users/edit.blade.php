@extends('layouts.master')
@section('title')
    User Management | Edit
@endsection

@section('contents')
    <div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">Edit User</h5>
            <nav>
                <ol class="flex items-center whitespace-nowrap min-w-0">
                    <li class="text-[12px]">
                        <a class="flex items-center text-textmuted hover:text-primary" href="{{ route('dashboard') }}">
                            Dashboard
                            <i
                                class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-textmuted rtl:rotate-180"></i>
                        </a>
                    </li>
                    <li class="text-[12px]">
                        <a class="flex items-center text-textmuted hover:text-primary" href="{{ route('users.index') }}">
                            User Management
                            <i
                                class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-textmuted rtl:rotate-180"></i>
                        </a>
                    </li>
                    <li class="text-[12px]">
                        <span class="flex items-center text-primary">Edit</span>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6 text-defaultsize">
        <div class="xl:col-span-12 col-span-12">
            <div class="box">
                <div class="box-header flex justify-between">
                    <div class="box-title">Edit User: {{ $user->name }}</div>
                </div>

                <div class="box-body">

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger !bg-danger/10 !text-danger border-0 py-3 mb-4">
                            <strong>Whoops! Something went wrong.</strong>
                            <ul class="mb-0 mt-1 ps-4">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="edit-user-form" action="{{ route('users.update', $user->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-12 sm:gap-6">

                            {{-- Name --}}
                            <div class="xl:col-span-6 col-span-12 mb-4">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $user->name) }}" required>
                            </div>

                            {{-- Email --}}
                            <div class="xl:col-span-6 col-span-12 mb-4">
                                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $user->email) }}" required>
                            </div>

                            {{-- Birth Date --}}
                            <div class="xl:col-span-6 col-span-12 mb-4">
                                <label class="form-label">Birth Date</label>
                                <input type="date" name="birthdate" class="form-control"
                                    value="{{ old('birthdate', $user->birthdate ? $user->birthdate->format('Y-m-d') : '') }}">
                            </div>

                            {{-- Role --}}
                            <div class="xl:col-span-6 col-span-12 mb-4">
                                <label class="form-label">Account Role <span class="text-danger">*</span></label>
                                <select name="role" class="form-control" required>
                                    <option value="farm_worker" {{ old('role', $user->role) == 'farm_worker' ? 'selected' : '' }}>Farm Worker</option>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrator</option>
                                </select>
                            </div>

                            {{-- Status --}}
                            <div class="xl:col-span-6 col-span-12 mb-4">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-control" required>
                                    <option value="1" {{ old('status', $user->status) == 1 ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="0" {{ old('status', $user->status) == 0 ? 'selected' : '' }}>
                                        Inactive</option>
                                </select>
                            </div>

                            {{-- Region --}}
                            <div class="xl:col-span-6 col-span-12 mb-4">
                                <label class="form-label">Farm Region</label>
                                <input type="text" name="region" class="form-control"
                                    value="{{ old('region', $user->region) }}" placeholder="e.g. Batangas, Philippines">
                                <small class="text-textmuted">Used for AI disease scout localization.</small>
                            </div>

                            {{-- Photo --}}
                            <div class="xl:col-span-12 col-span-12 mb-4">
                                <label class="form-label">Profile Photo</label>
                                <div
                                    class="flex flex-col sm:flex-row items-center sm:items-center justify-between gap-6 mt-2 p-6 rounded-lg border border-defaultborder bg-light/30">

                                    {{-- Left: Current Photo --}}
                                    <div class="flex flex-col items-center gap-3 flex-shrink-0">
                                        <span class="text-xs font-semibold text-textmuted uppercase tracking-wider">Current
                                            Photo</span>
                                        @if ($user->photo)
                                            <img id="photo-preview" src="{{ asset('storage/' . $user->photo) }}"
                                                alt="Current Photo"
                                                style="width:200px;height:200px;object-fit:cover;border-radius:50%;border:4px solid rgba(var(--primary-rgb),0.25);box-shadow:0 4px 20px rgba(0,0,0,0.12);">
                                        @else
                                            <div id="photo-preview-placeholder"
                                                style="width:200px;height:200px;border-radius:50%;background:rgba(var(--primary-rgb),0.08);border:4px solid rgba(var(--primary-rgb),0.2);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
                                                <span
                                                    style="font-size:4rem;font-weight:800;color:rgba(var(--primary-rgb),0.4);">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </span>
                                            </div>
                                            <img id="photo-preview" src="" alt="Preview"
                                                style="width:200px;height:200px;object-fit:cover;border-radius:50%;border:4px solid rgba(var(--primary-rgb),0.25);box-shadow:0 4px 20px rgba(0,0,0,0.12);display:none;">
                                        @endif
                                        <small class="text-textmuted text-center">
                                            @if ($user->photo)
                                                Current photo
                                            @else
                                                No photo yet
                                            @endif
                                        </small>
                                    </div>

                                    {{-- Right: Upload New Photo --}}
                                    <div class="flex flex-col justify-center gap-3"
                                        style="min-width:260px;max-width:360px;">
                                        <span class="text-xs font-semibold text-textmuted uppercase tracking-wider">Upload
                                            New Photo</span>

                                        {{-- Upload preview --}}
                                        <div id="upload-preview-wrap"
                                            style="display:none; flex-direction:column; align-items:center; gap:8px; margin-bottom:8px;">
                                            <img id="upload-preview" src="" alt="New photo preview"
                                                style="width:200px;height:200px;object-fit:cover;border-radius:50%;border:4px solid rgba(var(--primary-rgb),0.35);box-shadow:0 4px 20px rgba(0,0,0,0.15);">
                                            <small class="text-success fw-semibold"><i class="ri-check-line me-1"></i>New
                                                photo selected</small>
                                        </div>

                                        <input type="file" name="photo" id="photo-input" class="form-control"
                                            accept="image/jpeg,image/png,image/jpg">
                                        <small class="text-textmuted">
                                            <i class="ri-information-line me-1"></i>
                                            JPG or PNG, max 2MB. Leave blank to keep current photo.
                                        </small>
                                    </div>

                                </div>
                            </div>


                        </div>

                        {{-- Password Section --}}
                        <hr class="border-defaultborder my-4">
                        <p class="fw-semibold text-defaulttextcolor mb-3"><i class="ri-shield-key-line me-1"></i>Change
                            Password <small class="text-textmuted fw-normal">(leave blank to keep current)</small></p>

                        <div class="grid grid-cols-12 sm:gap-6">
                            <div class="xl:col-span-6 col-span-12 mb-4">
                                <label class="form-label">New Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Min 8 characters">
                            </div>
                            <div class="xl:col-span-6 col-span-12 mb-4">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control"
                                    placeholder="Repeat new password">
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 mt-4 pt-4 border-t border-defaultborder">
                            <a href="{{ route('users.index') }}" class="ti-btn ti-btn-light !font-medium">
                                <i class="ri-arrow-left-line me-1"></i> Cancel
                            </a>
                            <button type="submit" class="ti-btn ti-btn-primary-full ti-btn-wave">
                                <i class="ri-check-line me-1"></i> Save Changes
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // SweetAlert2 confirm before save (Valex style-2)
        document.getElementById('edit-user-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            
            // Check HTML5 Validation
            if (!form.checkValidity()) {
                form.classList.add('was-validated');

                // Optional: Scroll to the first error
                const firstError = form.querySelector(':invalid');
                if (firstError) firstError.focus();
                return;
            }
            
            const swalWithButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'ti-btn bg-primary text-white ms-2',
                    cancelButton: 'ti-btn bg-danger text-white'
                },
                buttonsStyling: false
            });
            swalWithButtons.fire({
                title: 'Save Changes?',
                text: 'Are you sure you want to update this user\'s information?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, save it!',
                cancelButtonText: 'No, cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithButtons.fire('Cancelled', 'No changes were saved.', 'error');
                }
            });
        });

        // Photo live preview
        document.getElementById('photo-input').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(ev) {
                // Update the left-side current photo circle
                const preview = document.getElementById('photo-preview');
                const placeholder = document.getElementById('photo-preview-placeholder');
                preview.src = ev.target.result;
                preview.style.display = 'block';
                if (placeholder) placeholder.style.display = 'none';

                // Show the right-side upload preview
                const uploadPreview = document.getElementById('upload-preview');
                const uploadWrap = document.getElementById('upload-preview-wrap');
                uploadPreview.src = ev.target.result;
                uploadWrap.style.display = 'flex';
            };
            reader.readAsDataURL(file);
        });
    </script>
@endsection
