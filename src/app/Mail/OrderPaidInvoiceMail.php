<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderPaidInvoiceMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Order $order
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice Pembayaran ' . $this->order->invoice_number . ' - ' . config('brand.name', 'Igrenc'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.paid-invoice',
        );
    }
}
