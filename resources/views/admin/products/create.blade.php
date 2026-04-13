@extends('layouts.admin')
@section('title', 'Yeni Ürün')
@section('topbar-title', 'Yeni Ürün Ekle')

@section('content')
<div class="page-header">
    <div class="page-title">Yeni Ürün Ekle</div>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Geri</a>
</div>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="grid grid-2">
        <div style="display:flex;flex-direction:column;gap:20px;">
            {{-- Temel --}}
            <div class="card">
                <div class="card-header"><span class="card-title">Temel Bilgiler</span></div>
                <div class="form-group">
                    <label class="form-label">Ürün Adı *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Örn: Premium Ortopedik Yatak">
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-control">
                        <option value="">— Kategori Seçin —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->parent ? '&nbsp;&nbsp;↳ ' . $cat->name : $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">SKU (Stok Kodu)</label>
                    <input type="text" name="sku" class="form-control" value="{{ old('sku') }}" placeholder="BY-0001">
                </div>
                <div class="form-group">
                    <label class="form-label">Kısa Açıklama</label>
                    <textarea name="short_description" class="form-control" rows="3" placeholder="Ürünün kısa özeti...">{{ old('short_description') }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Detaylı Açıklama</label>
                    <textarea name="description" class="form-control" rows="8" placeholder="Ürünün tam açıklaması...">{{ old('description') }}</textarea>
                </div>
            </div>

            {{-- Fiyat --}}
            <div class="card">
                <div class="card-header"><span class="card-title">Fiyatlandırma</span></div>
                <div class="grid grid-2">
                    <div class="form-group">
                        <label class="form-label">Satış Fiyatı (₺) *</label>
                        <input type="number" name="price" class="form-control" value="{{ old('price') }}" min="0" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Karşılaştırma Fiyatı (₺)</label>
                        <input type="number" name="compare_at_price" class="form-control" value="{{ old('compare_at_price') }}" min="0" step="0.01">
                        <div class="form-hint">Üstü çizili "eski fiyat"</div>
                    </div>
                </div>
            </div>

            {{-- Sertlik --}}
            <div class="card">
                <div class="card-header"><span class="card-title">Yatak Sertlik Skalası</span></div>
                <div class="form-group">
                    <label class="form-label">Sertlik Düzeyi: <strong id="firmnessVal">5</strong> / 10</label>
                    <input type="range" name="firmness_level" min="1" max="10" value="{{ old('firmness_level', 5) }}"
                           style="width:100%;accent-color:var(--primary);"
                           oninput="document.getElementById('firmnessVal').textContent=this.value">
                    <div style="display:flex;justify-content:space-between;font-size:11px;color:var(--text-muted);margin-top:4px;">
                        <span>Çok Yumuşak (1)</span><span>Orta (5)</span><span>Çok Sert (10)</span>
                    </div>
                </div>
            </div>

            {{-- SEO --}}
            <div class="card">
                <div class="card-header"><span class="card-title">SEO Ayarları</span></div>
                <div class="form-group">
                    <label class="form-label">SEO Başlığı</label>
                    <input type="text" name="seo_title" class="form-control" value="{{ old('seo_title') }}">
                    <div class="form-hint">Boş bırakılırsa ürün adı kullanılır</div>
                </div>
                <div class="form-group">
                    <label class="form-label">SEO Açıklaması</label>
                    <textarea name="seo_description" class="form-control" rows="3">{{ old('seo_description') }}</textarea>
                </div>
            </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:20px;">
            {{-- Durum --}}
            <div class="card">
                <div class="card-header"><span class="card-title">Yayın Durumu</span></div>
                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="is_active" checked> Yayında (aktif)
                    </label>
                </div>
                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="is_featured"> Öne Çıkan Ürün
                    </label>
                </div>
                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="is_bestseller"> En Çok Satan Ürün
                    </label>
                </div>
                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="is_new_arrival"> En Yeni Ürün
                    </label>
                </div>
                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="show_on_slider"> Slider'da Göster
                    </label>
                </div>
                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="is_comparable" checked> Karşılaştırmaya Eklenebilir
                    </label>
                </div>
            </div>

            {{-- Görseller --}}
            <div class="card">
                <div class="card-header"><span class="card-title">Ürün Görselleri</span></div>
                <div class="form-group">
                    <input type="file" name="images[]" multiple class="form-control" accept="image/*" id="imageInput">
                    <div class="form-hint">Birden fazla görsel seçebilirsiniz. İlk seçilen ana görsel olur.</div>
                </div>
                <div id="imagePreview" style="display:flex;flex-wrap:wrap;gap:8px;margin-top:8px;"></div>
            </div>

            {{-- Varyantlar --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Varyantlar (Boyutlar)</span>
                    <button type="button" class="btn btn-outline btn-sm" onclick="addVariantRow()">
                        <i class="fas fa-plus"></i> Ekle
                    </button>
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
                        @foreach(['80×200 cm','90×200 cm','100×200 cm','140×200 cm','160×200 cm','180×200 cm'] as $i => $size)
                        <tr class="variant-row">
                            <td style="padding:6px 10px;"><input type="text" name="variants[{{ $i }}][name]" class="form-control" value="{{ $size }}" placeholder="80×200 cm"></td>
                            <td style="padding:6px 10px;"><input type="number" name="variants[{{ $i }}][extra_price]" class="form-control" value="0" min="0" step="0.01"></td>
                            <td style="padding:6px 10px;"><input type="number" name="variants[{{ $i }}][stock]" class="form-control" value="0" min="0"></td>
                            <td style="padding:6px;"><button type="button" onclick="this.closest('tr').remove()" style="background:var(--danger);color:#fff;border:none;border-radius:7px;cursor:pointer;width:32px;height:32px;font-size:14px;">×</button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Özellikler --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Teknik Özellikler</span>
                    <button type="button" class="btn btn-outline btn-sm" onclick="addAttrRow()"><i class="fas fa-plus"></i></button>
                </div>
                <table style="width:100%;border-collapse:collapse;font-size:13px;">
                    <thead>
                        <tr style="background:#f9fafb;">
                            <th style="padding:8px 10px;border-bottom:2px solid var(--border);font-weight:600;color:var(--text-muted);font-size:11.5px;text-transform:uppercase;">Özellik</th>
                            <th style="padding:8px 10px;border-bottom:2px solid var(--border);font-weight:600;color:var(--text-muted);font-size:11.5px;text-transform:uppercase;">Değer</th>
                            <th style="width:36px;border-bottom:2px solid var(--border);"></th>
                        </tr>
                    </thead>
                    <tbody id="attrs-container">
                        @foreach(['Malzeme','Kaplama','Yükseklik','Garanti'] as $i => $key)
                        <tr class="attr-row">
                            <td style="padding:6px 10px;"><input type="text" name="attrs[{{ $i }}][key]" class="form-control" value="{{ $key }}" placeholder="Özellik adı"></td>
                            <td style="padding:6px 10px;"><input type="text" name="attrs[{{ $i }}][value]" class="form-control" placeholder="Değer"></td>
                            <td style="padding:6px;"><button type="button" onclick="this.closest('tr').remove()" style="background:var(--danger);color:#fff;border:none;border-radius:7px;cursor:pointer;width:32px;height:32px;font-size:14px;">×</button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div style="margin-top:20px;display:flex;justify-content:flex-end;gap:12px;">
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline">İptal</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Ürünü Kaydet</button>
    </div>
</form>
@endsection

@push('scripts')
<script>
let varIdx = 6, attrIdx = 4;

function addVariantRow() {
    const tbody = document.getElementById('variants-container');
    tbody.insertAdjacentHTML('beforeend', `
        <tr class="variant-row">
            <td style="padding:6px 10px;"><input type="text" name="variants[${varIdx}][name]" class="form-control" placeholder="200×200 cm"></td>
            <td style="padding:6px 10px;"><input type="number" name="variants[${varIdx}][extra_price]" class="form-control" value="0" min="0" step="0.01"></td>
            <td style="padding:6px 10px;"><input type="number" name="variants[${varIdx}][stock]" class="form-control" value="0" min="0"></td>
            <td style="padding:6px;"><button type="button" onclick="this.closest('tr').remove()" style="background:var(--danger);color:#fff;border:none;border-radius:7px;cursor:pointer;width:32px;height:32px;font-size:14px;">×</button></td>
        </tr>
    `);
    varIdx++;
}

function addAttrRow() {
    const tbody = document.getElementById('attrs-container');
    tbody.insertAdjacentHTML('beforeend', `
        <tr class="attr-row">
            <td style="padding:6px 10px;"><input type="text" name="attrs[${attrIdx}][key]" class="form-control" placeholder="Özellik adı"></td>
            <td style="padding:6px 10px;"><input type="text" name="attrs[${attrIdx}][value]" class="form-control" placeholder="Değer"></td>
            <td style="padding:6px;"><button type="button" onclick="this.closest('tr').remove()" style="background:var(--danger);color:#fff;border:none;border-radius:7px;cursor:pointer;width:32px;height:32px;font-size:14px;">×</button></td>
        </tr>
    `);
    attrIdx++;
}

// Görsel önizleme
document.getElementById('imageInput')?.addEventListener('change', function() {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    Array.from(this.files).forEach((file, i) => {
        const reader = new FileReader();
        reader.onload = e => {
            preview.insertAdjacentHTML('beforeend', `
                <div style="position:relative;">
                    <img src="${e.target.result}" style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:${i===0 ? '2px solid var(--primary)' : '1px solid var(--border)'}">
                    ${i===0 ? '<span style="position:absolute;bottom:2px;right:2px;background:var(--primary);color:#fff;font-size:9px;padding:1px 5px;border-radius:4px;">Ana</span>' : ''}
                </div>
            `);
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endpush
