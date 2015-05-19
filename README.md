# Verifier (Laravel 5 Package)

Verifier is a package for Laravel 5 that sends out verification emails to users in order for them to prove their
email is valid. Users will click a link in the email to validate their address.

## Installation
Require this package with composer using the following command:

    composer require coderjp/verifier


After updating composer, add the service provider to the `providers` array in `config/app.php`

    'Coderjp\Verifier\VerifierServiceProvider',
    
Generate the config file for changing various settings. This can be found in `config/verifier.php`.
    
    php artisan vendor:publish --provider=coderjp/verifier
    
Make sure the `tables` config option is correct. By default the table used is `users`. Then run the following
command to generate the migrations:

    php artisan verifier:migration
    
## Usage

### Model

Add the `VerifierUserTrait` to the model you wish to verify against. This will usually be the `User` model.

```php
<?php

use Coderjp\Verifier\Traits\VerifierUserTrait;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, EntrustUserTrait, SoftDeletes, VerifierUserTrait;

    ...
}
```

### Template

You will need to make a mail template for the welcome email to use. By default the config uses `emails.welcome`.

The variable `$user` will be passed to your template. This will be an array of the relevant user.

```php

Hi {{$user['name']}},

Welcome to our site. Please click the following link to activate your account:

http://www.example.com/?code={{$user['verification_code']}}

Regards, CoderJP

```


### Verifying

Where you create a user, you can now call  the `sendVerification()` method on the model. This will trigger
an email to be sent to the user.

```php
...

public function store(CreateUserRequest $request)
{
        $user = User::create($request->all());
        $user->sendVerification();
        
        ...
}

...

```

To verify a token you can use the `verify()` method like so:

```php
<?php

class UserController extends Controller {

    ...
    
    public function validate(Request $request)
    {
            $user = User::verify($request->get('code');
            
            if ($user) {
                // $user = User model
                return view('user.validated');
            } else {
                // $user = null;
                return view('user.invalid');
            }
    }

    ...
    
}
```

If the code is valid, verify will set `validated = true`, remove the `validation code` from the database
and return the related user. If the code is invalid `null` is returned.

## Overriding

Some options may need more thought than a simple attribute, the following can be overwritten by declaring methods
in your model

### To: Name

This is the name of the recipient. By default it uses the `name` attribute of the model, however you can override
it by declaring the following:

```php
...

public function getVerificationEmailName() {
    return ucwords($this->first_name .' '. $this->last_name);
}

...
```

### Email Subject

By default, the config option `subject` is used, however you can define the following method in the model:

```php
...

public function getVerificationEmailSubject()
{
    return 'Welcome to our site ' . $this->name;
}

...
```

## License

Verifier is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)