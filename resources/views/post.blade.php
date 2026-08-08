@extends('layouts.app')

@section('content')
@php
    $imageUrl = '';
    if ($post->featured_image) {
        if (\Illuminate\Support\Str::startsWith($post->featured_image, ['http://', 'https://'])) {
            $imageUrl = $post->featured_image;
        } else {
            $imageUrl = \Illuminate\Support\Facades\Storage::url($post->featured_image);
        }
    }
    
    // Calculate estimated reading time
    $wordCount = str_word_count(strip_tags($post->content));
    $readingTime = ceil($wordCount / 200);
    if ($readingTime < 1) $readingTime = 1;

    // Get dark media logo for watermark silhouette
    $siteSettings = \App\Models\Setting::first();
    $silhouetteUrl = '';
    if ($siteSettings) {
        // Use dark_logo (logo for dark media) specifically
        if ($siteSettings->dark_logo) {
            $silhouetteUrl = \Illuminate\Support\Facades\Storage::url($siteSettings->dark_logo);
        } else {
            $silhouetteUrl = \Illuminate\Support\Facades\Storage::url($siteSettings->logo);
        }
    }
@endphp

<style>
    /* Elegant Green Header */
    .article-page-header {
        position: relative;
        background-color: var(--primary-color);
        /* Deep elegant gradient */
        background: linear-gradient(135deg, var(--primary-color) 0%, #114216 100%);
        padding: 90px 20px 80px 20px;
        text-align: center;
        overflow: hidden;
        border-bottom: 2px solid var(--accent-color);
        box-shadow: 0 0 30px rgba(27, 94, 32, 0.4);
    }

    /* The Silhouette Watermark */
    .article-page-header::before {
        content: '';
        position: absolute;
        top: 20px;
        right: 20px;
        width: 350px;
        height: 350px;
        background-image: url('{{ $silhouetteUrl }}');
        background-repeat: no-repeat;
        background-position: top right;
        background-size: contain;
        opacity: 0.1; /* Adjusted for better visibility of the original dark media logo */
        pointer-events: none;
        z-index: 1;
    }

    .article-header-inner {
        position: relative;
        z-index: 2;
        max-width: 900px;
        margin: 0 auto;
        animation: fadeUp 1s ease-out forwards;
    }

    .article-category-pill {
        display: inline-block;
        background-color: var(--accent-color);
        color: #fff;
        padding: 6px 24px;
        border-radius: 50px;
        font-family: var(--font-heading);
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    .article-title-main {
        font-family: var(--font-heading);
        font-size: 2.6rem; /* Reduced from 3.4rem */
        font-weight: 700;
        line-height: 1.3;
        color: #ffffff;
        margin-bottom: 25px;
        text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }

    .article-meta-bar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 25px;
        color: rgba(255, 255, 255, 0.8);
        font-family: var(--font-body);
        font-size: 0.9rem;
        font-weight: 500;
    }

    .article-meta-bar span {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .article-meta-bar i {
        color: var(--accent-color);
    }

    /* Content Area */
    .article-content-wrapper {
        max-width: 860px;
        margin: -40px auto 100px auto; /* Pull content up slightly overlapping the header */
        padding: 40px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        position: relative;
        z-index: 5;
    }

    .article-hero-image {
        width: 100%;
        border-radius: 12px;
        margin-bottom: 40px;
        position: relative;
    }

    /* Morning Sunlight Glistening Animation */
    .article-hero-image::after {
        content: '';
        position: absolute;
        top: -30px;
        right: -30px;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255,255,255,0.9) 0%, rgba(255,235,160,0.6) 25%, rgba(255,215,100,0.2) 50%, transparent 70%);
        border-radius: 50%;
        filter: blur(15px);
        pointer-events: none;
        animation: morningSunGlisten 4s infinite alternate ease-in-out;
        z-index: 10;
        mix-blend-mode: overlay;
    }

    @keyframes morningSunGlisten {
        0% { transform: scale(0.8) translate(10px, -10px); opacity: 0.5; }
        50% { opacity: 1; }
        100% { transform: scale(1.4) translate(-20px, 20px); opacity: 0.8; }
    }

    .article-hero-image img {
        width: 100%;
        height: auto;
        max-height: 500px;
        object-fit: cover;
        border-radius: 12px;
        display: block;
    }

    /* Magazine-style Typography */
    .article-body-text {
        font-family: var(--font-body);
        font-size: 1.05rem; /* Reduced from 1.2rem */
        line-height: 1.9; /* Adjusted for better readability */
        color: #3b4252;
        padding: 0 10px;
        text-align: justify; /* Force text alignment to justify */
    }
    
    .article-body-text p.first-text-paragraph::first-letter {
        font-family: var(--font-heading, sans-serif);
        color: #2e7d32; /* Hijau / Green */
        font-size: 4rem; 
        line-height: 1;
        float: left;
        margin-right: 12px;
        margin-top: 5px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .article-body-text img {
        width: 100%;
        max-height: 400px;
        object-fit: cover;
        border-radius: 8px;
        margin: 25px 0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .article-body-text p {
        margin-bottom: 30px;
    }

    .article-body-text h2, .article-body-text h3 {
        font-family: var(--font-heading);
        color: var(--primary-color);
        font-weight: 700;
        margin-top: 50px;
        margin-bottom: 25px;
        line-height: 1.4;
    }

    /* Tags */
    .article-tags-box {
        margin-top: 60px;
        padding: 35px 20px;
        background: #f8fafc;
        border-radius: 12px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
        border: 1px solid var(--border-color);
    }

    .article-tag {
        background: var(--white);
        border: 1px solid #cbd5e1;
        color: #475569;
        padding: 8px 22px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .article-tag:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: #fff;
        box-shadow: 0 4px 12px rgba(27, 94, 32, 0.2);
    }

    /* Animations */
    @keyframes fadeUp {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    /* Dark Mode Support */
    body.dark-mode .article-content-wrapper { background: #1e293b; box-shadow: 0 15px 35px rgba(0,0,0,0.2); }
    body.dark-mode .article-body-text { color: #d8dee9; }
    body.dark-mode .article-tags-box { background: #1e293b; border-color: #334155; }
    body.dark-mode .article-tag { background: transparent; border-color: #475569; color: #cbd5e1; }
    
    @media (max-width: 768px) {
        .article-page-header { padding: 60px 20px; }
        .article-title-main { font-size: 2.3rem; }
        .article-meta-bar { flex-direction: column; gap: 15px; }
        .article-content-wrapper { margin-top: -20px; }
        .article-body-text { font-size: 1.1rem; }
        .article-body-text p.first-text-paragraph::first-letter { font-size: 3.2rem; }
        .article-body-text img { max-height: 280px; }
    }
</style>

<!-- Elegant Green Header Section -->
<div class="article-page-header">
    <div class="article-header-inner">
        @if($post->category)
            <div class="article-category-pill">{{ $post->category->name }}</div>
        @endif
        
        <h1 class="article-title-main">{{ $post->title }}</h1>
        
        <div class="article-meta-bar">
            <span><i class="far fa-calendar-alt"></i> {{ $post->published_at ? $post->published_at->format('F d, Y') : $post->created_at->format('F d, Y') }}</span>
            <span><i class="far fa-clock"></i> {{ $readingTime }} min read</span>
            <span><i class="fas fa-bookmark"></i> Featured News</span>
        </div>
    </div>
</div>

<!-- Article Content -->
<div class="article-content-wrapper">
    <!-- Note: Featured Image is intentionally not displayed here, as it's typically embedded in the content itself or shown on the landing page -->

    <div class="article-body-text">
        {!! $post->content !!}
    </div>
    
    <!-- Tags -->
    @if(is_array($post->hashtags) && count($post->hashtags) > 0)
        <div class="article-tags-box">
            <span style="font-weight: 700; color: var(--text-dark); margin-right: 15px; font-family: var(--font-heading);"><i class="fas fa-tags" style="color: var(--accent-color); margin-right: 8px;"></i> EXPLORE MORE:</span>
            @foreach($post->hashtags as $tag)
                @php
                    $cleanTag = ltrim($tag, '#');
                @endphp
                <a href="#" class="article-tag">{{ $cleanTag }}</a>
            @endforeach
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const content = document.querySelector('.article-body-text');
    if (content) {
        // Cari semua paragraf
        const paragraphs = content.querySelectorAll('p');
        for (let i = 0; i < paragraphs.length; i++) {
            // Jika paragraf memiliki teks nyata (bukan hanya gambar atau kosong)
            if (paragraphs[i].textContent.trim().length > 0) {
                paragraphs[i].classList.add('first-text-paragraph');
                break; // Berhenti setelah menemukan paragraf teks pertama
            }
        }
    }
});
</script>
@endsection
