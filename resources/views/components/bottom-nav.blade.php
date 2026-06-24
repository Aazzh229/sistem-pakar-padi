@props(['active' => 'beranda'])

<div class="fixed bottom-0 left-0 right-0 z-50 flex justify-center bg-transparent pointer-events-none">
    <div class="w-full max-w-md bg-white border-t border-neutral-100 flex justify-around items-center py-2 shadow-[0_-4px_12px_rgba(0,0,0,0.03)] pointer-events-auto">
        
        <!-- Beranda -->
        <a href="{{ route('beranda') }}" class="flex flex-col items-center flex-1 py-1 transition-all duration-200">
            @if($active === 'beranda')
                <div class="bg-[#E2F2EB] text-[#0A3D2A] px-5 py-1 rounded-full transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </div>
                <span class="text-[11px] font-semibold text-[#0A3D2A] mt-1">Beranda</span>
            @else
                <div class="text-[#8E9A94] p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </div>
                <span class="text-[11px] font-medium text-[#8E9A94] mt-1">Beranda</span>
            @endif
        </a>

        <!-- Ensiklopedia -->
        <a href="{{ route('ensiklopedia.index') }}" class="flex flex-col items-center flex-1 py-1 transition-all duration-200">
            @if($active === 'ensiklopedia')
                <div class="bg-[#E2F2EB] text-[#0A3D2A] px-5 py-1 rounded-full transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <span class="text-[11px] font-semibold text-[#0A3D2A] mt-1">Ensiklopedia</span>
            @else
                <div class="text-[#8E9A94] p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <span class="text-[11px] font-medium text-[#8E9A94] mt-1">Ensiklopedia</span>
            @endif
        </a>

        <!-- Deteksi -->
        <a href="{{ route('deteksi.index') }}" class="flex flex-col items-center flex-1 py-1 transition-all duration-200">
            @if($active === 'deteksi')
                <div class="bg-[#E2F2EB] text-[#0A3D2A] px-5 py-1 rounded-full transition-all duration-200">
                    <!-- Microscope Icon SVG -->
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 18h8" />
                        <path d="M3 22h18" />
                        <path d="M14 22a7 7 0 1 0-14 0" />
                        <path d="M9 14h2" />
                        <path d="M9 12a3 3 0 0 1-3-3V6a3 3 0 0 1 6 0v3a3 3 0 0 1-3 3Z" />
                        <path d="M12 6h4" />
                        <path d="M14 6v8a2 2 0 0 0 2 2h3a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-3a2 2 0 0 0-2 2Z" />
                    </svg>
                </div>
                <span class="text-[11px] font-semibold text-[#0A3D2A] mt-1">Deteksi</span>
            @else
                <div class="text-[#8E9A94] p-1">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 18h8" />
                        <path d="M3 22h18" />
                        <path d="M14 22a7 7 0 1 0-14 0" />
                        <path d="M9 14h2" />
                        <path d="M9 12a3 3 0 0 1-3-3V6a3 3 0 0 1 6 0v3a3 3 0 0 1-3 3Z" />
                        <path d="M12 6h4" />
                        <path d="M14 6v8a2 2 0 0 0 2 2h3a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-3a2 2 0 0 0-2 2Z" />
                    </svg>
                </div>
                <span class="text-[11px] font-medium text-[#8E9A94] mt-1">Deteksi</span>
            @endif
        </a>

    </div>
</div>
