<?php

namespace App\Mail;

use AllowDynamicProperties;
use App\Models\Quotation;
//vuse Barryvdh\DomPDF\Facade as PDF;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


#[AllowDynamicProperties]
class SendQuotation extends Mailable
{
    use Queueable, SerializesModels;

    public $quotation;
    public $pdf;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Quotation $quotation, $productsGroupedByRoom,)
    {
        $this->quotation = $quotation;
        $this->productsGroupedByRoom = $productsGroupedByRoom;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Generate the PDF
        $pdf = PDF::loadView('quotations.pdf', ['quotation' => $this->quotation, 'productsGroupedByRoom' => $this->productsGroupedByRoom]);
        if($this->quotation->file){
            return $this->subject('Il tuo preventivo MyDomotics')
                ->markdown('emails.quotations.send')
                ->attachData($pdf->output(), 'preventivo.pdf', [
                    'mime' => 'application/pdf',
                ])
                //attach the uploaded file too
                ->attach(storage_path('app/public/pdfs/' . $this->quotation->file))
                /*->attachData($pdf->output(), 'quotation.pdf', [
                    'mime' => 'application/pdf',
                ])*/
                ;
        }else {
            return $this->subject('Il tuo preventivo MyDomotics')
                ->markdown('emails.quotations.send')
                ->attachData($pdf->output(), 'preventivo.pdf', [
                    'mime' => 'application/pdf',
                ]);
                //attach the uploaded file too

            }
    }
}
