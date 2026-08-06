<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plagiarism Scan Report - AICIS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        @media print {
            body { background-color: white; }
            .no-print { display: none !important; }
            .page-break { page-break-after: always; }
            @page { margin: 0; size: A4; }
            .print-container { margin: 0 !important; padding: 2rem !important; box-shadow: none !important; }
        }
    </style>
</head>
<body class="py-8">

    <div class="no-print max-w-4xl mx-auto mb-4 flex justify-between items-center px-4">
        <a href="/admin/plagiarism-checker" class="text-blue-600 hover:text-blue-800 font-medium flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Back to Dashboard
        </a>
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-medium shadow flex items-center gap-2 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0v3.396c0 .896.68 1.637 1.572 1.731a44.5 44.5 0 0 0 7.356 0c.892-.094 1.572-.835 1.572-1.731V6.149Z" />
            </svg>
            Print / Save as PDF
        </button>
    </div>

    <!-- A4 Container -->
    <div class="print-container max-w-4xl mx-auto bg-white min-h-[297mm] p-12 shadow-xl border border-gray-200 relative overflow-hidden">
        
        <!-- Header Background Pattern -->
        <div class="absolute top-0 left-0 w-full h-40 bg-gradient-to-r from-blue-900 via-blue-800 to-indigo-900 opacity-10 pointer-events-none" style="border-bottom-left-radius: 50%; border-bottom-right-radius: 20%;"></div>

        <!-- Header -->
        <div class="flex justify-between items-start border-b-2 border-gray-100 pb-8 relative z-10">
            <div>
                <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">AICIS</h1>
                <p class="text-blue-700 font-semibold mt-1 text-lg">Plagiarism & Originality Report</p>
                <p class="text-gray-500 text-sm mt-2">Generated on: {{ now()->format('F j, Y, g:i A') }}</p>
            </div>
            <div class="text-right">
                <div class="inline-block p-3 rounded-xl bg-gray-50 border border-gray-100">
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-1">Document ID</p>
                    <p class="text-gray-800 font-mono text-sm">{{ strtoupper(substr(md5(now()), 0, 12)) }}</p>
                </div>
            </div>
        </div>

        @php
            $score = $data['score'] ?? 0;
            $color = $score <= 15 ? 'emerald' : ($score <= 25 ? 'amber' : 'red');
            $status = $score <= 15 ? 'EXCELLENT' : ($score <= 25 ? 'ACCEPTABLE' : 'HIGH RISK');
            $statusDesc = $score <= 15 ? 'The document shows very high originality.' : ($score <= 25 ? 'The document is within acceptable similarity limits.' : 'The document exceeds the recommended similarity threshold.');
        @endphp

        <!-- Main Score Section -->
        <div class="mt-12 flex flex-col md:flex-row items-center justify-between gap-10">
            <div class="flex-1">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Analysis Summary</h2>
                <p class="text-gray-600 mb-6">This report provides a comprehensive originality analysis against billions of internet sources, academic journals, and publications.</p>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Author</p>
                        <p class="text-gray-800 font-medium">{{ $user->name ?? 'Unknown Author' }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Institution</p>
                        <p class="text-gray-800 font-medium">{{ $user->institution ?? 'Not specified' }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Words Scanned</p>
                        <p class="text-gray-800 font-medium">{{ number_format($data['words'] ?? 0) }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Processing Time</p>
                        <p class="text-gray-800 font-medium">{{ $data['duration'] ?? '0' }}s</p>
                    </div>
                </div>
            </div>

            <!-- Score Circle -->
            <div class="relative flex-shrink-0 flex flex-col items-center justify-center w-64 h-64">
                <svg class="absolute w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                    <path class="text-gray-100" stroke-width="3" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    <path class="text-{{ $color }}-500 transition-all duration-1000 ease-out" stroke-dasharray="{{ $score }}, 100" stroke-width="3" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                </svg>
                <div class="text-center z-10">
                    <span class="text-6xl font-black text-gray-800">{{ $score }}<span class="text-3xl">%</span></span>
                    <p class="text-gray-500 font-medium mt-1">Similarity Index</p>
                </div>
            </div>
        </div>

        <!-- Status Alert -->
        <div class="mt-8 p-4 rounded-xl flex items-center gap-4 border border-{{ $color }}-200 bg-{{ $color }}-50 text-{{ $color }}-800">
            <div class="p-2 bg-white rounded-full shadow-sm">
                @if($score <= 15)
                <svg class="w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                @elseif($score <= 25)
                <svg class="w-6 h-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                @else
                <svg class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                @endif
            </div>
            <div>
                <p class="font-bold tracking-wide">{{ $status }}</p>
                <p class="text-sm opacity-90">{{ $statusDesc }}</p>
            </div>
        </div>

        <!-- Details -->
        <div class="mt-12">
            <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-2 mb-6">Matched Sources ({{ $data['sources'] ?? 0 }})</h3>
            <div class="space-y-4">
                @for ($i = 1; $i <= ($data['sources'] ?? 3); $i++)
                    @php
                        $sourceScore = rand(1, max(1, floor($score / 1.5)));
                    @endphp
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="flex items-center gap-4">
                            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-700 font-bold text-sm">{{ $i }}</span>
                            <div>
                                <p class="font-semibold text-gray-800">Internet Source / Academic Database</p>
                                <p class="text-xs text-gray-500">Submitted to multiple cross-check databases</p>
                            </div>
                        </div>
                        <span class="font-bold text-gray-700">{{ $sourceScore }}%</span>
                    </div>
                @endfor
                @if(($data['sources'] ?? 0) == 0)
                    <div class="p-8 text-center text-gray-500 border-2 border-dashed border-gray-200 rounded-xl">
                        No matched sources found in the database.
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="absolute bottom-12 left-12 right-12 text-center border-t border-gray-200 pt-6">
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">AICIS Simulator Report • This document is auto-generated and does not require a signature.</p>
        </div>
    </div>

    <script>
        // Auto-print prompt when page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
