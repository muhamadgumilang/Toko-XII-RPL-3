<?php

namespace App\Http\Controllers;
use App\Models\Produk;

class FrontController extends Controller
{
    //index
    public function index()
    {
        $produk = Produk::with('kategori')->get();
        
        return view('front.index', compact('produk'));
    }
}