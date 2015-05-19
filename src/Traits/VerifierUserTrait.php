<?php namespace Coderjp\Verifier\Traits;

/**
 * This file is part of Verifier,
 *
 * @license MIT
 * @package Coderjp\Verifier
 */

use Config;
use Mail;

trait VerifierUserTrait
{
    public function sendVerification()
    {
        $this->setVerificationCode ($this->createVerificationCode());

        $user =& $this;

        return Mail::queue(Config::get('verifier.template'), ['user' => $this ], function($message) use($user) {
            $message->to($user->email, $user->getVerificationEmailName())->subject($user->getVerificationEmailSubject());
        });

    }

    protected function createVerificationCode()
    {
        do {
            $code = str_random(Config::get('verifier.code_length'));
        } while ( self::lookupVerificationCode($code) );

        return $code;

    }

    protected static function lookupVerificationCode($code)
    {
        return self::where(Config::get('verifier.store_column'), $code)->first();
    }

    public static function verify($code)
    {
        if ($user = self::lookupVerificationCode($code)) {
            $user->setVerificationCode();
        }

        return $user;
    }

    public function getVerificationEmailName()
    {
        return $this->name;
    }

    public function getVerificationEmailSubject()
    {
        return Config::get('verifier.subject');
    }

    protected function setVerificationCode($code = null)
    {
        $this->{Config::get('verifier.store_column')}  = $code;

        if ($code) {
            $this->{Config::get('verifier.flag_column')} = false;
        } else {
            $this->{Config::get('verifier.flag_column')} = true;
        }

        $this->save();
    }


}