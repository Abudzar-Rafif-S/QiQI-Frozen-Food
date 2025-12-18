<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\City;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CustomerProfileController extends Controller
{
    /**
     * Halaman utama management profile customer (dengan sidebar)
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Ambil data customer berdasarkan user_id
        $customer = Customer::where('user_id', $user->id)->first();

        // Dropdown kota untuk update profile
        $cities = City::orderBy('city_name')->get();

        // Section aktif pada sidebar
        $section = $request->query('section', 'profile');

        return view('customer.profile-index', compact(
            'user',
            'customer',
            'cities',
            'section'
        ));
    }

    /**
     * OPTIONAL: Hanya redirect, karena viewnya dipusatkan ke index()
     */
    public function edit(Request $request)
    {
        return Redirect::route('customer.profile.index', [
            'section' => 'profile'
        ]);
    }

    /**
     * Update customer profile data.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // --- Update tabel users ---
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();

        // --- Ambil data customer terkait ---
        $customer = Customer::where('user_id', $user->id)->first();

        // Data dasar customer
        $customerData = $request->only(['fullname', 'city_id', 'address', 'phone', 'postal_code']);

        // --- Handle avatar upload ---
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $filename = time() . '_' . $avatar->getClientOriginalName();

            $avatarPath = public_path('avatars');
            if (!file_exists($avatarPath)) {
                mkdir($avatarPath, 0755, true);
            }

            // Hapus avatar lama jika ada
            if ($customer && $customer->avatar && file_exists($avatarPath . '/' . $customer->avatar)) {
                unlink($avatarPath . '/' . $customer->avatar);
            }

            // Simpan avatar baru
            $avatar->move($avatarPath, $filename);
            $customerData['avatar'] = $filename;
        }

        // Update tabel customers
        if ($customer) {
            $customer->update($customerData);
        } else {
            // Opsional: buat record baru jika belum ada (sebaiknya tidak terjadi di update)
            Customer::create(array_merge($customerData, ['user_id' => $user->id]));
        }

        return Redirect::route('customer.profile.index', [
            'section' => 'update-profile'
        ])->with('status', 'profile-updated');
    }

    /**
     * Delete account (customer).
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        $customer = Customer::where('user_id', $user->id)->first();

        Auth::logout();

        // Hapus avatar customer jika ada
        if ($customer && $customer->avatar && file_exists(public_path('avatars/' . $customer->avatar))) {
            unlink(public_path('avatars/' . $customer->avatar));
        }

        // Hapus data customer dan user
        if ($customer) {
            $customer->delete();
        }
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
