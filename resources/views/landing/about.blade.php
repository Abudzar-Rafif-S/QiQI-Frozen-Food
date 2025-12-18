@extends('landing.index')

@section('content')

<div class="container py-5">

    <!-- Title -->
    <div class="text-center mb-5">
        <h2 class="font-weight-bold">Tentang Kami</h2>
        <p class="text-muted">Mengenal lebih dekat siapa kami dan apa tujuan kami.</p>
    </div>

    <!-- Row 1 - About Description -->
    <div class="row mb-5">
        <div class="col-md-6">
            <img src="{{ asset('component/1.png') }}"
                 alt="About Us"
                 class="img-fluid rounded shadow-sm">
        </div>

        <div class="col-md-6 d-flex align-items-center">
            <div>
                <h4 class="font-weight-bold">Siapa Kami?</h4>
                <p class="text-muted">
                    Kami adalah platform e-commerce yang berkomitmen memberikan pengalaman belanja terbaik
                    untuk semua pelanggan. Dengan berbagai produk berkualitas, harga terjangkau, dan pengiriman cepat,
                    kami ingin menjadi pilihan utama masyarakat.
                </p>

                <h4 class="font-weight-bold mt-4">Visi Kami</h4>
                <p class="text-muted">
                    Menjadi platform belanja online terpercaya di Indonesia, yang mendukung UMKM dan memberikan
                    layanan terbaik kepada seluruh pengguna.
                </p>

                <h4 class="font-weight-bold mt-4">Misi Kami</h4>
                <ul class="text-muted">
                    <li>Memberikan pengalaman belanja nyaman dan mudah.</li>
                    <li>Menyediakan produk yang berkualitas dan terjamin.</li>
                    <li>Mendukung pertumbuhan brand lokal.</li>
                    <li>Menghadirkan inovasi pada layanan e-commerce.</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Row 2 - Values -->
    <div class="text-center mb-4">
        <h3 class="font-weight-bold mb-3">Nilai Utama Kami</h3>
        <p class="text-muted">Kami terus berusaha memberikan yang terbaik.</p>
    </div>

    <div class="row text-center">

        <div class="col-md-4 mb-4">
            <div class="p-4 shadow-sm bg-white rounded">
                <i class="fa fa-star fa-3x text-warning mb-3"></i>
                <h5 class="font-weight-bold">Kualitas</h5>
                <p class="text-muted">Produk kami dipilih dengan ketat untuk menjaga kepuasan pelanggan.</p>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="p-4 shadow-sm bg-white rounded">
                <i class="fa fa-heart fa-3x text-danger mb-3"></i>
                <h5 class="font-weight-bold">Kepercayaan</h5>
                <p class="text-muted">Kami selalu menjaga transparansi dan amanah dalam pelayanan.</p>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="p-4 shadow-sm bg-white rounded">
                <i class="fa fa-truck fa-3x text-primary mb-3"></i>
                <h5 class="font-weight-bold">Layanan Cepat</h5>
                <p class="text-muted">Pengiriman cepat dan aman menjadi prioritas kami.</p>
            </div>
        </div>

    </div>

</div>

@endsection
