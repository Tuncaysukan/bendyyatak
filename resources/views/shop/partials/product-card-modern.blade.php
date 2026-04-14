<div class="product-card group relative bg-white border border-gray-100 rounded-[2.5rem] overflow-hidden transition-all duration-500 hover:shadow-[0_22px_50px_rgba(0,0,0,0.1)] hover:-translate-y-2">
    <div class="relative aspect-square bg-[#fcfcfc] overflow-hidden m-3 rounded-[2rem]">
        {{-- Badges --}}
        <div class="absolute top-4 left-4 z-10 flex flex-col gap-2">
            @if($product->is_featured || $product->is_new_arrival)
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white text-[9px] font-black tracking-[0.2em] rounded-full shadow-lg transition-transform group-hover:scale-105">
                    <span class="w-1 h-1 bg-accent rounded-full animate-ping"></span> ÖNE ÇIKAN
                </div>
            @endif
            @if($product->is_bestseller)
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-accent text-white text-[9px] font-black tracking-[0.2em] rounded-full shadow-lg">
                    POPÜLER
                </div>
            @endif
        </div>

        {{-- Actions --}}
        <div class="absolute bottom-4 right-4 z-10 flex flex-col gap-3 translate-x-12 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all duration-300">
            <button onclick="toggleWishlist({{ $product->id }}, this)" class="w-11 h-11 bg-white rounded-2xl flex items-center justify-center text-gray-400 hover:text-red-500 hover:shadow-xl transition-all shadow-sm">
                <i class="far fa-heart text-lg"></i>
            </button>
            <button onclick="toggleCompare({{ $product->id }}, this)" class="w-11 h-11 bg-white rounded-2xl flex items-center justify-center text-gray-400 hover:text-primary hover:shadow-xl transition-all shadow-sm">
                <i class="fas fa-shuffle text-sm"></i>
            </button>
        </div>

        {{-- Image --}}
        <a href="{{ route('product.show', $product) }}" class="block w-full h-full p-8">
            <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="w-full h-full object-contain transition-transform duration-700 group-hover:scale-110">
        </a>
    </div>

    <div class="px-8 pb-8 pt-4">
        <a href="{{ route('product.show', $product) }}" class="block">
            <span class="text-[10px] font-black text-gray-300 uppercase tracking-[0.2em] mb-2 block">{{ $product->category?->name ?? 'Yatak' }}</span>
            <h3 class="font-black text-primary text-xl mb-6 line-clamp-2 group-hover:text-accent transition-colors min-h-[3.5rem]">
                {{ $product->name }}
            </h3>
        </a>
        
        <div class="flex items-center justify-between border-t border-gray-50 pt-6">
            <div class="flex flex-col">
                @if($product->compare_at_price > $product->price)
                    <span class="text-[10px] text-gray-300 font-bold line-through mb-1">{{ number_format($product->compare_at_price, 0, ',', '.') }} TL</span>
                @endif
                <div class="flex items-baseline gap-1">
                    <span class="text-2xl font-black text-primary">{{ number_format($product->price, 0, ',', '.') }}</span>
                    <span class="text-xs font-black text-primary/40 uppercase">TL</span>
                </div>
            </div>
            
            <button onclick="addToCart({{ $product->id }}, null, this)" class="flex items-center gap-3 px-6 py-4 bg-primary text-white rounded-2xl text-[11px] font-black tracking-widest hover:bg-black transition-all shadow-lg shadow-primary/10 active:scale-95">
                <i class="fas fa-plus"></i> <span class="hidden sm:inline">EKLE</span>
            </button>
        </div>
    </div>
</div>
