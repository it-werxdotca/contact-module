<?php

namespace Modules\Contact\App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Modules\Contact\App\Models\Contact;
use Modules\Contact\App\Mail\ContactMail;

class ContactController extends BaseController
{
    public function index()
    {
        // Define the view name
        $viewName = 'index'; // Replace with your dynamic view name logic if needed

        // Get the active theme from config
        $activeTheme = Config::get('theme.active', 'default');

        // Attempt to load view from the active theme first
        $themeView = "{$activeTheme}::contact.{$viewName}";
        $moduleView = "Modules::contact.{$viewName}";

        if (View::exists($themeView)) {
            Log::info("Loading view from active theme: {$activeTheme}");
            return view($themeView);
        } elseif (View::exists($moduleView)) {
            return view($moduleView);
        } else {
            // Log error and handle view not found
            Log::error("View [contact.{$viewName}] not found in theme [{$activeTheme}] or module [Contact].");
            //abort(404, "View [contact.{$viewName}] not found.");
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'phone' => ['required', 'regex:/^\d{3}-\d{3}-\d{4}$/'],
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        try {
            // Store contact information in the database
            Contact::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject,
                'message' => $request->message,
            ]);

            // Retrieve the CC email address from the config file
            $ccEmail = Config::get('contact.contact-email');

            // Send email to the user with CC
            Mail::to($request->email)
                ->cc($ccEmail)  // Use the retrieved CC email address from the config.php file.
                ->send(new ContactMail($request));

        } catch (\Exception $e) {
            Log::error("ContactController@store: Failed - {$e->getMessage()}");
        }

        return redirect()->back()->with(['success' => 'Thank you for contacting us. We will contact you shortly.']);
    }
}
