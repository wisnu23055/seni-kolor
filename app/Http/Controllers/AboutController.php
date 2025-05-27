<?php

namespace App\Http\Controllers;

use App\Models\UMKM;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        // Ambil data UMKM tunggal seperti di HomeController
        $umkm = UMKM::first();
        
        // Ambil semua data UMKM untuk section mitra
        $umkms = UMKM::all();
        
        // Kirim data ke view - perhatikan dua variabel berbeda
        return view('about', compact('umkm', 'umkms'));
    }
}