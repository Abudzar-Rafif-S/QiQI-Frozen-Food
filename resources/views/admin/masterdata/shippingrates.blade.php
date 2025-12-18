@extends('admin.index')

@section('content')
    <div class="container-fluid">

        {{-- ALERT SUCCESS --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- TABLE LIST --}}
        <div class="card shadow">

            <div class="card-header d-flex justify-content-between align-items-center mb-4">
                <h3 class="mb-0">Shipping Rates</h3>
                <button class="btn btn-outline-primary" data-toggle="modal" data-target="#modalAdd">
                    + Add Shipping Rate
                </button>
            </div>

            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>City</th>
                            <th>Price / KG</th>
                            <th>Note</th>
                            <th width="140px">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @foreach ($shippingRates as $rate)
                            <tr id="row-{{ $rate->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $rate->city->city_name }}</td>
                                <td>Rp {{ number_format($rate->price_per_kg) }}</td>
                                <td>{{ $rate->note ?? '-' }}</td>
                                <td>
                                    <button class="btn btn-outline-warning btn-sm btn-edit" data-id="{{ $rate->id }}"
                                        data-city="{{ $rate->city_id }}" data-price="{{ $rate->price_per_kg }}"
                                        data-note="{{ $rate->note }}">
                                        Edit
                                    </button>

                                    <button class="btn btn-outline-danger btn-sm btn-delete" data-id="{{ $rate->id }}">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>



    {{-- ========================================= --}}
    {{-- MODAL ADD (Bootstrap 4) --}}
    {{-- ========================================= --}}
    <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form id="formAdd" class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Add Shipping Rate</h5>

                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label>City</label>
                        <select name="city_id" class="form-control" required>
                            <option value="">-- Select City --</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->city_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Price per KG</label>
                        <input type="number" name="price_per_kg" class="form-control" required min="0">
                    </div>

                    <div class="form-group">
                        <label>Note</label>
                        <textarea name="note" class="form-control"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-success" type="submit">Save</button>
                </div>

            </form>
        </div>
    </div>



    {{-- ========================================= --}}
    {{-- MODAL EDIT (Bootstrap 4) --}}
    {{-- ========================================= --}}
    <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form id="formEdit" class="modal-content">
                @csrf
                @method('PUT')

                <input type="hidden" name="id">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Shipping Rate</h5>

                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label>City</label>
                        <select name="city_id" class="form-control" required>
                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->city_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Price per KG</label>
                        <input type="number" name="price_per_kg" class="form-control" required min="0">
                    </div>

                    <div class="form-group">
                        <label>Note</label>
                        <textarea name="note" class="form-control"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-success" type="submit">Update</button>
                </div>

            </form>
        </div>
    </div>



    {{-- ========================================= --}}
    {{-- AJAX --}}
    {{-- ========================================= --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(function() {

            // Add
            $('#formAdd').submit(function(e) {
                e.preventDefault();

                $.post("{{ route('admin.shipping-rates.store') }}", $(this).serialize(), function(res) {
                    if (res.success) {
                        location.reload();
                    }
                });
            });

            // Edit Button Click
            $('.btn-edit').click(function() {
                $('#modalEdit [name=id]').val($(this).data('id'));
                $('#modalEdit [name=city_id]').val($(this).data('city'));
                $('#modalEdit [name=price_per_kg]').val($(this).data('price'));
                $('#modalEdit [name=note]').val($(this).data('note'));

                $('#modalEdit').modal('show');
            });

            // Update
            $('#formEdit').submit(function(e) {
                e.preventDefault();

                let id = $('#modalEdit [name=id]').val();

                $.post(`/admin/shipping-rates/${id}`, $(this).serialize(), function(res) {
                    if (res.success) {
                        location.reload();
                    }
                });
            });

            // Delete
            $('.btn-delete').click(function() {
                if (!confirm("Delete this shipping rate?")) return;

                let id = $(this).data('id');

                $.post(`/admin/shipping-rates/${id}`, {
                    _token: "{{ csrf_token() }}",
                    _method: "DELETE"
                }, function(res) {
                    if (res.success) {
                        $(`#row-${id}`).remove();
                    }
                });
            });

        });
    </script>
@endsection
