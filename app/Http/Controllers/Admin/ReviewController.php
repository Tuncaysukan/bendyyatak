<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with('product')->latest()->paginate(25);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => !$review->is_approved]);
        $msg = $review->is_approved ? 'Yorum onaylandı.' : 'Yorum onayı kaldırıldı.';
        return redirect()->back()->with('success', $msg);
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->back()->with('success', 'Yorum silindi.');
    }
}
