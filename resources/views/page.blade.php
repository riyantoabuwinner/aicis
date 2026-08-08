@extends('layouts.app')

@section('content')
@php
    $imageUrl = '';
    if ($page->featured_image) {
        if (\Illuminate\Support\Str::startsWith($page->featured_image, ['http://', 'https://'])) {
            $imageUrl = $page->featured_image;
        } else {
            $imageUrl = \Illuminate\Support\Facades\Storage::url($page->featured_image);
        }
    }
    
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
        overflow: hidden;
        margin-bottom: 40px;
    }

    .article-hero-image img {
        width: 100%;
        height: auto;
        max-height: 500px;
        object-fit: cover;
        border-radius: 6px;
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
    
    .article-body-text > p:first-of-type::first-letter {
        font-family: var(--font-heading);
        color: var(--primary-color);
        font-size: 3.8rem; /* Reduced from 4.8rem */
        line-height: 0.8;
        float: left;
        padding-top: 8px;
        padding-right: 12px;
        font-weight: 700;
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
        .article-body-text > p:first-of-type::first-letter { font-size: 4rem; }
    }
</style>

<!-- Elegant Green Header Section -->
<div class="article-page-header">
    <div class="article-header-inner">
        <h1 class="article-title-main" style="margin-bottom: 0;">{{ $page->title }}</h1>
    </div>
</div>

<!-- Article Content -->
<div class="article-content-wrapper">
    <!-- Note: Featured Image is intentionally not displayed here, as it's typically embedded in the content itself or shown on the landing page -->

    <div class="article-body-text">
        {!! $page->content !!}
    </div>
</div>
@endsection
