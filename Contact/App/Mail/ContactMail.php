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
        // Retrieve the CC email address from the config file
        $ccEmail = Config::get('contact.contact-email');

        return $this->view('templates.emails.contact')
                    ->subject('Contact Form Submission')
                    ->with([
                        'name' => $this->contact->name,
                        'email' => $this->contact->email,
                        'phone' => $this->contact->phone,
                        'subject' => $this->contact->subject,
                        'message' => $this->contact->message,
                    ])
                    ->cc($ccEmail); // Use the retrieved CC email address
    }
}
