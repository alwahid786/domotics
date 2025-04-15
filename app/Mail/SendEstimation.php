<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class SendEstimation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }
    /**
     * Get the message envelope.
     */
    public function build()
    {
        // Generate the PDF
        $pdf = PDF::loadView('quotations.pdf', ['quotation' => $this->user]);
        if($this->user->file){
            return $this->subject('Il tuo preventivo MyDomotics')
                ->markdown('emails.users.send')
                ->attachData($pdf->output(), 'preventivo.pdf', [
                    'mime' => 'application/pdf',
                ])->attach(storage_path('app/public/pdfs/' . $this->user->file))
                ;
        }else {
            return $this->subject('Il tuo preventivo MyDomotics')
                ->markdown('emails.users.send')
                ->attachData($pdf->output(), 'preventivo.pdf', [
                    'mime' => 'application/pdf',
                ]);
            }
    }
}
