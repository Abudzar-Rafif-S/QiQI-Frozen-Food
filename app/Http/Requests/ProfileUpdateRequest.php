<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Hanya user yang login bisa update profil sendiri
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];

        // Jika user adalah admin, validasi tambahan untuk tabel admins
        if ($this->user()->isAdmin()) {
            $rules = array_merge($rules, [
                'fullname' => ['required', 'string', 'max:255'],
                'phone_number' => ['nullable', 'string', 'max:20'],
                'address' => ['nullable', 'string', 'max:255'],
                'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ]);
        }

        // Jika user adalah customer, validasi tambahan untuk tabel customers
        if ($this->user()->isCustomer()) {
            $rules = array_merge($rules, [
                'fullname' => ['required', 'string', 'max:255'],
                'city_id'      => ['nullable', 'integer', 'exists:cities,id'],
                'address'      => ['nullable', 'string', 'max:255'],
                'phone'        => ['nullable', 'string', 'max:20'],
                'postal_code'  => ['nullable', 'string', 'max:10'],
                'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ]);
        }

        return $rules;
    }
}
