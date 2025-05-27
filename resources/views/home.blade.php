@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="bg-light py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <h1 class="display-4 fw-bold mb-4">Seni Kolor</h1>
                <p class="lead mb-4">Temukan kenyamanan dan gaya dalam setiap koleksi celana kolor kami. Produk UMKM berkualitas dengan bahan premium.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">Lihat Produk</a>
            </div>
            <div class="col-md-6">
                <img src="{{ $umkm?->image ? asset('storage/' . $umkm->image) : asset('images/logo-placeholder.png') }}" 
                     alt="UMKM Logo" class="img-fluid rounded">
            </div>
        </div>
    </div>
</section>

<!-- Keunggulan Section -->
<section class="py-5 bg-white">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Keunggulan Produk Kami</h2>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="text-primary mb-3">
                            <i class="fas fa-check feature-icon"></i>
                        </div>
                        <h3 class="fw-semibold mb-3">Kualitas Premium</h3>
                        <p class="text-muted">Terbuat dari bahan berkualitas tinggi yang nyaman digunakan sepanjang hari.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="text-primary mb-3">
                            <i class="fas fa-palette feature-icon"></i>
                        </div>
                        <h3 class="fw-semibold mb-3">Desain Unik</h3>
                        <p class="text-muted">Berbagai pilihan desain menarik yang membuat penampilan Anda lebih stylish.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="text-primary mb-3">
                            <i class="fas fa-dollar-sign feature-icon"></i>
                        </div>
                        <h3 class="fw-semibold mb-3">Harga Terjangkau</h3>
                        <p class="text-muted">Kualitas terbaik dengan harga yang bersahabat untuk semua kalangan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Produk Unggulan</h2>
        
        <div class="row g-4">
            @foreach($featuredProducts as $product)
            <div class="col-sm-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/product-placeholder.png') }}" 
                         alt="{{ $product->name }}" 
                         class="card-img-top" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold">{{ $product->name }}</h5>
                        <p class="card-text text-muted small">{{ Str::limit($product->description, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="text-primary fw-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary btn-sm">Detail</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-5">
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">Lihat Semua Produk</a>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-5 bg-white">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Testimoni Pelanggan</h2>
        
        <div class="row g-4">
            @foreach($testimonials as $testimonial)
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $testimonial->rating)
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>
                        <p class="card-text text-muted mb-3">"{{ $testimonial->content }}"</p>
                        <p class="fw-semibold mb-0">- {{ $testimonial->name }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-4">Bergabunglah dengan Pelanggan Kami yang Puas</h2>
        <p class="lead mb-4 mx-auto" style="max-width: 700px;">Dapatkan kenyamanan dan gaya dengan koleksi celana kolor terbaik dari Seni Kolor. Temukan produk yang sesuai dengan kebutuhan Anda sekarang!</p>
        <a href="{{ route('products.index') }}" class="btn btn-light btn-lg text-primary">Belanja Sekarang</a>
    </div>
</section>
@endsection