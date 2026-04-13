<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::orderBy('sort_order')->get();
        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image'       => 'required|image|max:2048',
            'title'       => 'nullable|string|max:255',
            'subtitle'    => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:50',
            'button_link' => 'nullable|string|max:255',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        $data['image'] = $request->file('image')->store('sliders', 'public');

        Slider::create($data);

        return redirect()->route('admin.sliders.index')->with('success', 'Slider eklendi.');
    }

    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'image'       => 'nullable|image|max:2048',
            'title'       => 'nullable|string|max:255',
            'subtitle'    => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:50',
            'button_link' => 'nullable|string|max:255',
        ]);

        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($slider->image) Storage::disk('public')->delete($slider->image);
            $data['image'] = $request->file('image')->store('sliders', 'public');
        }

        $slider->update($data);

        return redirect()->route('admin.sliders.index')->with('success', 'Slider güncellendi.');
    }

    public function updateOrder(Request $request)
    {
        if ($request->has('order')) {
            foreach ($request->order as $id => $order) {
                Slider::where('id', $id)->update(['sort_order' => $order]);
            }
        }
        return redirect()->back()->with('success', 'Sıralama güncellendi.');
    }

    public function destroy(Slider $slider)
    {
        if ($slider->image) Storage::disk('public')->delete($slider->image);
        $slider->delete();
        return redirect()->route('admin.sliders.index')->with('success', 'Slider silindi.');
    }
}
