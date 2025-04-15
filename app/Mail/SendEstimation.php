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
    public $roomData;
    public $sensorData;
    public $totalPrice;
    public $floorName;
    public $image;

    public function __construct($roomData, $sensorData, $totalPrice, $floorName, $image)
    {
        $this->roomData = $roomData;
        $this->sensorData = $sensorData;
        $this->totalPrice = $totalPrice;
        $this->floorName = $floorName;
        $this->image = $image;
    }
    /**
     * Get the message envelope.
     */
    public function build()
    {
        // Generate the PDF
        $pdf = PDF::loadView('estimation.pdf', ['estimationId' => $this->roomData, 'sensorData' => $this->sensorData, 'totalPrice' => $this->totalPrice, 'floorName' => $this->floorName, 'image' => $this->image]);
        return $this->subject('La tua stima MyDomotics')
            ->attachData($pdf->output(), 'stima.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
