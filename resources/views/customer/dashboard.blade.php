@extends('customer.index')

@section('title', 'Dashboard Customer')

@section('content')
 {{-- =======================
     HERO CAROUSEL
======================= --}}
    @include('customer.partials.hero-carousel')

    {{-- =======================
     FITUR UNGGULAN
======================= --}}
    @include('customer.partials.features')

    {{-- =======================
     KATEGORI POPULER
======================= --}}
    @include('customer.partials.categories')

    {{-- =======================
     PRODUK TERBARU
======================= --}}
    @include('customer.partials.latest-products')

    {{-- =======================
     PRODUK DISKON
======================= --}}
    @include('customer.partials.discount-products')

@endsection
