<div class="profile-view">

    {{-- Avatar --}}
    <div class="text-center mb-4">
        <img src="{{ $customer->avatar
            ? asset('avatars/' . $customer->avatar)
            : asset('avatars/cat.png') }}"
            class="rounded-circle shadow-sm"
            width="130" height="130" alt="Avatar">
    </div>

    {{-- Data Profile --}}
    <div class="row mb-3">
        <div class="col-sm-4 font-weight-bold text-secondary">Nama Lengkap</div>
        <div class="col-sm-8">{{ $customer->fullname }}</div>
    </div>

    <div class="row mb-3">
        <div class="col-sm-4 font-weight-bold text-secondary">Email</div>
        <div class="col-sm-8">{{ $customer->user->email }}</div>
    </div>

    <div class="row mb-3">
        <div class="col-sm-4 font-weight-bold text-secondary">No. HP</div>
        <div class="col-sm-8">{{ $customer->phone ?? '-' }}</div>
    </div>

    <div class="row mb-3">
        <div class="col-sm-4 font-weight-bold text-secondary">Kota</div>
        <div class="col-sm-8">{{ $customer->city->city_name ?? '-' }}</div>
    </div>

    <div class="row mb-3">
        <div class="col-sm-4 font-weight-bold text-secondary">Alamat</div>
        <div class="col-sm-8">{{ $customer->address ?? '-' }}</div>
    </div>

    <div class="row mb-3">
        <div class="col-sm-4 font-weight-bold text-secondary">Kode Pos</div>
        <div class="col-sm-8">{{ $customer->postal_code ?? '-' }}</div>
    </div>

</div>
