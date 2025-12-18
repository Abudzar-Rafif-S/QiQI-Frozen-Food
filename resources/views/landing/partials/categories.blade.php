<!-- ================= CATEGORIES SECTION ================= -->
<section id="categories" class="py-5">
    <div class="container">

        <div class="mb-4 text-center">
            <h2 class="font-weight-bold">Shop by Category</h2>
            <p class="text-muted">Temukan produk berdasarkan kategori favorit Anda</p>
        </div>

        <div class="row justify-content-center">

            @foreach($categories as $category)
                <div class="mb-4 col-6 col-md-4 col-lg-3">
                    <div class="p-4 text-center border-0 card category-card">

                        {{-- ICON --}}
                        <div class="mb-3">
                            @php
                                $icons = [
                                    'Sosis & Daging Olahan'   => 'fa-solid fa-hotdog icon-large',
                                    'Nugget & Olahan Ayam'   => 'fa-solid fa-drumstick-bite icon-large',
                                    'Default'   => 'fa-solid fa-bowl-food icon-large',
                                    'Kecantikan'=> 'fa-solid fa-spray-can',
                                    'Olahan Ikan / Seafood'   => 'fa-solid fa-fish icon-large',
                                    'Roti & Burger Patty'     => 'fa-solid fa-burger icon-large',
                                ];
                                $icon = $icons[$category->name] ?? $icons['Default'];
                            @endphp

                            <i class="{{ $icon }}" class="cat-icon"></i>
                        </div>

                        <h5 class="text-dark">{{ $category->name }}</h5>

                        <a href="{{ route('guest.products.byCategory', $category->id) }}"
                           class="mt-3 btn btn-outline-primary rounded-pill btn-sm category-btn">
                            Lihat Produk
                        </a>
                    </div>
                </div>
            @endforeach

        </div>

    </div>
</section>

<!-- FontAwesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
