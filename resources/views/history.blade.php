@extends('layouts.app')

@section('content')
<section class="section">
    <div class="container">
        <h1 class="section-title">Conference History</h1>
        
        <div style="max-width: 800px; margin: 0 auto; position: relative; padding-left: 30px; border-left: 2px solid var(--secondary-color);">
            
            <div style="margin-bottom: 40px; position: relative;">
                <div style="position: absolute; left: -39px; top: 0; width: 16px; height: 16px; background: var(--white); border: 4px solid var(--primary-color); border-radius: 50%;"></div>
                <h3 style="color: var(--primary-color); margin-bottom: 10px; font-size: 1.4rem;">2001 - The Beginning (ACIS)</h3>
                <p>The Annual Conference on Islamic Studies (ACIS) was established, focusing primarily on traditional religious studies and providing a vital platform for scholars to engage in theological discourse.</p>
            </div>
            
            <div style="margin-bottom: 40px; position: relative;">
                <div style="position: absolute; left: -39px; top: 0; width: 16px; height: 16px; background: var(--white); border: 4px solid var(--primary-color); border-radius: 50%;"></div>
                <h3 style="color: var(--primary-color); margin-bottom: 10px; font-size: 1.4rem;">2012 - Rebranding to AICIS</h3>
                <p>Embracing a broader global vision, the conference was rebranded as AICIS (Annual International Conference on Islamic Studies), fostering international dialogue and expanding its reach.</p>
            </div>

            <div style="position: relative;">
                <div style="position: absolute; left: -39px; top: 0; width: 16px; height: 16px; background: var(--white); border: 4px solid var(--accent-color); border-radius: 50%;"></div>
                <h3 style="color: var(--primary-color); margin-bottom: 10px; font-size: 1.4rem;">2026 - UIN Siber Syekh Nurjati</h3>
                <p>Continuing the legacy, AICIS 2026 is proudly hosted by UIN Siber Syekh Nurjati Cirebon, pushing the boundaries of digital and global Islamic scholarship.</p>
            </div>

        </div>
    </div>
</section>
@endsection
