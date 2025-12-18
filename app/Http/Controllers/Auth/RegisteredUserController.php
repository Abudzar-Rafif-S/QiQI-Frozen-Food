<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:15'],
        ]);

        // 1) Simpan ke tabel USERS
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        // 2) Tambahkan ke tabel CUSTOMERS
        Customer::create([
            'user_id' => $user->id,
            'fullname'     => $request->name,
            'city_id' => null,
            'address' => $request->address,
            'phone' => $request->phone,
            'postal_code' => null,
            'avatar' => null,
        ]);

        event(new Registered($user));

        // 3) Auto Login
        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
