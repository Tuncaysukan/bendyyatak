<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent', 'children')
                               ->whereNull('parent_id')
                               ->orderBy('sort_order')
                               ->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = Category::whereNull('parent_id')->orderBy('name')->get();
        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'parent_id'       => 'nullable|exists:categories,id',
            'description'     => 'nullable|string',
            'image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'seo_title'       => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'sort_order'      => 'nullable|integer',
        ]);

        $validated['slug']      = Str::slug($validated['name'], '-');
        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($validated);
        return redirect()->route('admin.categories.index')->with('success', 'Kategori oluşturuldu.');
    }

    public function edit(Category $category)
    {
        $parents = Category::whereNull('parent_id')
                           ->where('id', '!=', $category->id)
                           ->orderBy('name')
                           ->get();
        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'parent_id'       => 'nullable|exists:categories,id',
            'description'     => 'nullable|string',
            'image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'seo_title'       => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'sort_order'      => 'nullable|integer',
        ]);

        $validated['slug']      = Str::slug($validated['name'], '-');
        $validated['is_active'] = $request->boolean('is_active');

        // Görsel silme
        if ($request->boolean('remove_image') && $category->image) {
            Storage::disk('public')->delete($category->image);
            $validated['image'] = null;
        }

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($validated);
        return redirect()->route('admin.categories.index')->with('success', 'Kategori güncellendi.');
    }

    public function destroy(Category $category)
    {
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Kategori silindi.');
    }

    public function updateOrder(Request $request)
    {
        foreach ($request->order as $item) {
            Category::where('id', $item['id'])->update(['sort_order' => $item['sort']]);
        }
        return response()->json(['success' => true]);
    }
}
