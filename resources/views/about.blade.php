@extends('layouts.app')

@section('content')
@php
    $settings = \App\Models\Setting::first();
@endphp
<section class="section">
    <div class="container">
        <h1 class="section-title">{{ $settings->about_title ?? 'About AICIS' }}</h1>
        
        <div style="max-width: 800px; margin: 0 auto; line-height: 1.8;">
            <div style="margin-bottom: 20px; text-align: justify;">
                {!! $settings->about_content ? nl2br(e($settings->about_content)) : 'Information will be updated soon.' !!}
            </div>
            <div style="text-align: center; margin-top: 40px;">
                @if($settings && $settings->logo)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($settings->logo) }}" alt="{{ $settings->about_title ?? 'Logo' }}" style="max-width: 300px; opacity: 0.8;">
                @else
                    <img src="{{ asset('images/logo.png') }}" alt="AICIS Logo" style="max-width: 300px; opacity: 0.8;">
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
