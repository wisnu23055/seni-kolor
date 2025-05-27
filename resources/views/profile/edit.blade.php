@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold mb-0">Edit Profil</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('profile.index') }}">Profil</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <!-- Update Profile Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="fw-bold mb-3">Informasi Profil</h3>
                    <p class="text-muted mb-4">Perbarui informasi profil dan alamat email akun Anda.</p>
                    
                    <form method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Nama</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                
                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                    <div class="mt-2 text-warning small">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        Email Anda belum diverifikasi.
                                        
                                        <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline small">
                                                Kirim ulang email verifikasi
                                            </button>
                                        </form>
                                    </div>
                                    
                                    @if (session('status') === 'verification-link-sent')
                                        <div class="mt-2 text-success small">
                                            <i class="fas fa-check me-1"></i>
                                            Link verifikasi baru telah dikirim ke alamat email Anda.
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            
                            @if (session('status') === 'profile-updated')
                                <div class="text-success ms-3">
                                    <i class="fas fa-check me-1"></i>
                                    Profil berhasil diperbarui.
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Update Password -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="fw-bold mb-3">Ubah Password</h3>
                    <p class="text-muted mb-4">Pastikan akun Anda menggunakan password yang panjang dan acak untuk keamanan.</p>
                    
                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="current_password" class="form-label fw-semibold">Password Saat Ini</label>
                                <input type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" id="current_password" name="current_password" required>
                                @error('current_password', 'updatePassword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4">
                                <label for="password" class="form-label fw-semibold">Password Baru</label>
                                <input type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" id="password" name="password" required>
                                @error('password', 'updatePassword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4">
                                <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                                <input type="password" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" id="password_confirmation" name="password_confirmation" required>
                                @error('password_confirmation', 'updatePassword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center">
                            <button type="submit" class="btn btn-primary">Ubah Password</button>
                            
                            @if (session('status') === 'password-updated')
                                <div class="text-success ms-3">
                                    <i class="fas fa-check me-1"></i>
                                    Password berhasil diubah.
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Delete User -->
            <div class="card border-0 shadow-sm border-danger">
                <div class="card-body">
                    <h3 class="fw-bold mb-3 text-danger">Hapus Akun</h3>
                    <p class="text-muted mb-4">Setelah akun Anda dihapus, semua data Anda akan terhapus secara permanen. Sebelum menghapus akun, silakan unduh data apa pun yang ingin Anda simpan.</p>
                    
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
                        <i class="fas fa-trash-alt me-2"></i> Hapus Akun
                    </button>
                    
                    <!-- Confirmation Modal -->
                    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmUserDeletionModalLabel">Konfirmasi Hapus Akun</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="mb-3">Apakah Anda yakin ingin menghapus akun Anda? Setelah akun Anda dihapus, semua data Anda akan terhapus secara permanen. Tindakan ini tidak dapat dibatalkan.</p>
                                    
                                    <form method="post" action="{{ route('profile.destroy') }}" id="deleteUserForm">
                                        @csrf
                                        @method('delete')
                                        
                                        <div class="mb-3">
                                            <label for="delete_password" class="form-label fw-semibold">Password</label>
                                            <input type="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" id="delete_password" name="password" placeholder="Masukkan password Anda untuk konfirmasi" required>
                                            @error('password', 'userDeletion')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="button" class="btn btn-danger" onclick="document.getElementById('deleteUserForm').submit();">Hapus Akun</button>
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