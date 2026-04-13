@extends('layouts.admin')
@section('title', 'Ürün Düzenle')
@section('topbar-title', 'Ürün Düzenle')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Ürün Düzenle</div>
        <div class="page-subtitle">{{ $product->name }}</div>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('product.show', $product) }}" target="_blank" class="btn btn-outline"><i class="fas fa-eye"></i> Görüntüle</a>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Geri</a>
    </div>
</div>

<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div class="grid grid-2">
        <div style="display:flex;flex-direction:column;gap:20px;">
            {{-- Temel Bilgiler --}}
            <div class="card">
                <div class="card-header"><span class="card-title">Temel Bilgiler</span></div>
                <div class="form-group">
                    <label class="form-label">Ürün Adı *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-control">
                        <option value="">— Kategori Seçin —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">SKU</label>
                    <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}" placeholder="BY-0001">
                </div>
                <div class="form-group">
                    <label class="form-label">Kısa Açıklama</label>
                    <textarea name="short_description" class="form-control" rows="3">{{ old('short_description', $product->short_description) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Detaylı Açıklama</label>
                    <textarea name="description" class="form-control" rows="7">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>

            {{-- Fiyatlandırma --}}
            <div class="card">
                <div class="card-header"><span class="card-title">Fiyatlandırma</span></div>
                <div class="grid grid-2">
                    <div class="form-group">
                        <label class="form-label">Satış Fiyatı (₺) *</label>
                        <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}" min="0" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Karşılaştırma Fiyatı (₺)</label>
                        <input type="number" name="compare_at_price" class="form-control" value="{{ old('compare_at_price', $product->compare_at_price) }}" min="0" step="0.01">
                        <div class="form-hint">Üstü çizili eski fiyat</div>
                    </div>
                </div>
            </div>

            {{-- Sertlik --}}
            <div class="card">
                <div class="card-header"><span class="card-title">Yatak Sertlik Skalası</span></div>
                <div class="form-group">
                    <label class="form-label">Sertlik Düzeyi (1–10): <strong id="firmnessVal">{{ old('firmness_level', $product->firmness_level ?? 5) }}</strong></label>
                    <input type="range" name="firmness_level" min="1" max="10" value="{{ old('firmness_level', $product->firmness_level ?? 5) }}"
                           class="form-control" style="padding:0;" oninput="document.getElementById('firmnessVal').textContent=this.value">
                    <div style="display:flex;justify-content:space-between;font-size:11px;color:var(--text-muted);margin-top:4px;">
                        <span>Çok Yumuşak</span><span>Çok Sert</span>
                    </div>
                </div>
            </div>

            {{-- SEO --}}
            <div class="card">
                <div class="card-header"><span class="card-title">SEO Ayarları</span></div>
                <div class="form-group">
                    <label class="form-label">SEO Başlığı</label>
                    <input type="text" name="seo_title" class="form-control" value="{{ old('seo_title', $product->seo_title) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">SEO Açıklaması</label>
                    <textarea name="seo_description" class="form-control" rows="3">{{ old('seo_description', $product->seo_description) }}</textarea>
                </div>
            </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:20px;">
            {{-- Durum --}}
            <div class="card">
                <div class="card-header"><span class="card-title">Yayın Durumu</span></div>
                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="is_active" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        Yayında (aktif)
                    </label>
                </div>
                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="is_featured" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                        Öne Çıkan Ürün
                    </label>
                </div>
                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="is_bestseller" {{ old('is_bestseller', $product->is_bestseller) ? 'checked' : '' }}>
                        En Çok Satan Ürün
                    </label>
                </div>
                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="is_new_arrival" {{ old('is_new_arrival', $product->is_new_arrival) ? 'checked' : '' }}>
                        En Yeni Ürün
                    </label>
                </div>
                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="show_on_slider" {{ old('show_on_slider', $product->show_on_slider) ? 'checked' : '' }}>
                        Slider'da Göster
                    </label>
                </div>
                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="is_comparable" {{ old('is_comparable', $product->is_comparable) ? 'checked' : '' }}>
                        Karşılaştırmaya Eklenebilir
                    </label>
                </div>
            </div>

            {{-- Görseller --}}
            <div class="card">
                <div class="card-header"><span class="card-title">Ürün Görselleri</span></div>
                @if($product->images->isNotEmpty())
                <div style="display:flex;flex-wrap:wrap;gap:12px;margin-bottom:14px;">
                    @foreach($product->images as $img)
                    <div style="position:relative;display:flex;flex-direction:column;align-items:center;">
                        <div style="position:relative;">
                            <img src="{{ asset('storage/'.$img->image) }}" style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:{{ $img->is_primary ? '2px solid var(--primary)' : '1px solid var(--border)' }}">
                            @if($img->is_primary)
                                <span style="position:absolute;bottom:2px;right:2px;background:var(--primary);color:#fff;font-size:9px;padding:1px 5px;border-radius:4px;">Ana</span>
                            @endif
                        </div>
                        <label style="font-size:11px;margin-top:6px;cursor:pointer;color:var(--danger);display:flex;align-items:center;gap:4px;">
                            <input type="checkbox" name="delete_images[]" value="{{ $img->id }}"> Sil
                        </label>
                    </div>
                    @endforeach
                </div>
                @endif
                <div class="form-group">
                    <label class="form-label">Yeni Görsel Ekle</label>
                    <input type="file" name="images[]" multiple class="form-control" accept="image/*">
                    <div class="form-hint">Birden fazla görsel seçebilirsiniz. Mevcut görsellerin üstüne eklenir.</div>
                </div>
            </div>

            {{-- Varyantlar --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Varyantlar (Boyutlar)</span>
                    <button type="button" class="btn btn-outline btn-sm" onclick="addVariantRow()"><i class="fas fa-plus"></i> Ekle</button>
                </div>
                <table style="width:100%;border-collapse:collapse;font-size:13px;">
                    <thead>
                        <tr style="background:#f9fafb;">
                            <th style="padding:8px 10px;text-align:left;border-bottom:2px solid var(--border);font-weight:600;color:var(--text-muted);font-size:11.5px;text-transform:uppercase;">Ölçü / Ad</th>
                            <th style="padding:8px 10px;text-align:left;border-bottom:2px solid var(--border);font-weight:600;color:var(--text-muted);font-size:11.5px;text-transform:uppercase;">Ek Fiyat (₺)</th>
                            <th style="padding:8px 10px;text-align:left;border-bottom:2px solid var(--border);font-weight:600;color:var(--text-muted);font-size:11.5px;text-transform:uppercase;">Stok</th>
                            <th style="width:36px;border-bottom:2px solid var(--border);"></th>
                        </tr>
                    </thead>
                    <tbody id="variants-container">
                        @foreach($product->variants as $v)
                        <tr class="variant-row">
                            <td style="padding:6px 10px;">
                                <input type="hidden" name="variants[{{ $loop->index }}][id]" value="{{ $v->id }}">
                                <input type="text" name="variants[{{ $loop->index }}][name]" class="form-control" placeholder="80×200 cm" value="{{ $v->name }}">
                            </td>
                            <td style="padding:6px 10px;">
                                <input type="number" name="variants[{{ $loop->index }}][extra_price]" class="form-control" placeholder="Ek Fiyat" value="{{ $v->extra_price }}" min="0" step="0.01">
                            </td>
                            <td style="padding:6px 10px;">
                                <input type="number" name="variants[{{ $loop->index }}][stock]" class="form-control" placeholder="Stok" value="{{ $v->stock }}" min="0">
                            </td>
                            <td style="padding:6px;">
                                <button type="button" onclick="this.closest('tr').remove()" style="background:var(--danger);color:#fff;border:none;border-radius:7px;cursor:pointer;width:32px;height:32px;font-size:14px;">×</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div style="margin-top:20px;display:flex;justify-content:flex-end;gap:12px;">
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline">İptal</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Güncelle</button>
    </div>
</form>
@endsection

@push('scripts')
<script>
let varIdx = {{ $product->variants->count() }};
function addVariantRow() {
    const container = document.getElementById('variants-container');
    container.insertAdjacentHTML('beforeend', `
        <tr class="variant-row">
            <td style="padding:6px 10px;">
                <input type="text" name="variants[${varIdx}][name]" class="form-control" placeholder="180×200 cm">
            </td>
            <td style="padding:6px 10px;">
                <input type="number" name="variants[${varIdx}][extra_price]" class="form-control" placeholder="Ek Fiyat" value="0" min="0" step="0.01">
            </td>
            <td style="padding:6px 10px;">
                <input type="number" name="variants[${varIdx}][stock]" class="form-control" placeholder="Stok" value="0" min="0">
            </td>
            <td style="padding:6px;">
                <button type="button" onclick="this.closest('tr').remove()" style="background:var(--danger);color:#fff;border:none;border-radius:7px;cursor:pointer;width:32px;height:32px;font-size:14px;">×</button>
            </td>
        </tr>
    `);
    varIdx++;
}
</script>
@endpush
