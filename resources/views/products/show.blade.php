@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5 mb-4 mb-md-0">
            <div class="card border-0 shadow-sm">
                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/product-placeholder.jpg') }}" 
                    class="card-img-top img-fluid" alt="{{ $product->name }}">
            </div>
        </div>
        
        <div class="col-md-7">
            <h1 class="fw-bold mb-3">{{ $product->name }}</h1>
            
            <div class="mb-3">
                <span class="badge bg-primary">{{ $product->category->name }}</span>
            </div>
            
            <div class="mb-4">
                <h2 class="text-primary fw-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</h2>
            </div>
            
            <div class="mb-4">
                <p class="text-muted">{{ $product->description }}</p>
            </div>
            
            <form action="{{ route('cart.add', ['product' => $product->id]) }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="quantity" class="form-label fw-semibold">Jumlah</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1">
                    </div>
                    
                    <div class="col-md-4">
                        <label for="size" class="form-label fw-semibold">Ukuran</label>
                        <select name="size" id="size" class="form-select">
                            <option value="S">S</option>
                            <option value="M" selected>M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                        </select>
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex">
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-shopping-cart me-2"></i> Tambah ke Keranjang
                    </button>
                </div>
            </form>
            
            <div class="mt-4">
                <div class="d-flex align-items-center text-muted">
                    <i class="fas fa-shield-alt me-2"></i>
                    <span>Garansi produk 100% uang kembali</span>
                </div>
                <div class="d-flex align-items-center text-muted mt-2">
                    <i class="fas fa-shipping-fast me-2"></i>
                    <span>Pengiriman cepat ke seluruh Indonesia</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="fw-bold mb-4">Produk Terkait</h3>
            
            <div class="row g-4">
                @if(isset($bestSellerProduct))
                <!-- Produk Terlaris -->
                <div class="col-6 col-md-3">
                    <div class="card h-100 border-0 shadow-sm position-relative">
                        <div class="badge bg-danger position-absolute top-0 end-0 m-2">
                            <i class="fas fa-fire me-1"></i> Terlaris
                        </div>
                        <a href="{{ route('products.show', $bestSellerProduct->id) }}">
                            <img src="{{ $bestSellerProduct->image ? asset('storage/' . $bestSellerProduct->image) : asset('images/product-placeholder.jpg') }}" 
                                class="card-img-top" style="height: 180px; object-fit: cover;" alt="{{ $bestSellerProduct->name }}">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title fw-semibold">{{ $bestSellerProduct->name }}</h5>
                            <p class="card-text text-primary fw-bold">Rp {{ number_format($bestSellerProduct->price, 0, ',', '.') }}</p>
                            <a href="{{ route('products.show', $bestSellerProduct->id) }}" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection