<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $note;

    public function __construct(Order $order, $note = null)
    {
        $this->order = $order;
        $this->note = $note;
    }

    public function envelope(): Envelope
    {
        $statusLabels = [
            'pending' => 'Bekliyor',
            'confirmed' => 'Onaylandı',
            'preparing' => 'Hazırlanıyor',
            'shipped' => 'Kargolandı',
            'delivered' => 'Teslim Edildi',
            'cancelled' => 'İptal Edildi',
            'refunded' => 'İade Edildi'
        ];
        $label = $statusLabels[$this->order->status] ?? $this->order->status;

        return new Envelope(
            subject: 'Sipariş Durumu Güncellendi: ' . $label . ' - #' . $this->order->order_no,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order_status',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
