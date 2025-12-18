{{-- HERO CAROUSEL --}}
<div id="heroCarousel" class="carousel slide" data-ride="carousel">

    <ol class="carousel-indicators">
        <li data-target="#heroCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#heroCarousel" data-slide-to="1"></li>
        <li data-target="#heroCarousel" data-slide-to="2"></li>
    </ol>

    <div class="carousel-inner">

        {{-- Slide 1 --}}
        <div class="carousel-item active">
            <img src="{{ asset('component/1.png') }}" class="d-block w-100" alt="Slide 1">
            <div class="carousel-caption d-none d-md-block">
                <h1 class="font-weight-bold">Promo Besar Akhir Tahun!</h1>
                <p>Dapatkan diskon hingga 50% untuk semua kategori.</p>
                <a href="#discount-section" class="btn btn-outline-primary rounded-pill btn-lg">Belanja Sekarang</a>
            </div>
        </div>

        {{-- Slide 2 --}}
        <div class="carousel-item">
            <img src="{{ asset('component/2.png') }}" class="d-block w-100" alt="Slide 2">
            <div class="carousel-caption d-none d-md-block">
                <h1 class="font-weight-bold">Gratis Ongkir Setiap Hari</h1>
                <p>Belanja tanpa ribet, tanpa biaya tambahan.</p>
                <a href="{{ route('guest.products') }}" class="btn btn-outline-primary rounded-pill btn-lg">Cek Produk</a>
            </div>
        </div>

        {{-- Slide 3 --}}
        <div class="carousel-item">
            <img src="{{ asset('component/3.png') }}" class="d-block w-100" alt="Slide 3">
            <div class="carousel-caption d-none d-md-block">
                <h1 class="font-weight-bold">Produk Terbaru Sudah Tersedia</h1>
                <p>Upgrade kebutuhanmu dengan produk terbaru kami.</p>
                <a href="#latest-products" class="btn btn-outline-primary rounded-pill btn-lg">Lihat Koleksi</a>
            </div>
        </div>

    </div>

    <a class="carousel-control-prev" href="#heroCarousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </a>

    <a class="carousel-control-next" href="#heroCarousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon"></span>
    </a>
</div>

<script>
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();

        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    });
});
</script>
