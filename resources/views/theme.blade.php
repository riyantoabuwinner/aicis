@extends('layouts.app')

@section('title', 'Event Theme - ' . $theme->title)

@section('content')
<!-- Hero Section for Theme -->
<section style="position: relative; padding: 100px 0; background: url('{{ asset('images/cyber-bg.png') }}') no-repeat center center; background-size: cover; background-attachment: fixed; text-align: center; color: white;">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(27, 94, 32, 0.9); z-index: 1;"></div>
    
    <div class="container" style="position: relative; z-index: 2;">
        <h3 style="font-size: 1.2rem; font-weight: normal; letter-spacing: 3px; text-transform: uppercase; color: #dfb162; margin-bottom: 20px;">
            Main Theme
        </h3>
        <h1 style="font-size: 3rem; font-weight: 800; max-width: 900px; margin: 0 auto; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
            {{ $theme->title }}
        </h1>
    </div>
</section>

<!-- Content Section -->
<section style="padding: 80px 0; background-color: #f8f9fa;">
    <div class="container">
        <div style="max-width: 900px; margin: 0 auto; background: white; padding: 50px; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.08);">
            
            <div class="prose max-w-none" style="font-size: 1.1rem; line-height: 1.8; color: #444; text-align: justify;">
                @if($theme->description)
                    {!! $theme->description !!}
                @else
                    <p style="text-align: center; color: #888;"><i>Theme description will be updated soon.</i></p>
                @endif
            </div>
            
            <div style="margin-top: 50px; text-align: center;">
                <a href="{{ url('/') }}" class="btn btn-outline-primary" style="padding: 10px 30px; font-weight: 600;">
                    <i class="fas fa-arrow-left"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
