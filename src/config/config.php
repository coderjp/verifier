<?php
/**
 * This file is part of Verifier
 *
 * @license MIT
 * @package Coderjp\Verifier
 */
return array(
    /*
    |--------------------------------------------------------------------------
    | Mail Template
    |--------------------------------------------------------------------------
    |
    | This is the template that is used when the welcome email is sent.
    |
    */
    'template' => 'emails.welcome',
    /*
    |--------------------------------------------------------------------------
    | Mail Subject
    |--------------------------------------------------------------------------
    |
    | This is the subject of the email.
    |
    */
    'subject' => 'Welcome to our site!',
    /*
    |--------------------------------------------------------------------------
    | Authorisation Code Length
    |--------------------------------------------------------------------------
    |
    | This is the length of the generated authorisation code.
    |
    */
    'code_length' => 80,
    /*
    |--------------------------------------------------------------------------
    | Authorisation Column Name
    |--------------------------------------------------------------------------
    |
    | This is the name of the column used to store and lookup authorisation
    | codes.
    |
    */
    'store_column' => 'verification_code',
    /*
    |--------------------------------------------------------------------------
    | Authorised Flag Column
    |--------------------------------------------------------------------------
    |
    | This is the name of the column used to store whether the user has been
    | verified. (Bool)
    |
    */
    'flag_column' => 'verified',
    /*
    |--------------------------------------------------------------------------
    | Migration Tables
    |--------------------------------------------------------------------------
    |
    | This is an array of tables to add Verifier columns for.
    |
    */
    'tables' => ['users'],
);