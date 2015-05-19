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
    /**
     * Queues an email for delivery
     *
     * @return mixed
     */
    public function sendVerification()
    {
        $this->setVerificationCode ($this->createVerificationCode());

        $user =& $this;

        return Mail::queue(Config::get('verifier.template'), ['user' => $this ], function($message) use($user) {
            $message->to($user->email, $user->getVerificationEmailName())->subject($user->getVerificationEmailSubject());
        });

    }

    /**
     * Generates a random, but unique verification code
     *
     * @return mixed
     */
    protected function createVerificationCode()
    {
        do {
            $code = str_random(Config::get('verifier.code_length'));
        } while ( self::lookupVerificationCode($code) );

        return $code;

    }

    /**
     * Finds the record matching the verification code
     *
     * @param $code
     * @return mixed
     */
    protected static function lookupVerificationCode($code)
    {
        return self::where(Config::get('verifier.store_column'), $code)->first();
    }

    /**
     * Verifies a given verification code
     *
     * @param $code
     * @return mixed
     */
    public static function verify($code)
    {
        if ($user = self::lookupVerificationCode($code)) {
            $user->setVerificationCode();
        }

        return $user;
    }

    /**
     * Returns the name used when sending an email
     *
     * @return mixed
     */
    public function getVerificationEmailName()
    {
        return $this->name;
    }

    /**
     * Returns the email address used when sending an email
     *
     * @return mixed
     */
    public function getVerificationEmailSubject()
    {
        return Config::get('verifier.subject');
    }

    /**
     * Assigns and saves the verification code
     *
     * @param null $code
     */
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