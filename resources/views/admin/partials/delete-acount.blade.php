<form method="POST" action="{{ route('admin.profile.destroy') }}">
    @csrf
    @method('DELETE')

    <div class="form-group">
        <label for="password">Masukkan Password untuk Konfirmasi</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>

    <button type="submit" class="btn btn-danger">Hapus Akun</button>
</form>
