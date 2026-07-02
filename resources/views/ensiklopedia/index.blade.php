@extends('layouts.app')

@section('title', 'Ensiklopedia Hama & Penyakit Padi')

@section('content')
<div class="flex flex-col w-full text-neutral-800">
    <!-- Header -->
    <div class="bg-gradient-to-b from-[#0A3D2A] to-[#1C6646] px-6 pt-8 pb-8 rounded-b-[2rem] text-white">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <a href="{{ route('beranda') }}" class="text-white/80 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h1 class="text-2xl font-bold">Ensiklopedia</h1>
            </div>
            
            @if(Auth::check() && (Auth::user()->role === 'pakar' || Auth::user()->role === 'admin'))
                <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('pakar.dashboard') }}" 
                   class="bg-white/20 hover:bg-white/30 text-white text-[10px] font-bold px-3 py-1.5 rounded-full transition">
                    Dashboard
                </a>
            @endif
        </div>
        <p class="text-white/80 text-xs mb-4 font-light">
            Daftar lengkap penyakit dan hama padi beserta cara pencegahan dan solusinya.
        </p>

        <!-- Search Form with Live Suggestions -->
        <div class="relative w-full" id="search-container">
            <form action="{{ route('ensiklopedia.index') }}" method="GET" id="search-form" autocomplete="off">
                @if($type)
                    <input type="hidden" name="type" value="{{ $type }}">
                @endif
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 z-10">
                    <svg class="w-5 h-5 text-white/60" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" 
                       name="search" 
                       id="search-input"
                       value="{{ $search }}"
                       placeholder="Cari penyakit atau hama..." 
                       class="w-full bg-white/15 border border-white/10 text-white placeholder-white/60 text-sm rounded-full py-3.5 pl-11 pr-10 focus:outline-none focus:ring-2 focus:ring-white/20 focus:bg-white/20 transition shadow-sm"
                >
                <!-- Clear Button -->
                <button type="button" id="search-clear" 
                        class="absolute inset-y-0 right-0 flex items-center pr-4 z-10 {{ $search ? '' : 'hidden' }}"
                        onclick="document.getElementById('search-input').value=''; this.classList.add('hidden'); document.getElementById('suggestions-dropdown').classList.add('hidden');">
                    <svg class="w-4 h-4 text-white/50 hover:text-white/80 transition" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </form>

            <!-- Suggestions Dropdown -->
            <div id="suggestions-dropdown" 
                 class="hidden absolute left-0 right-0 top-full mt-2 bg-white rounded-2xl shadow-xl border border-neutral-100 overflow-hidden z-50 max-h-[320px] overflow-y-auto">
                <div id="suggestions-list"></div>
                <div id="suggestions-loading" class="hidden p-4 text-center">
                    <div class="inline-flex items-center gap-2 text-xs text-neutral-400">
                        <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Mencari...
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Tabs -->
    <div class="flex gap-2 px-6 mt-6">
        <a href="{{ route('ensiklopedia.index', ['search' => $search]) }}" 
           class="px-4 py-1.5 rounded-full text-xs font-semibold tracking-wide border transition {{ !$type ? 'bg-[#0E4E37] text-white border-[#0E4E37]' : 'bg-white text-neutral-500 border-neutral-200' }}">
            Semua
        </a>
        <a href="{{ route('ensiklopedia.index', ['type' => 'penyakit', 'search' => $search]) }}" 
           class="px-4 py-1.5 rounded-full text-xs font-semibold tracking-wide border transition {{ $type === 'penyakit' ? 'bg-[#0E4E37] text-white border-[#0E4E37]' : 'bg-white text-neutral-500 border-neutral-200' }}">
            Penyakit
        </a>
        <a href="{{ route('ensiklopedia.index', ['type' => 'hama', 'search' => $search]) }}" 
           class="px-4 py-1.5 rounded-full text-xs font-semibold tracking-wide border transition {{ $type === 'hama' ? 'bg-[#0E4E37] text-white border-[#0E4E37]' : 'bg-white text-neutral-500 border-neutral-200' }}">
            Hama
        </a>
    </div>

    <!-- Found Item Counter -->
    <div class="px-6 mt-4">
        <p class="text-xs text-neutral-400 font-bold uppercase tracking-wider">
            {{ $count }} item ditemukan
        </p>
    </div>

    <!-- 2-Column Grid of Items -->
    <div class="grid grid-cols-2 gap-4 px-6 mt-4 pb-6">
        @forelse($items as $item)
            @php
                $slug = \Illuminate\Support\Str::slug($item->nama);
                $isPenyakit = $item->jenis === 'penyakit';
            @endphp
            <a href="{{ route('ensiklopedia.show', $slug) }}" 
               class="bg-white border border-neutral-100 rounded-2xl overflow-hidden shadow-sm flex flex-col hover:shadow-md transition">
                
                <!-- Image Box -->
                <div class="h-[105px] w-full overflow-hidden bg-neutral-100 relative">
                    @if(!$item->gambar || $item->gambar === 'placeholder')
                        <div class="w-full h-full bg-[#E8F3ED] flex flex-col items-center justify-center p-4 text-center">
                            <svg class="w-6 h-6 text-[#A3B3AB] mb-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"></path>
                            </svg>
                            <span class="text-[9px] text-[#A3B3AB] leading-none font-bold">{{ $item->nama }}</span>
                        </div>
                    @else
                        <img src="{{ $item->gambar }}" alt="{{ $item->nama }}" class="w-full h-full object-cover">
                    @endif
                </div>

                <!-- Text Content Info -->
                <div class="p-3.5 flex flex-col flex-grow justify-between min-h-[110px]">
                    <div>
                        <!-- Category Badge -->
                        @if($isPenyakit)
                            <span class="inline-block bg-[#E2F2EB] text-[#0A3D2A] text-[9px] font-bold px-2 py-0.5 rounded-full mb-1.5">Penyakit</span>
                        @else
                            <span class="inline-block bg-[#FDECE8] text-[#D85C30] text-[9px] font-bold px-2 py-0.5 rounded-full mb-1.5">Hama</span>
                        @endif

                        <h3 class="font-bold text-neutral-800 text-sm leading-tight">{{ $item->nama }}</h3>
                        
                        @if($item->nama_latin)
                            <span class="text-[10px] text-neutral-400 italic block mt-0.5 leading-none">{{ $item->nama_latin }}</span>
                        @endif
                    </div>
                    
                    <p class="text-[10px] text-neutral-500 line-clamp-2 mt-2 leading-relaxed font-light">
                        {{ $item->deskripsi }}
                    </p>
                </div>
            </a>
        @empty
            <div class="col-span-2 bg-white border border-neutral-100 p-8 rounded-2xl text-center shadow-sm">
                <svg class="w-10 h-10 text-neutral-300 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.637 10.637z"></path>
                </svg>
                <p class="text-xs text-neutral-500 font-medium">Tidak ada hasil pencarian ditemukan.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const dropdown = document.getElementById('suggestions-dropdown');
    const suggestionsList = document.getElementById('suggestions-list');
    const loadingEl = document.getElementById('suggestions-loading');
    const clearBtn = document.getElementById('search-clear');
    const container = document.getElementById('search-container');
    
    let debounceTimer = null;
    let activeIndex = -1;
    let currentSuggestions = [];

    const suggestionsUrl = @json(route('ensiklopedia.suggestions'));
    const showUrlTemplate = "{{ route('ensiklopedia.show', '__SLUG__') }}";

    function buildSuggestionItem(item, index) {
        const isPenyakit = item.jenis === 'penyakit';
        const badgeBg = isPenyakit ? '#E2F2EB' : '#FDECE8';
        const badgeColor = isPenyakit ? '#0A3D2A' : '#D85C30';
        const badgeText = isPenyakit ? 'Penyakit' : 'Hama';
        const latin = item.nama_latin ? '<span style="font-size:10px;color:#9ca3af;font-style:italic;margin-left:6px;">' + item.nama_latin + '</span>' : '';
        const url = showUrlTemplate.replace('__SLUG__', item.slug);
        
        return '<a href="' + url + '" ' +
               'class="suggestion-item flex items-center gap-3 px-4 py-3 hover:bg-neutral-50 transition-colors cursor-pointer border-b border-neutral-50 last:border-b-0" ' +
               'data-index="' + index + '">' +
               '<div class="flex-shrink-0">' +
               '<svg class="w-4 h-4 text-neutral-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>' +
               '</div>' +
               '<div class="flex-grow min-w-0">' +
               '<div class="flex items-center gap-1.5 flex-wrap">' +
               '<span style="display:inline-block;background:' + badgeBg + ';color:' + badgeColor + ';font-size:9px;font-weight:700;padding:2px 6px;border-radius:9999px;">' + badgeText + '</span>' +
               '<span class="text-sm font-semibold text-neutral-800 truncate">' + item.nama + '</span>' +
               latin +
               '</div></div>' +
               '<svg class="w-3.5 h-3.5 text-neutral-300 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>' +
               '</a>';
    }

    function showSuggestions(items) {
        currentSuggestions = items;
        activeIndex = -1;

        if (items.length === 0) {
            suggestionsList.innerHTML = '<div class="px-4 py-4 text-center"><p class="text-xs text-neutral-400 font-medium">Tidak ditemukan hasil untuk pencarian ini</p></div>';
        } else {
            let html = '';
            for (let i = 0; i < items.length; i++) {
                html += buildSuggestionItem(items[i], i);
            }
            suggestionsList.innerHTML = html;
        }

        loadingEl.classList.add('hidden');
        dropdown.classList.remove('hidden');
    }

    function hideSuggestions() {
        setTimeout(function() {
            dropdown.classList.add('hidden');
        }, 200);
    }

    function highlightItem(index) {
        const items = dropdown.querySelectorAll('.suggestion-item');
        for (let i = 0; i < items.length; i++) {
            items[i].classList.remove('bg-neutral-50');
        }
        
        if (index >= 0 && index < items.length) {
            items[index].classList.add('bg-neutral-50');
            items[index].scrollIntoView({ block: 'nearest' });
        }
        activeIndex = index;
    }

    function fetchSuggestions(query) {
        loadingEl.classList.remove('hidden');
        suggestionsList.innerHTML = '';
        dropdown.classList.remove('hidden');

        fetch(suggestionsUrl + '?q=' + encodeURIComponent(query), {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(res) { return res.json(); })
        .then(function(data) { showSuggestions(data); })
        .catch(function() {
            loadingEl.classList.add('hidden');
            dropdown.classList.add('hidden');
        });
    }

    searchInput.addEventListener('input', function() {
        const val = this.value.trim();
        
        if (val.length > 0) {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }

        clearTimeout(debounceTimer);

        if (val.length < 1) {
            dropdown.classList.add('hidden');
            return;
        }

        debounceTimer = setTimeout(function() { fetchSuggestions(val); }, 250);
    });

    searchInput.addEventListener('focus', function() {
        if (this.value.trim().length >= 1 && currentSuggestions.length > 0) {
            dropdown.classList.remove('hidden');
        }
    });

    searchInput.addEventListener('keydown', function(e) {
        const items = dropdown.querySelectorAll('.suggestion-item');
        if (!items.length) return;

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            highlightItem(Math.min(activeIndex + 1, items.length - 1));
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            highlightItem(Math.max(activeIndex - 1, 0));
        } else if (e.key === 'Enter' && activeIndex >= 0) {
            e.preventDefault();
            items[activeIndex].click();
        } else if (e.key === 'Escape') {
            hideSuggestions();
            searchInput.blur();
        }
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!container.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
});
</script>
@endsection

@section('navigation')
    <x-bottom-nav active="ensiklopedia" />
@endsection
