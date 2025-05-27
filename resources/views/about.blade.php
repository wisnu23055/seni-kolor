@extends('layouts.app')

@section('title', 'Tentang Kami')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold mb-0">Tentang Kami</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tentang Kami</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <h2 class="fw-bold mb-3">Sejarah Seni Kolor</h2>
            <p class="text-muted">Seni Kolor dimulai tahun 2020 sebagai respons terhadap kebutuhan pakaian rumah yang nyaman namun tetap stylish. Kami percaya bahwa kenyamanan tidak harus mengorbankan gaya dan kualitas.</p>
            <p class="text-muted">Bermula dari workshop kecil di rumah pendiri kami, kini Seni Kolor telah berkembang menjadi brand lokal yang dipercaya oleh ribuan pelanggan di seluruh Indonesia.</p>
            <p class="text-muted">Kami berkomitmen untuk selalu menggunakan bahan berkualitas tinggi dan mendukung perekonomian lokal dengan bermitra dengan UMKM di berbagai daerah di Indonesia.</p>
        </div>
        <div class="col-lg-6">
            <img src="{{ $umkm?->image ? asset('storage/' . $umkm->image) : asset('images/logo-placeholder.png') }}" alt="Tentang Seni Kolor" class="img-fluid rounded shadow">
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h2 class="fw-bold mb-4">Visi & Misi</h2>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="text-primary me-3">
                                    <i class="fas fa-eye feature-icon"></i>
                                </div>
                                <div>
                                    <h4 class="fw-bold mb-3">Visi</h4>
                                    <p class="text-muted">Menjadi brand celana kolor terdepan di Indonesia yang dikenal karena kualitas, kenyamanan, dan dukungan terhadap UMKM lokal.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="text-primary me-3">
                                    <i class="fas fa-bullseye feature-icon"></i>
                                </div>
                                <div>
                                    <h4 class="fw-bold mb-3">Misi</h4>
                                    <ul class="text-muted">
                                        <li>Memproduksi celana kolor berkualitas tinggi dengan desain modern</li>
                                        <li>Memberikan pengalaman berbelanja yang menyenangkan</li>
                                        <li>Mendukung perkembangan UMKM lokal di Indonesia</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection