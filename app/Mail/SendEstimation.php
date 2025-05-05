<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class SendEstimation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $filePath;
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }
    /**
     * Get the message envelope.
     */
    public function build()
    {
        // Generate the PDF
        return $this->subject('La tua stima MyDomotics')
            ->view('estimation.email')
            ->attach(storage_path("app/private/estimations/{$this->filePath}"), [
                'as' => 'Estimation.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
