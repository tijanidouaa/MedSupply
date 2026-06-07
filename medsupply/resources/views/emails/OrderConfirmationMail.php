<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

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
        try {
            $pdf = Pdf::loadView('emails.order_invoice_pdf', ['order' => $this->order])
                      ->setPaper('a4', 'portrait');

            return [
                Attachment::fromData(
                    fn () => $pdf->output(),
                    'facture-' . $this->order->reference . '.pdf'
                )->withMime('application/pdf'),
            ];
        } catch (\Exception $e) {
            \Log::error('PDF generation failed: ' . $e->getMessage());
            return [];
        }
    }
}