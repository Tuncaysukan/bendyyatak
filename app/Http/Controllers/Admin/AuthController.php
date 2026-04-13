<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session()->has('admin_id')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'E-posta adresi zorunludur.',
            'email.email'       => 'Geçerli bir e-posta adresi girin.',
            'password.required' => 'Şifre zorunludur.',
        ]);

        $admin = Admin::where('email', $request->email)
                      ->where('is_active', true)
                      ->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return back()->withErrors(['email' => 'E-posta veya şifre hatalı.'])->withInput();
        }

        session([
            'admin_id'   => $admin->id,
            'admin_name' => $admin->name,
            'admin_role' => $admin->role,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Hoş geldiniz, ' . $admin->name . '!');
    }

    public function logout(Request $request)
    {
        session()->forget(['admin_id', 'admin_name', 'admin_role']);
        return redirect()->route('admin.login')->with('success', 'Başarıyla çıkış yapıldı.');
    }
}
