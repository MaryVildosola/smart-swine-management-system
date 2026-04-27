<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    // Get all users with pagination
    public function getAllUsers(): View
    {
        $users = User::where('id', '!=', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Changed from get() to paginate()

        return view('users.index', compact('users'));
    }

    // Show create user form
    public function create(): View
    {
        return view('users.create');
    }

    // Store new user
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
            'birthdate' => ['nullable', 'date'],
            'role'      => ['required', 'string', 'in:admin,farm_worker'],
            'photo'     => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
            
            Storage::disk('public')->makeDirectory('users');
            $manager = new ImageManager(new Driver());
            $manager->read($photo)
                ->scaleDown(width: 300)
                ->toJpeg(80)
                ->save(storage_path('app/public/users/' . $photoName));
                
            $photoPath = 'users/' . $photoName;
        }

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'birthdate' => $validated['birthdate'] ?? null,
            'role' => $validated['role'],
            'status' => $validated['status'] ?? 1,
            'email_verified_at' => now(),
            'photo' => $photoPath,
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');
    }

    // Show edit user form (admin)
    public function editUser($id): View
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    // Show edit form for the currently logged-in user
    public function editOwnProfile(): View
    {
        $user = User::findOrFail(Auth::id());
        return view('users.edit', compact('user'));
    }

    // Update user
    public function updateUser(Request $request, $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'password'  => ['sometimes', 'nullable', 'string', 'min:8', 'confirmed'],
            'birthdate' => ['nullable', 'date'],
            'role'      => ['required', 'string', 'in:admin,farm_worker'],
            'status'    => ['required', 'boolean'],
            'photo'     => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ]);

        $user->name     = $validated['name'];
        $user->email    = $validated['email'];
        $user->birthdate = $validated['birthdate'] ?? null;
        $user->role     = $validated['role'];
        $user->status   = $validated['status'];

        // Update password only if provided
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Update photo if a new one was uploaded
        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $photo     = $request->file('photo');
            $photoName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
            
            Storage::disk('public')->makeDirectory('users');
            $manager = new ImageManager(new Driver());
            $manager->read($photo)
                ->scaleDown(width: 300)
                ->toJpeg(80)
                ->save(storage_path('app/public/users/' . $photoName));
                
            $user->photo = 'users/' . $photoName;
        }

        $user->save();

        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    // Update the currently logged-in user's own profile
    public function updateOwnProfile(Request $request): RedirectResponse
    {
        $user = User::findOrFail(Auth::id());

        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
            'password'  => ['sometimes', 'nullable', 'string', 'min:8', 'confirmed'],
            'birthdate' => ['nullable', 'date'],
            'status'    => ['required', 'boolean'],
            'photo'     => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ]);

        $user->name     = $validated['name'];
        $user->email    = $validated['email'];
        $user->birthdate = $validated['birthdate'] ?? null;
        $user->status   = $validated['status'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $photo     = $request->file('photo');
            $photoName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
            
            Storage::disk('public')->makeDirectory('users');
            $manager = new ImageManager(new Driver());
            $manager->read($photo)
                ->scaleDown(width: 300)
                ->toJpeg(80)
                ->save(storage_path('app/public/users/' . $photoName));
                
            $user->photo = 'users/' . $photoName;
        }

        $user->save();

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    // Delete user
    public function destroyUser($id): RedirectResponse
    {
        $user = User::findOrFail($id);

        // Prevent deleting own account
        if ($user->id === Auth::id()) {
            return redirect()
                ->route('users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User deleted successfully.');
    }

    // Show settings for the worker
    public function workerSettings(): View
    {
        $user = Auth::user();
        return view('worker.settings', compact('user'));
    }

    // Update settings specifically for workers
public function updateWorkerSettings(Request $request): RedirectResponse
{
    $user = auth()->user();

    $validated = $request->validate([
        'name'      => ['required', 'string', 'max:255'],
        'password'  => ['nullable', 'string', 'min:8', 'confirmed'],
        'photo'     => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        'theme'     => ['nullable', 'in:light,dark'],
    ]);

    // Update name
    $user->name = $validated['name'];

    // Update password if provided
    if (!empty($validated['password'])) {
        $user->password = Hash::make($validated['password']);
    }

    // HANDLE PHOTO UPLOAD
    if ($request->hasFile('photo')) {

        // delete old photo
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        $photo = $request->file('photo');
        $photoName = time() . '_' . uniqid() . '.jpg';

        Storage::disk('public')->makeDirectory('users');

        $manager = new ImageManager(new Driver());
        $manager->read($photo)
            ->scaleDown(width: 300)
            ->toJpeg(80)
            ->save(storage_path('app/public/users/' . $photoName));

        $user->photo = 'users/' . $photoName;
    }

    // Update theme if exists
    if ($request->filled('theme')) {
        $user->theme = $validated['theme'];
    }

    $user->save();

    return back()->with('success', 'Profile updated successfully.');
}
}