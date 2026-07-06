<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderPaidInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order->loadMissing([
            'payment',
            'paymentGateway',
        ]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice Pembayaran ' . $this->order->invoice_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.orders.paid-invoice',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}