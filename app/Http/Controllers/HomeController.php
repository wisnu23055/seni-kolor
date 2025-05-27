<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Testimonial;
use App\Models\UMKM;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('is_featured', true)->take(4)->get();
        $testimonials = Testimonial::where('is_published', true)->take(3)->get();
        $umkm = UMKM::first();

        return view('home', compact('featuredProducts', 'testimonials', 'umkm'));
      

    }
}