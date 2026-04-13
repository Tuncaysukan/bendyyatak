@extends('layouts.app')

@section('seo_title', \App\Models\Setting::get('site_name', 'BendyyYatak') . ' — Kaliteli Yatak & Baza')
@section('seo_description', \App\Models\Setting::get('site_description', 'Türkiye\'nin en kaliteli yatak ve baza markası.'))

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    .hero-swiper .swiper-button-next, .hero-swiper .swiper-button-prev {
        background: white; width: 44px; height: 44px; border-radius: 50%; color: #1a1a2e; box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .hero-swiper .swiper-pagination-bullet-active { background: #1a1a2e !important; }
    
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .animate-float { animation: float 4s ease-in-out infinite; }
</style>
@endpush

@section('content')

<!-- ─── SLIDER SECTION (Full Width Background) ─── -->
<section class="relative bg-[#F3F4F6] overflow-hidden">
    <div class="swiper hero-swiper">
        <div class="swiper-wrapper">
            @forelse($sliders as $slider)
            <div class="swiper-slide !flex items-center">
                <div class="container mx-auto px-4 grid lg:grid-cols-2 gap-8 items-center py-12 lg:py-24">
                    <div class="order-2 lg:order-1 flex justify-center">
                        <img src="{{ Storage::url($slider->image) }}" alt="{{ $slider->title }}" class="max-w-full drop-shadow-2xl animate-float">
                    </div>
                    <div class="order-1 lg:order-2 space-y-6">
                        @if($slider->subtitle)
                            <span class="inline-block px-4 py-1.5 bg-[#1a1a2e] text-white text-xs font-bold tracking-widest uppercase rounded-full">{{ $slider->subtitle }}</span>
                        @endif
                        <h1 class="text-4xl lg:text-7xl font-black text-[#1a1a2e] leading-tight">
                            {!! nl2br(e($slider->title)) !!}
                        </h1>
                        <p class="text-gray-600 text-lg leading-relaxed max-w-lg">
                            İleri teknoloji ile donatılmış uyku sistemleri ile sabahları tazelenmiş uyanın.
                        </p>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4">
                            <div class="flex items-start gap-3">
                                <div class="w-12 h-12 flex-shrink-0 bg-white rounded-xl flex items-center justify-center border border-gray-100 shadow-sm"><i class="fas fa-wind text-[#1a1a2e]"></i></div>
                                <div><h4 class="font-bold text-sm text-[#1a1a2e]">NANOTEX</h4><p class="text-xs text-gray-500">Mikro kapsüller içeren kumaş sistemi.</p></div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="w-12 h-12 flex-shrink-0 bg-white rounded-xl flex items-center justify-center border border-gray-100 shadow-sm"><i class="fas fa-shield-virus text-[#1a1a2e]"></i></div>
                                <div><h4 class="font-bold text-sm text-[#1a1a2e]">HyCare</h4><p class="text-xs text-gray-500">Anti bakteriyel kalıcı tazelik sunar.</p></div>
                            </div>
                        </div>

                        <div class="pt-6">
                            @if($slider->button_text && $slider->button_link)
                            <a href="{{ $slider->button_link }}" class="inline-flex items-center px-8 py-4 bg-[#1a1a2e] text-white font-bold rounded-xl hover:bg-black transition-all">
                                {{ $slider->button_text }} <i class="fas fa-arrow-right ml-3"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            @endforelse
        </div>
        <div class="swiper-pagination"></div>
    </div>
</section>

<!-- ─── KATEGORİLER SECTION ─── -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-black text-[#1a1a2e] mb-2 uppercase">Kategoriler</h2>
            <div class="w-20 h-1 bg-accent mx-auto"></div>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @php $homeCats = $categories->where('show_on_slider', true)->take(4); if($homeCats->isEmpty()) $homeCats = $categories->take(4); @endphp
            @foreach($homeCats as $cat)
            <a href="{{ route('category.show', $cat) }}" class="group block text-center space-y-4">
                <div class="relative aspect-square overflow-hidden rounded-[2rem] bg-gray-50 border border-gray-100 transition-all duration-300 group-hover:shadow-xl group-hover:-translate-y-2">
                    @if($cat->image)
                        <img src="{{ Storage::url($cat->image) }}" alt="{{ $cat->name }}" class="w-full h-full object-contain p-6 transition-transform duration-500 group-hover:scale-110">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300"><i class="fas fa-image text-4xl"></i></div>
                    @endif
                </div>
                <h3 class="font-bold text-sm text-[#1a1a2e] uppercase tracking-widest">{{ $cat->name }}</h3>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- ─── EN YENİLER SECTION ─── -->
<section class="py-20 bg-gray-50 overflow-hidden">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl font-black text-[#1a1a2e] mb-2">En Yeniler</h2>
                <p class="text-gray-500 uppercase text-xs tracking-widest font-bold">2026 Yeni modelleri keşfedin</p>
            </div>
            <div class="flex gap-2">
                <div class="swiper-prev-new cursor-pointer w-10 h-10 bg-white border border-gray-100 rounded-full flex items-center justify-center hover:bg-black hover:text-white transition-all"><i class="fas fa-chevron-left text-xs"></i></div>
                <div class="swiper-next-new cursor-pointer w-10 h-10 bg-white border border-gray-100 rounded-full flex items-center justify-center hover:bg-black hover:text-white transition-all"><i class="fas fa-chevron-right text-xs"></i></div>
            </div>
        </div>

        <div class="swiper products-swiper !overflow-visible">
            <div class="swiper-wrapper">
                @php $newItems = \App\Models\Product::where('is_active', true)->where('is_new_arrival', true)->latest()->take(8)->get(); if($newItems->isEmpty()) $newItems = $newProducts; @endphp
                @foreach($newItems as $product)
                <div class="swiper-slide">
                    @include('shop.partials.product-card-modern', ['product' => $product])
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- ─── EN ÇOK SATANLAR SECTION ─── -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-12 items-center">
            <div class="lg:w-1/4 space-y-6">
                <h2 class="text-4xl font-black text-[#1a1a2e]">En Çok Satanlar</h2>
                <p class="text-gray-500">En sevilen modellerimizle konforu yakalayın.</p>
                <a href="{{ route('category.show', $categories->first()) }}" class="inline-flex items-center font-bold text-[#1a1a2e] border-b-2 border-accent pb-1">TÜMÜNÜ GÖR</a>
            </div>
            <div class="lg:w-3/4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @php $bestItems = \App\Models\Product::where('is_active', true)->where('is_bestseller', true)->take(3)->get(); if($bestItems->isEmpty()) $bestItems = $featuredProducts->take(3); @endphp
                    @foreach($bestItems as $product)
                        @include('shop.partials.product-card-modern', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ─── BLOG SECTION ─── -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-black text-[#1a1a2e] mb-4">Blog & Makale</h2>
            <div class="w-16 h-1 bg-black mx-auto"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($latestPosts as $post)
            <a href="{{ route('blog.show', $post) }}" class="group bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm transition-all hover:shadow-xl">
                <div class="aspect-video overflow-hidden rounded-2xl mb-6">
                    @if($post->cover_image)
                        <img src="{{ Storage::url($post->cover_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                    @endif
                </div>
                <div class="space-y-4">
                    <span class="text-[10px] font-bold text-accent uppercase tracking-widest">{{ $post->created_at->format('d/m/Y') }}</span>
                    <h3 class="font-bold text-lg text-[#1a1a2e] line-clamp-2">{{ $post->title }}</h3>
                    <div class="pt-2 text-xs font-bold text-[#1a1a2e] flex items-center gap-2">OKUMAYA DEVAM ET <i class="fas fa-arrow-right text-[8px]"></i></div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new Swiper('.hero-swiper', {
            loop: true,
            autoplay: { delay: 5000 },
            pagination: { el: '.swiper-pagination', clickable: true },
        });

        new Swiper('.products-swiper', {
            slidesPerView: 1.2,
            spaceBetween: 20,
            loop: true,
            navigation: { nextEl: '.swiper-next-new', prevEl: '.swiper-prev-new' },
            breakpoints: {
                640: { slidesPerView: 2.2 },
                1024: { slidesPerView: 4 }
            }
        });
    });
</script>
@endpush
