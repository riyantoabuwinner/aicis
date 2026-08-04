@extends('layouts.app')

@section('content')
<section class="section" style="background-color: var(--bg-light);">
    <div class="container">
        <h1 class="section-title">Contact Us</h1>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 50px; background: var(--white); padding: 40px; border-radius: 8px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border: 1px solid var(--border-color); margin-bottom: 50px;">
            
            @php
                $setting = \App\Models\Setting::first();
            @endphp
            <!-- Contact Info -->
            <div>
                <h3 style="font-size: 1.8rem; margin-bottom: 25px; color: var(--primary-color);">Conference Secretariat</h3>
                
                <div style="display: flex; gap: 20px; margin-bottom: 25px;">
                    <div style="font-size: 1.5rem; color: var(--secondary-color);">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <h4 style="font-size: 1.1rem; margin-bottom: 5px;">Venue</h4>
                        <p style="color: var(--text-light); line-height: 1.6;">{!! nl2br(e($setting?->address ?? "UIN Siber Syekh Nurjati Cirebon\nJl. Perjuangan By Pass Sunyaragi\nCirebon, West Java, Indonesia")) !!}</p>
                    </div>
                </div>

                <div style="display: flex; gap: 20px; margin-bottom: 25px;">
                    <div style="font-size: 1.5rem; color: var(--secondary-color);">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <h4 style="font-size: 1.1rem; margin-bottom: 5px;">Email</h4>
                        <p style="color: var(--text-light);">{{ $setting?->email ?? 'aicis2026@syekhnurjati.ac.id' }}</p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 20px;">
                    <div style="font-size: 1.5rem; color: var(--secondary-color);">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div>
                        <h4 style="font-size: 1.1rem; margin-bottom: 5px;">Phone / WhatsApp</h4>
                        <p style="color: var(--text-light);">{{ $setting?->phone ?? '+62 851-1702-2042' }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div>
                <h3 style="font-size: 1.8rem; margin-bottom: 25px; color: var(--primary-color);">Send a Message</h3>
                
                <form action="{{ route('contact.send') }}" method="POST">
                    @csrf
                    
                    @if(session('success'))
                        <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="form-group" style="margin-bottom: 15px;">
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Your Name" value="{{ old('name') }}" required>
                        @error('name')
                            <small style="color: red;">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Your Email" value="{{ old('email') }}" required>
                        @error('email')
                            <small style="color: red;">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <textarea name="message" class="form-control @error('message') is-invalid @enderror" placeholder="Your Message" rows="5" style="resize: none;" required>{{ old('message') }}</textarea>
                        @error('message')
                            <small style="color: red;">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <!-- CAPTCHA -->
                    <div class="form-group" style="margin-bottom: 20px;">
                        <div style="margin-bottom: 10px;">
                            {!! captcha_img('flat') !!}
                        </div>
                        <input type="text" name="captcha" class="form-control @error('captcha') is-invalid @enderror" placeholder="Enter CAPTCHA Code" required>
                        @error('captcha')
                            <small style="color: red;">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">Send Message</button>
                </form>
            </div>
            
        </div>

        @if($setting?->google_maps_url)
        <div class="map-container" style="border-radius: 8px; overflow: hidden; box-shadow: 0 5px 20px rgba(0,0,0,0.05); height: 450px; background: #eee;">
            {!! $setting->google_maps_url !!}
        </div>
        @endif
        <style>
            .map-container iframe {
                width: 100% !important;
                height: 100% !important;
                border: 0 !important;
            }
            .form-control {
                width: 100%;
                padding: 12px 15px;
                font-family: var(--font-body);
                font-size: 0.9rem;
                color: var(--text-dark);
                background-color: #f9f9f9;
                border: 1px solid var(--border-color);
                border-radius: 6px;
                transition: all 0.3s;
                outline: none;
                box-sizing: border-box;
            }
            .form-control:focus {
                border-color: var(--primary-color);
                background-color: #fff;
                box-shadow: 0 0 0 3px rgba(30, 58, 95, 0.1);
            }
        </style>
    </div>
</section>
@endsection
