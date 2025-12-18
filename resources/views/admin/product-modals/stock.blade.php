<div class="modal fade" id="editStockModal{{ $product->id }}">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('admin.products.updateStock', $product->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="modal-header">
                    <h5 class="modal-title">Update Stok</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <label>Stok Produk</label>
                    <input type="number"
                           name="stock"
                           class="form-control"
                           value="{{ $product->stock }}"
                           required>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Tutup</button>
                    <button class="btn btn-outline-primary">Update</button>
                </div>

            </form>

        </div>
    </div>
</div>
