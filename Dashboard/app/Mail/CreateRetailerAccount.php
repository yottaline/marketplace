<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CreateRetailerAccount extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $create;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $create)
    {
        $this->name = $name;
        $this->create = $create;
    }

    /**
     * Get the message envelope.
     */


    public function build()
    {
        return $this->subject('Create Retailer Account -' . $this->create)
                    ->view('mail.retailer_account')
                    ->with([
                        'name' => $this->name,
                    ]);
    }
}