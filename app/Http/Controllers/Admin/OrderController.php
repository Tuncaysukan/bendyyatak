<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('payment')) {
            $query->where('payment_method', $request->payment);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_no', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_email', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(25)->withQueryString();

        $statusCounts = [
            'pending'   => Order::where('status', 'pending')->count(),
            'confirmed' => Order::where('status', 'confirmed')->count(),
            'preparing' => Order::where('status', 'preparing')->count(),
            'shipped'   => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'statusCounts'));
    }

    public function show(Order $order)
    {
        $order->load('items.product', 'statusLogs', 'transactions');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,shipped,delivered,cancelled,refunded',
            'note'   => 'nullable|string|max:500',
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        OrderStatusLog::create([
            'order_id'        => $order->id,
            'admin_id'        => session('admin_id'),
            'status'          => $request->status,
            'note'            => $request->note,
            'notify_customer' => $request->has('notify_customer'),
        ]);

        // Müşteri bildirimi gönder (mail)
        if ($request->has('notify_customer')) {
            try {
                Mail::to($order->customer_email)->send(new \App\Mail\OrderStatusMail($order, $request->note));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Müşteri durum maili gönderilemedi: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Sipariş durumu güncellendi.');
    }

    public function updateCargo(Request $request, Order $order)
    {
        $request->validate([
            'cargo_company'     => 'required|string',
            'cargo_tracking_no' => 'required|string',
        ]);

        $trackingUrls = [
            'yurtici' => 'https://www.yurtiçikargo.com/tr-TR/Kargo-Takip?documentNumber=',
            'mng'     => 'https://www.mngkargo.com.tr/wps/portal/mngkargo/takip?hkod=',
            'aras'    => 'https://kargotakip.araskargo.com.tr/?kod=',
            'ptt'     => 'https://gonderitakip.ptt.gov.tr/Track/Verify?q=',
        ];

        $order->update([
            'cargo_company'      => $request->cargo_company,
            'cargo_tracking_no'  => $request->cargo_tracking_no,
            'cargo_tracking_url' => ($trackingUrls[$request->cargo_company] ?? '') . $request->cargo_tracking_no,
            'status'             => 'shipped',
        ]);

        OrderStatusLog::create([
            'order_id' => $order->id,
            'admin_id' => session('admin_id'),
            'status'   => 'shipped',
            'note'     => $request->cargo_company . ' - ' . $request->cargo_tracking_no,
            'notify_customer' => true,
        ]);

        try {
            Mail::to($order->customer_email)->send(new \App\Mail\OrderStatusMail($order, clone $request->cargo_company . ' takip no: ' . clone $request->cargo_tracking_no));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Müşteri kargo maili gönderilemedi: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Kargo bilgisi güncellendi ve durum "Kargoya Verildi" olarak güncellendi.');
    }

    public function addNote(Request $request, Order $order)
    {
        $request->validate(['note' => 'required|string|max:1000']);
        $order->update(['admin_note' => $request->note]);
        return redirect()->back()->with('success', 'Not eklendi.');
    }
}
