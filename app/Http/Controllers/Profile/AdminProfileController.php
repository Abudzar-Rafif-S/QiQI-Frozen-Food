<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminProfileController extends Controller
{
    /**
     * Display the admin profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $admin = $user->admin; // Ambil data admin dari relasi

        return view('admin.profile', [
            'user' => $user,
            'admin' => $admin,
        ]);
    }

    /**
     * Update admin profile data.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $admin = $user->admin;

        // --- Update tabel users ---
        $validatedUserData = $request->validated();
        $user->fill($validatedUserData);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();

        // --- Update tabel admins ---
        $adminData = $request->only(['fullname', 'phone_number', 'address']);

        // Upload avatar jika ada
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $filename = time() . '_' . $avatar->getClientOriginalName();

            // Gunakan public_path('avatars') dan buat folder jika belum ada
            $avatarPath = public_path('avatars');
            if (!file_exists($avatarPath)) {
                mkdir($avatarPath, 0755, true);
            }

            // Hapus avatar lama jika ada
            if ($admin->avatar && file_exists($avatarPath . '/' . $admin->avatar)) {
                unlink($avatarPath . '/' . $admin->avatar);
            }

            // Simpan file avatar baru
            $avatar->move($avatarPath, $filename);
            $adminData['avatar'] = $filename;
        }

        $admin->update($adminData);

        return Redirect::route('admin.profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete account (admin).
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        // Hapus avatar admin dari public
        if ($user->admin && $user->admin->avatar && file_exists(public_path('avatars/' . $user->admin->avatar))) {
            unlink(public_path('avatars/' . $user->admin->avatar));
        }

        // Hapus admin terkait
        $user->admin()->delete();

        // Hapus user
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
