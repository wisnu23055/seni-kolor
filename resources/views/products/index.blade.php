@extends('layouts.app')

@section('title', 'Produk Kami')

@section('content')
<!-- Products Header -->
<section class="bg-light py-5">
    <div class="container">
        <h1 class="fw-bold mb-0">Produk Kami</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Produk</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Products Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Sidebar with filters -->
            <div class="col-lg-3 mb-4 mb-lg-0">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h4 class="fw-bold mb-3">Kategori</h4>
                        <div class="list-group list-group-flush">
                            <a href="{{ route('products.index') }}" class="list-group-item list-group-item-action border-0 {{ request()->category ? '' : 'fw-bold text-primary' }}">
                                Semua Kategori
                            </a>
                            @foreach($categories as $category)
                            <a href="{{ route('products.index', ['category' => $category->slug]) }}" 
                                class="list-group-item list-group-item-action border-0 {{ request()->category == $category->slug ? 'fw-bold text-primary' : '' }}">
                                {{ $category->name }}
                            </a>
                            @endforeach
                        </div>
                        
                        <h4 class="fw-bold mt-4 mb-3">Harga</h4>
                        <form action="{{ route('products.index') }}" method="GET">
                            @if(request()->has('category'))
                                <input type="hidden" name="category" value="{{ request()->category }}">
                            @endif
                            <div class="mb-3">
                                <label for="min_price" class="form-label">Harga Minimum</label>
                                <input type="number" id="min_price" name="min_price" value="{{ request()->min_price }}" 
                                    class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="max_price" class="form-label">Harga Maksimum</label>
                                <input type="number" id="max_price" name="max_price" value="{{ request()->max_price }}" 
                                    class="form-control">
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    Terapkan Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Products grid -->
            <div class="col-lg-9">
                @if($products->isEmpty())
                    <div class="alert alert-warning">
                        <p class="mb-0">Tidak ada produk yang ditemukan. Silakan coba filter lain.</p>
                    </div>
                @else
                    <!-- Sort options -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <p class="text-muted mb-0">Menampilkan {{ $products->firstItem() }}-{{ $products->lastItem() }} dari {{ $products->total() }} produk</p>
                        <div class="d-flex align-items-center">
                            <span class="me-2 text-muted">Urutkan:</span>
                            <select onchange="window.location.href=this.value" class="form-select form-select-sm" style="width: auto;">
                                <option value="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'latest'])) }}" 
                                    {{ request()->sort == 'latest' || !request()->has('sort') ? 'selected' : '' }}>
                                    Terbaru
                                </option>
                                <option value="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'price_low'])) }}" 
                                    {{ request()->sort == 'price_low' ? 'selected' : '' }}>
                                    Harga: Rendah ke Tinggi
                                </option>
                                <option value="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'price_high'])) }}" 
                                    {{ request()->sort == 'price_high' ? 'selected' : '' }}>
                                    Harga: Tinggi ke Rendah
                                </option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Products grid -->
                    <div class="row g-4">
                        @foreach($products as $product)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <a href="{{ route('products.show', $product->id) }}">
                                    <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/product-placeholder.jpg') }}" 
                                        alt="{{ $product->name }}" 
                                        class="card-img-top" style="height: 200px; object-fit: cover;">
                                </a>
                                <div class="card-body">
                                    <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none">
                                        <h5 class="card-title fw-bold text-dark mb-2">{{ $product->name }}</h5>
                                    </a>
                                    <p class="card-text text-muted small mb-2">{{ Str::limit($product->description, 80) }}</p>
                                    <div class="d-flex align-items-center text-muted small mb-3">
                                        <i class="fas fa-tag me-1"></i>
                                        {{ $product->category->name }}
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-primary">Detail</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-5">
                        {{ $products->appends(request()->except('page'))->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Featured section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Mengapa Memilih Kami?</h2>
        
        <div class="row g-4">
            <div class="col-md-3 text-center">
                <div class="bg-white rounded-circle p-3 d-inline-flex justify-content-center align-items-center mb-3" style="width: 80px; height: 80px;">
                    <i class="fas fa-check text-primary fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-2">Kualitas Premium</h5>
                <p class="text-muted">Produk terbaik dengan bahan berkualitas tinggi</p>
            </div>
            
            <div class="col-md-3 text-center">
                <div class="bg-white rounded-circle p-3 d-inline-flex justify-content-center align-items-center mb-3" style="width: 80px; height: 80px;">
                    <i class="fas fa-comments text-primary fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-2">Layanan Pelanggan</h5>
                <p class="text-muted">Dukungan pelanggan responsif dan ramah</p>
            </div>
            
            <div class="col-md-3 text-center">
                <div class="bg-white rounded-circle p-3 d-inline-flex justify-content-center align-items-center mb-3" style="width: 80px; height: 80px;">
                    <i class="fas fa-shipping-fast text-primary fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-2">Pengiriman Cepat</h5>
                <p class="text-muted">Produk kami dikirim dengan cepat dan aman</p>
            </div>
            
            <div class="col-md-3 text-center">
                <div class="bg-white rounded-circle p-3 d-inline-flex justify-content-center align-items-center mb-3" style="width: 80px; height: 80px;">
                    <i class="fas fa-shield-alt text-primary fa-2x"></i>
                </div>
                <h5 class="fw-bold mb-2">Pembayaran Aman</h5>
                <p class="text-muted">Metode pembayaran aman dan terpercaya</p>
            </div>
        </div>
    </div>
</section>
@endsection