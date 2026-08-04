@extends('layouts.app')

@section('content')
<div style="min-height: 80vh; display: flex; align-items: center; justify-content: center; background-image: url('{{ asset('images/hero-bg.png') }}'); background-size: cover; background-position: center; position: relative;">
    <div style="position: absolute; top:0; left:0; right:0; bottom:0; background: rgba(17, 17, 17, 0.85);"></div>
    
    <div style="background: rgba(30, 30, 30, 0.95); padding: 50px 40px; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,255,100,0.1); z-index: 10; text-align: center; max-width: 600px; width: 90%; border: 1px solid rgba(0, 255, 100, 0.2);">
        <i class="fas fa-check-circle" style="font-size: 5rem; color: #00ff66; margin-bottom: 25px; text-shadow: 0 0 20px rgba(0,255,102,0.4);"></i>
        
        <h2 style="font-size: 2.2rem; color: #fff; margin-bottom: 15px; font-weight: 700; letter-spacing: 1px;">Registration successful!</h2>
        
        <p style="font-size: 1.2rem; color: #bbb; margin-bottom: 35px; line-height: 1.6;">
            Your account is pending approval, please check your email periodically.
        </p>
        
        <a href="{{ url('/') }}" style="display: inline-block; padding: 14px 35px; background-color: #d4af37; color: #000; font-weight: 700; text-decoration: none; border-radius: 8px; font-size: 1.1rem; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);">
            <i class="fas fa-home" style="margin-right: 8px;"></i> Kembali ke Halaman Utama
        </a>
    </div>
</div>
@endsection
