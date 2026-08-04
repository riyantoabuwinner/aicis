@extends('layouts.app')

@section('content')
<section class="section">
    <div class="container">
        <h1 class="section-title">About AICIS</h1>
        
        <div style="max-width: 800px; margin: 0 auto; line-height: 1.8;">
            <p style="margin-bottom: 20px; font-size: 1.1rem;">
                The Annual International Conference on Islamic Studies (AICIS) is a premier academic event that gathers scholars, researchers, and practitioners from around the globe to discuss and present their latest findings in the field of Islamic Studies.
            </p>
            <p style="margin-bottom: 20px; font-size: 1.1rem;">
                Our mission is to foster a vibrant intellectual community where traditional Islamic sciences meet contemporary global challenges, promoting peace, understanding, and academic excellence.
            </p>
            <div style="text-align: center; margin-top: 40px;">
                <img src="{{ asset('images/logo.png') }}" alt="AICIS Logo" style="max-width: 300px; opacity: 0.8;">
            </div>
        </div>
    </div>
</section>
@endsection
