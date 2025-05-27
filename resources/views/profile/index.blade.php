@extends('layouts.app')

@section('title', 'Profil Pengguna')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold mb-0">Profil Pengguna</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Profil</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-4 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-user-circle fa-5x text-primary"></i>
                    </div>
                    <h3 class="fw-bold mb-2">{{ $user->name }}</h3>
                    <p class="text-muted mb-3">{{ $user->email }}</p>
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i> Edit Profil
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h3 class="fw-bold mb-4">Informasi Pengguna</h3>
                    
                    <div class="mb-4">
                        <h5 class="fw-semibold">Nama</h5>
                        <p>{{ $user->name }}</p>
                    </div>
                    
                    <div class="mb-4">
                        <h5 class="fw-semibold">Email</h5>
                        <p>{{ $user->email }}</p>
                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div class="alert alert-warning">
                                <p class="mb-0">Email belum diverifikasi. 
                                <form method="post" action="{{ route('verification.send') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0 m-0 align-baseline">Kirim email verifikasi</button>.
                                </form>
                                </p>
                            </div>
                        @endif
                    </div>
                    
                    <div class="mb-4">
                        <h5 class="fw-semibold">Tanggal Bergabung</h5>
                        <p>{{ $user->created_at->format('d M Y') }}</p>
                    </div>
                    
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i> Edit Profil
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection