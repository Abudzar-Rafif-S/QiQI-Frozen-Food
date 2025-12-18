<h4 class="mb-4 fw-bold">Update Profil</h4>

@if ($errors->any())
    <div class="alert alert-danger rounded-pill">
        <strong>Perbaiki kesalahan berikut:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('status') == 'profile-updated')
    <div class="alert alert-success rounded-pill">
        Profil berhasil diperbarui!
    </div>
@endif

<form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

    {{-- Email --}}
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control rounded-pill"
               value="{{ old('email', $user->email) }}" required>
    </div>

    {{-- Nama Lengkap --}}
    <div class="mb-3">
        <label class="form-label">Nama Lengkap</label>
        <input type="text" name="fullname" class="form-control rounded-pill"
               value="{{ old('fullname', $customer->fullname) }}" required>
    </div>

    {{-- Nomor Telepon --}}
    <div class="mb-3">
        <label class="form-label">Nomor Telepon</label>
        <input type="text" name="phone" class="form-control rounded-pill"
               value="{{ old('phone', $customer->phone) }}">
    </div>

    {{-- Alamat --}}
    <div class="mb-3">
        <label class="form-label">Alamat</label>
        <input type="text" name="address" class="form-control rounded-pill"
               value="{{ old('address', $customer->address) }}">
    </div>

    {{-- Kode Pos --}}
    <div class="mb-3">
        <label class="form-label">Kode Pos</label>
        <input type="text" name="postal_code" class="form-control rounded-pill"
               value="{{ old('postal_code', $customer->postal_code) }}">
    </div>

    {{-- Kota --}}
    <div class="mb-3">
        <label class="form-label">Kota</label>
        <select name="city_id" class="form-select rounded-pill">
            <option value="">Pilih Kota</option>
            @foreach($cities as $city)
                <option value="{{ $city->id }}"
                        {{ $city->id == ($customer->city_id ?? null) ? 'selected' : '' }}>
                    {{ $city->city_name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Avatar --}}
    <div class="mb-3">
        <label class="form-label">Foto Profil (Avatar)</label>
        <input type="file" name="avatar" class="form-control rounded-pill">
        @if($customer?->avatar)
            <div class="mt-2">
                <img src="/avatars/{{ $customer->avatar }}" alt="Avatar" class="img-thumbnail" style="max-height: 100px;">
            </div>
        @endif
    </div>

    <button class="btn btn-warning w-100 rounded-pill">
        <i class="fas fa-save me-2"></i> Simpan Perubahan
    </button>
</form>
