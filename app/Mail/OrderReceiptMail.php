<?php

declare(strict_types=1);

namespace App\Mail;

use App\Domain\Payments\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderReceiptMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly Order $order,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Receipt — '.$this->order->reference,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.order-receipt',
        );
    }
}
