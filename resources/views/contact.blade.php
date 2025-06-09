@extends('layouts.app')

@section('title', 'Kontak Kami')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold mb-0">Kontak Kami</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Kontak</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h3 class="fw-bold mb-4">Hubungi Kami</h3>
                    <p class="text-muted mb-4">Jika Anda memiliki pertanyaan atau ingin bekerja sama dengan Seni Kolor, silakan hubungi kami melalui:</p>
                    
                    <div class="d-flex align-items-center mb-3">
                        <div class="text-primary me-3">
                            <i class="fas fa-envelope feature-icon"></i>
                        </div>
                        <div>
                            <p class="fw-semibold mb-0">Email</p>
                            <p class="text-muted mb-0">admin@senikolor.com</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center mb-3">
                        <div class="text-primary me-3">
                            <i class="fab fa-whatsapp feature-icon"></i>
                        </div>
                        <div>
                            <p class="fw-semibold mb-0">WhatsApp</p>
                            <p class="text-muted mb-0">
                                <a href="https://wa.me/6287857179236" target="_blank" class="text-decoration-none">+62 878 5717 9236</a>
                            </p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <div class="text-primary me-3">
                            <i class="fas fa-map-marker-alt feature-icon"></i>
                        </div>
                        <div>
                            <p class="fw-semibold mb-0">Lokasi</p>
                            <p class="text-muted mb-0">Jl. Contoh No. 123, Kota Contoh, Indonesia</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h3 class="fw-bold mb-4">Jam Operasional</h3>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-semibold">Senin - Jumat</span>
                        <span>08:00 - 17:00 WIB</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-semibold">Sabtu</span>
                        <span>09:00 - 15:00 WIB</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-semibold">Minggu &amp; Hari Libur</span>
                        <span>Tutup</span>
                    </div>
                    
                    <div class="alert alert-info mb-0">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div>
                                <p class="mb-0">Untuk pertanyaan dan layanan pelanggan, silakan hubungi kami melalui WhatsApp atau Email. Kami akan merespons Anda secepat mungkin.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="ratio ratio-16x9" style="height: 400px;">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.6664672948397!2d106.82496631431061!3d-6.175392362292368!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f421321644bd%3A0x48e94f907c32d8d3!2sMonas!5e0!3m2!1sen!2sid!4v1650000000000!5m2!1sen!2sid" 
                                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-5">
        <div class="col-12 text-center">
            <h3 class="fw-bold mb-4">Ikuti Kami</h3>
            <div class="d-flex justify-content-center gap-4">
                <a href="#" class="text-decoration-none">
                    <div class="bg-primary text-white rounded-circle p-3">
                        <i class="fab fa-facebook-f"></i>
                    </div>
                </a>
                <a href="#" class="text-decoration-none">
                    <div class="bg-danger text-white rounded-circle p-3">
                        <i class="fab fa-instagram"></i>
                    </div>
                </a>
                <a href="#" class="text-decoration-none">
                    <div class="bg-dark text-white rounded-circle p-3">
                        <i class="fab fa-tiktok"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection