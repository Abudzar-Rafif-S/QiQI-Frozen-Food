<form method="POST" action="{{ route('admin.profile.update') }}">
    @csrf
    @method('PATCH')

    <div class="form-group">
        <label for="current_password">Current Password</label>
        <input type="password" class="form-control" id="current_password" name="current_password">
    </div>

    <div class="form-group">
        <label for="password">New Password</label>
        <input type="password" class="form-control" id="password" name="password">
    </div>

    <div class="form-group">
        <label for="password_confirmation">Confirm Password</label>
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
    </div>

    <button type="submit" class="btn btn-warning">Update Password</button>
</form>
