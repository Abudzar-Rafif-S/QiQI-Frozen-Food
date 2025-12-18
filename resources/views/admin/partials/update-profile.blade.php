<form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

    <div class="form-group">
        <label for="fullname">Fullname</label>
        <input type="text" class="form-control" id="fullname" name="fullname" value="{{ old('fullname', $admin->fullname) }}">
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}">
    </div>

    <div class="form-group">
        <label for="phone_number">Phone Number</label>
        <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number', $admin->phone_number) }}">
    </div>

    <div class="form-group">
        <label for="address">Address</label>
        <textarea class="form-control" id="address" name="address">{{ old('address', $admin->address) }}</textarea>
    </div>

    <div class="form-group">
        <label for="avatar">Avatar</label>
        <input type="file" class="form-control-file" id="avatar" name="avatar">
        @if($admin->avatar)
            <img src="{{ asset('avatars/' . $admin->avatar) }}" alt="Avatar" class="mt-2 rounded-circle" width="100">
        @endif
    </div>

    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
</form>
