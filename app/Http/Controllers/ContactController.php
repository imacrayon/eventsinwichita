<?php

namespace App\Http\Controllers;

use Mail;
use App\Mail\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a contact form.
     *
     * @return Response
     */
    public function index()
    {
        return view('contact.index');
    }
    /**
     * Send contact.
     *
     * @return Response
     */
    public function send(Request $request)
    {
        $this->validate($request, [
            'name'    => ['required', 'string'],
            'email'   => ['required', 'email'],
            'message' => ['required', 'string', 'max:1000']
        ]);

        Mail::to(env('MAIL_CONTACT_ADDRESS'))->send(new ContactMessage($request->all()));

        return back()
            ->with('status', 'Message sent! Thanks for taking the time to reach out, we seriously value your feedback. You\'re awesome.')
            ->with('flash', 'Your message was sent.');
    }
}
