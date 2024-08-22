<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait WithCaptcha
{
   public function validateCaptcha($g_recaptcha_response)
   {
      $g_response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
         'secret' => config('services.recaptcha.secret_key'),
         'response' => $g_recaptcha_response
      ]);

      // returns false if google detects the request is coming from a bot.
      return !$g_response->json('success') ? false : true;
   }
}