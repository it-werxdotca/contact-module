<?php

namespace Modules\Contact\App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $contact;

    /**
     * Create a new message instance.
     *
     * @param  array $contact
     * @return void
     */
    public function __construct($contact)
    {
        // Convert array to object
        $this->contact = (object) $contact;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('templates.emails.contact')
                    ->subject('Contact Form Submission')
                    ->with([
                        'contact' => $this->contact,
                    ])
                    ->cc(Config::get('contact.contact-email'));
    }
}
