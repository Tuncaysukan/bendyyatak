<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        return view('shop.pages.about');
    }

    public function contact()
    {
        return view('shop.pages.contact');
    }

    public function contactPost(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|min:10',
        ]);

        $msg = \App\Models\ContactMessage::create($request->all());

        // Mail gönderimi (Admin'e)
        $adminEmail = \App\Models\Setting::get('contact_email', 'info@bendyy.com');
        try {
            \Illuminate\Support\Facades\Mail::to($adminEmail)->send(new \App\Mail\ContactMessageMail($msg));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('İletişim formu maili gönderilemedi: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Mesajınız alındı. En kısa sürede size dönüş yapacağız.');
    }

    public function shipping()
    {
        return view('shop.pages.shipping');
    }

    public function privacy()
    {
        return view('shop.pages.privacy');
    }

    public function terms()
    {
        return view('shop.pages.terms');
    }
}
