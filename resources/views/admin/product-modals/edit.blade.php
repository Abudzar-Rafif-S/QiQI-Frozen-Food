<div class="modal fade" id="editProductModal{{ $product->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">Edit Produk</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body row">

                    <div class="mb-3 col-md-6">
                        <label>Nama Produk</label>
                        <input type="text" name="product_name" value="{{ $product->product_name }}"
                            class="form-control" required>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label>Kategori</label>
                        <select name="category_id" class="form-control" required>
                            @foreach ($categories as $c)
                                <option value="{{ $c->id }}"
                                    {{ $product->category_id == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label>Brand</label>
                        <select name="brand_id" class="form-control" required>
                            @foreach ($brand as $b)
                                <option value="{{ $b->id }}"
                                    {{ $product->brand_id == $b->id ? 'selected' : '' }}>
                                    {{ $b->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label>Diskon</label>
                        <select name="discount_id" class="form-control">
                            <option value="">-- Tanpa Diskon --</option>
                            @foreach ($discounts as $d)
                                <option value="{{ $d->id }}" @if ($product->discount_id == $d->id) selected @endif>
                                    {{ $d->value }}%
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 col-md-12">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3">{{ $product->description }}</textarea>
                    </div>
                    
                    <div class="mb-3 col-md-4">
                        <label>Harga</label>
                        <input type="number" name="price" value="{{ $product->price }}" class="form-control">
                    </div>

                    <div class="mb-3 col-md-4">
                        <label>Berat (gram)</label>
                        <input type="number" name="weight" value="{{ $product->weight }}" class="form-control">
                    </div>

                    <div class="mb-3 col-md-6">
                        <label>Gambar Baru (opsional)</label>
                        <input type="file" name="picture" class="form-control">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Tutup</button>
                    <button class="btn btn-outline-primary">Update</button>
                </div>

            </form>

        </div>
    </div>
</div>
