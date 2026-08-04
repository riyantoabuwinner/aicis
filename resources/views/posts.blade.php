@extends('layouts.app')

@section('content')
@php
    $siteSettings = \App\Models\Setting::first();
    $silhouetteUrl = '';
    if ($siteSettings) {
        if ($siteSettings->dark_logo) {
            $silhouetteUrl = \Illuminate\Support\Facades\Storage::url($siteSettings->dark_logo);
        } else {
            $silhouetteUrl = \Illuminate\Support\Facades\Storage::url($siteSettings->logo);
        }
    }
@endphp

<style>
    /* Elegant Green Header for Posts Index */
    .posts-page-header {
        position: relative;
        background-color: var(--primary-color);
        background: linear-gradient(135deg, var(--primary-color) 0%, #114216 100%);
        padding: 90px 20px 80px 20px;
        text-align: center;
        overflow: hidden;
        border-bottom: 2px solid var(--accent-color);
        box-shadow: 0 0 30px rgba(27, 94, 32, 0.4);
    }

    .posts-page-header::before {
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
        opacity: 0.1;
        pointer-events: none;
        z-index: 1;
    }

    .posts-header-inner {
        position: relative;
        z-index: 2;
        max-width: 900px;
        margin: 0 auto;
        animation: fadeUp 1s ease-out forwards;
    }

    .posts-title-main {
        font-family: var(--font-heading);
        font-size: 3rem;
        font-weight: 700;
        line-height: 1.3;
        color: #ffffff;
        margin-bottom: 20px;
        text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }
    
    .posts-subtitle {
        color: rgba(255, 255, 255, 0.85);
        font-size: 1.1rem;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }

    .posts-container {
        padding: 80px 0;
        background-color: #f9f9f9;
        position: relative;
        z-index: 5;
    }

    @keyframes fadeUp {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    
    /* Pagination Styles */
    .pagination-wrapper {
        margin-top: 60px;
        display: flex;
        justify-content: center;
    }
    .pagination-wrapper nav {
        background: #fff;
        padding: 10px 20px;
        border-radius: 50px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    .pagination-wrapper .page-link {
        color: var(--primary-color);
        border: none;
        padding: 10px 18px;
        font-weight: 600;
        border-radius: 50px;
        transition: all 0.3s;
    }
    .pagination-wrapper .page-item.active .page-link {
        background-color: var(--primary-color);
        color: #fff;
    }
    .pagination-wrapper .page-item:not(.active) .page-link:hover {
        background-color: rgba(27, 94, 32, 0.1);
    }
</style>

<div class="posts-page-header">
    <div class="posts-header-inner">
        <h1 class="posts-title-main">News & Updates</h1>
        <p class="posts-subtitle">Stay informed with the latest announcements, articles, and insights from the AICIS conference.</p>
    </div>
</div>

<div class="posts-container">
    <div class="container">
        @if($posts->count() > 0)
            <div class="news-grid">
                @foreach($posts as $post)
                <div class="news-card" style="background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.04); transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1); display: flex; flex-direction: column; height: 100%;" onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 20px 40px rgba(27,94,32,0.08)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.03)';">
                    <a href="{{ url('/post/' . $post->slug) }}" style="display: block; height: 220px; overflow: hidden; position: relative;">
                        @if($post->featured_image)
                            @php
                                $imgSrc = \Illuminate\Support\Str::startsWith($post->featured_image, ['http://', 'https://']) 
                                    ? $post->featured_image 
                                    : Storage::url($post->featured_image);
                            @endphp
                            <img src="{{ $imgSrc }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease;" onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'">
                        @else
                            <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%); display: flex; align-items: center; justify-content: center; color: #b0b8c4;">
                                <i class="fas fa-image" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                        <!-- Elegant Date Pill -->
                        <div style="position: absolute; top: 20px; left: 20px; background: rgba(255, 255, 255, 0.95); color: var(--primary-color); padding: 8px 16px; border-radius: 30px; font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); backdrop-filter: blur(5px);">
                            {{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('M d, Y') : $post->created_at->format('M d, Y') }}
                        </div>
                    </a>
                    <div style="padding: 30px; display: flex; flex-direction: column; flex-grow: 1;">
                        <div style="margin-bottom: 12px;">
                            <span style="color: var(--accent-color); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
                                {{ $post->category ? $post->category->name : 'Uncategorized' }}
                            </span>
                        </div>
                        <h4 style="font-size: 1.15rem; font-weight: 600; margin-bottom: 15px; line-height: 1.5; font-family: var(--font-heading);">
                            <a href="{{ url('/post/' . $post->slug) }}" style="color: #1a2530; text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='var(--primary-color)'" onmouseout="this.style.color='#1a2530'">{{ $post->title }}</a>
                        </h4>
                        <p style="color: #666; font-size: 0.9rem; line-height: 1.7; font-weight: 300; margin-bottom: 25px; flex-grow: 1;">
                            {{ Str::limit(strip_tags($post->content), 120) }}
                        </p>
                        <a href="{{ url('/post/' . $post->slug) }}" style="display: inline-flex; align-items: center; gap: 8px; color: var(--primary-color); font-weight: 500; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; text-decoration: none;" onmouseover="this.querySelector('i').style.transform='translateX(5px)'" onmouseout="this.querySelector('i').style.transform='translateX(0)'">
                            Read More <i class="fas fa-arrow-right" style="font-size: 0.75rem; transition: transform 0.3s;"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="pagination-wrapper">
                {{ $posts->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div style="text-align: center; padding: 100px 0;">
                <i class="fas fa-newspaper" style="font-size: 4rem; color: #cbd5e1; margin-bottom: 20px;"></i>
                <h3 style="color: #64748b; font-family: var(--font-heading);">No news available at the moment.</h3>
                <p style="color: #94a3b8;">Please check back later for updates.</p>
            </div>
        @endif
    </div>
</div>
@endsection
