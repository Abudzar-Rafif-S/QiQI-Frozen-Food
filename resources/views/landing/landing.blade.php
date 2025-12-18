@extends('landing.index')

@section('title', 'Landing Page E-Commerce')

@section('content')

    {{-- =======================
     HERO CAROUSEL
======================= --}}
    @include('landing.partials.hero-carousel')

    {{-- =======================
     FITUR UNGGULAN
======================= --}}
    @include('landing.partials.features')

    {{-- =======================
     KATEGORI POPULER
======================= --}}
    @include('landing.partials.categories')

    {{-- =======================
     PRODUK TERBARU
======================= --}}
    @include('landing.partials.latest-products')

    {{-- =======================
     PRODUK DISKON
======================= --}}
    @include('landing.partials.discount-products')

@endsection
