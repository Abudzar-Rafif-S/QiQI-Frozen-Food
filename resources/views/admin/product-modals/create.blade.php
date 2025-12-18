<div class="modal fade" id="addProductModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body row">

                    <div class="mb-3 col-md-6">
                        <label>Nama Produk</label>
                        <input type="text" name="product_name" class="form-control" required>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label>Kategori</label>
                        <select name="category_id" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label>Brand</label>
                        <select name="brand_id" class="form-control" required>
                            <option value="">-- Pilih Brand --</option>
                            @foreach ($brand as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label>Diskon</label>
                        <select name="discount_id" class="form-control">
                            <option value="">-- Tanpa Diskon --</option>
                            @foreach ($discounts as $d)
                                <option value="{{ $d->id }}">
                                    {{ $d->value }}%
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 col-md-12">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="mb-3 col-md-4">
                        <label>Stok</label>
                        <input type="number" name="stock" class="form-control" required>
                    </div>

                    <div class="mb-3 col-md-4">
                        <label>Harga</label>
                        <input type="number" name="price" class="form-control" required>
                    </div>

                    <div class="mb-3 col-md-4">
                        <label>Berat (gram)</label>
                        <input type="number" name="weight" class="form-control" required>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label>Gambar Produk</label>
                        <input type="file" name="picture" class="form-control" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Tutup</button>
                    <button class="btn btn-outline-primary">Simpan</button>
                </div>

            </form>

        </div>
    </div>
</div>
