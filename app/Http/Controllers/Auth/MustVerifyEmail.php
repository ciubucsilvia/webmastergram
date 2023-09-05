<?php
namespace App\Http\Controllers\Auth;

use App\Notifications\VerifyEmail;
use Carbon\Carbon;

trait MustVerifyEmail
{
    public function hasVerifiedEmail()
    {
        return !is_null($this->email_verified_at);
    }

    public function sendEmailVerificationToken()
    {
        $this->saveVerificationToken();
        $this->notify(new VerifyEmail());
        return redirect()->route('verify/email');
    }

    public function markEmailAsVerified()
    {
        $this->email_verified_at = $this->freshTimestamp();
        return $this->save();
        
    }

    public function generateVerificationToken()
    {
        $token = '';

        for($i = 0; $i < 6; $i++) {
            $token .= random_int(0, 9);
        }

        return $token;
    }

    public function saveVerificationToken()
    {
        if(!$this->emailVerification()->first()) {
           return $this->emailVerification()->create([
                'code' => $this->generateVerificationToken(),
                'expires_at' => now()->addHour()
            ]);
        } 
        return $this->emailVerification()->first();  
    }

    public function resetNotificationToken()
    {
        $this->emailVerification()->delete();
        $this->saveVerificationToken();
    }

    public function validNotificationToken($verification_code)
    {
        if(Carbon::parse($this->emailVerification->expires_at)->lt(now())){
            $this->resetNotificationToken();
            $this->sendEmailVerificationToken();
            return false;
        }

        if($this->emailVerification->code == $verification_code) {
            $this->markEmailAsVerified();
            return true;
        }
        return false;
    }
}
?>