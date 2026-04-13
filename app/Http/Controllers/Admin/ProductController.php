<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category', 'images')->withTrashed();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $products   = $query->latest()->paginate(20)->withQueryString();
        $categories = Category::orderBy('name')->get();
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'category_id'       => 'required|exists:categories,id',
            'price'             => 'required|numeric|min:0',
            'compare_at_price'  => 'nullable|numeric|min:0',
            'firmness_level'    => 'required|integer|min:1|max:10',
            'short_description' => 'nullable|string',
            'description'       => 'nullable|string',
            'sku'               => 'nullable|string|unique:products,sku',
            'seo_title'         => 'nullable|string|max:255',
            'seo_description'   => 'nullable|string|max:500',
            'images.*'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $validated['slug']         = $this->uniqueSlug($validated['name']);
        $validated['is_active']      = $request->has('is_active');
        $validated['is_featured']    = $request->has('is_featured');
        $validated['is_bestseller']  = $request->has('is_bestseller');
        $validated['is_new_arrival'] = $request->has('is_new_arrival');
        $validated['show_on_slider'] = $request->has('show_on_slider');
        $validated['is_comparable']  = $request->has('is_comparable');

        DB::transaction(function () use ($request, $validated) {
            $product = Product::create($validated);

            // Görseller
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $i => $file) {
                    $path = $file->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image'      => $path,
                        'is_primary' => $i === 0,
                        'sort_order' => $i,
                    ]);
                }
            }

            // Varyantlar
            if ($request->has('variants')) {
                foreach ($request->variants as $i => $variant) {
                    if (!empty($variant['name'])) {
                        ProductVariant::create([
                            'product_id'  => $product->id,
                            'name'        => $variant['name'],
                            'sku'         => $variant['sku'] ?? null,
                            'extra_price' => $variant['extra_price'] ?? 0,
                            'stock'       => $variant['stock'] ?? 0,
                            'sort_order'  => $i,
                        ]);
                    }
                }
            }

            // Özellikler
            if ($request->has('attributes')) {
                foreach ($request->attributes as $i => $attr) {
                    if (!empty($attr['key'])) {
                        ProductAttribute::create([
                            'product_id' => $product->id,
                            'key'        => $attr['key'],
                            'value'      => $attr['value'],
                            'sort_order' => $i,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('admin.products.index')->with('success', 'Ürün oluşturuldu.');
    }

    public function edit(Product $product)
    {
        $product->load('images', 'variants', 'attributes', 'category');
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'category_id'       => 'required|exists:categories,id',
            'price'             => 'required|numeric|min:0',
            'compare_at_price'  => 'nullable|numeric|min:0',
            'firmness_level'    => 'required|integer|min:1|max:10',
            'short_description' => 'nullable|string',
            'description'       => 'nullable|string',
            'sku'               => 'nullable|string|unique:products,sku,' . $product->id,
            'seo_title'         => 'nullable|string|max:255',
            'seo_description'   => 'nullable|string|max:500',
            'images.*'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $validated['is_active']      = $request->has('is_active');
        $validated['is_featured']    = $request->has('is_featured');
        $validated['is_bestseller']  = $request->has('is_bestseller');
        $validated['is_new_arrival'] = $request->has('is_new_arrival');
        $validated['show_on_slider'] = $request->has('show_on_slider');
        $validated['is_comparable']  = $request->has('is_comparable');
        $validated['slug']         = $this->uniqueSlug($validated['name'], $product->id);

        DB::transaction(function () use ($request, $validated, $product) {
            $product->update($validated);

            // Eski görselleri sil
            if ($request->has('delete_images')) {
                foreach ($request->delete_images as $imgId) {
                    $img = ProductImage::where('product_id', $product->id)->find($imgId);
                    if ($img) {
                        Storage::disk('public')->delete($img->image);
                        $img->delete();
                    }
                }
                // Ana görsel silindiyse ilk kalanı ana yap
                if ($product->images()->where('is_primary', true)->count() === 0) {
                    $first = $product->images()->first();
                    if ($first) $first->update(['is_primary' => true]);
                }
            }

            // Yeni görseller
            if ($request->hasFile('images')) {
                $lastSort = $product->images()->max('sort_order') ?? -1;
                foreach ($request->file('images') as $i => $file) {
                    $path = $file->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image'      => $path,
                        'is_primary' => $product->images()->count() === 0 && $i === 0,
                        'sort_order' => $lastSort + $i + 1,
                    ]);
                }
            }

            // Varyantları sıfırla ve yeniden ekle
            $product->variants()->delete();
            if ($request->has('variants')) {
                foreach ($request->variants as $i => $variant) {
                    if (!empty($variant['name'])) {
                        ProductVariant::create([
                            'product_id'  => $product->id,
                            'name'        => $variant['name'],
                            'sku'         => $variant['sku'] ?? null,
                            'extra_price' => $variant['extra_price'] ?? 0,
                            'stock'       => $variant['stock'] ?? 0,
                            'sort_order'  => $i,
                        ]);
                    }
                }
            }

            // Özellikleri sıfırla ve yeniden ekle
            $product->attributes()->delete();
            if ($request->has('attributes')) {
                foreach ($request->attributes as $i => $attr) {
                    if (!empty($attr['key'])) {
                        ProductAttribute::create([
                            'product_id' => $product->id,
                            'key'        => $attr['key'],
                            'value'      => $attr['value'],
                            'sort_order' => $i,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('admin.products.index')->with('success', 'Ürün güncellendi.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Ürün silindi.');
    }

    public function deleteImage(Product $product, Request $request)
    {
        $image = ProductImage::where('product_id', $product->id)
                             ->findOrFail($request->image_id);
        Storage::disk('public')->delete($image->image);
        $image->delete();

        // Eğer silinen birinci görselse, sonrakini birinci yap
        if ($image->is_primary) {
            $first = $product->images()->first();
            if ($first) $first->update(['is_primary' => true]);
        }
        return response()->json(['success' => true]);
    }

    public function reorderImages(Product $product, Request $request)
    {
        foreach ($request->order as $item) {
            ProductImage::where('id', $item['id'])->update(['sort_order' => $item['sort']]);
        }
        // İlk görsel primary olsun
        $firstId = collect($request->order)->sortBy('sort')->first()['id'];
        ProductImage::where('product_id', $product->id)->update(['is_primary' => false]);
        ProductImage::where('id', $firstId)->update(['is_primary' => true]);
        return response()->json(['success' => true]);
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug  = Str::slug($name, '-');
        $query = Product::where('slug', $slug);
        if ($ignoreId) $query->where('id', '!=', $ignoreId);

        $count = 1;
        $base  = $slug;
        while ($query->clone()->exists()) {
            $slug = $base . '-' . $count++;
            $query = Product::where('slug', $slug);
            if ($ignoreId) $query->where('id', '!=', $ignoreId);
        }
        return $slug;
    }
}
