<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactUs;

class ContactusController extends Controller
{
    public function sendMail(Request $request) {
        Mail::to(config('mail.contact_to_mail'))->send(new ContactUs($request));
        // check for failures
        if (Mail::failures()) {
            return response()->json(['status' => 'failed'], 422);
        }
        return response()->json(['status' => 'success'], 200);

    }
}
