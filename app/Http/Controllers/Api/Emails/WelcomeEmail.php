<?php

namespace App\Http\Controllers\Api\Emails;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class WelcomeEmail extends Controller
{
    public function sendWelcomeEmail($username,$loginemail,$password)
    {

        $username = $username;
        $loginUrl = config('app.frontend_url') . "/login";
        $loginemail = $loginemail;
        $password = $password;

        Mail::to($loginemail)->send(new WelcomeMail($username, $loginUrl, $loginemail, $password));

        return response()->json(['message' => "E-mail envoyé avec succès"], 200);
    }
}
