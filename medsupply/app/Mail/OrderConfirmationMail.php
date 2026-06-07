<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order->load('supplier', 'hospital');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de commande — ' . $this->order->reference,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order_confirmation',
            with: ['order' => $this->order],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}