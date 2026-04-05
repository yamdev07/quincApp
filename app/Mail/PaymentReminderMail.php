<?php
// app/Mail/PaymentReminderMail.php

namespace App\Mail;

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $tenant;

    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Rappel de paiement - ' . $this->tenant->company_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-reminder',
        );
    }
}