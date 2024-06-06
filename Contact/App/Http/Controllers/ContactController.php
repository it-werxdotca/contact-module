<?php

namespace Modules\Contact\App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Modules\Contact\App\Models\Contact;

class ContactController extends BaseController
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
        return view('contact::index');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|digits:10|numeric',
            'subject' => 'required',
            'message' => 'required'
        ]);

        Contact::create($request->all());

        return redirect()->back()
                         ->with(['success' => 'Thank you for contacting us. We will contact you shortly.']);
    }
}
