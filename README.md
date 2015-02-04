Openssl Encrypter for Yii2
======================
Openssl Encrypter for Yii2
Version 1.1.0

This extension is used for two-way encryption.
The cypher method used is **AES**, and you can either use the **128 bites** or **256 bites** encryption.

You can also decide whether you want to use base64 encoding on the encrypted string to make it easier to store it, keeping in mind that the additional encoding will always increase the size of the string.

Openssl has been used in place of mcrypt because of its sheer speed in the encryption and decryption process (**up to 30 times faster**).


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist nickcv/yii2-encrypter "*"
```

or add

```
"nickcv/yii2-encrypter": "*"
```

to the require section of your `composer.json` file.


Set Up
------

Once the extension is installed you can either add manually the configuration in the ```web.php``` file or you can use the command line installer that will randomly generate your encryption password and IV.

**Manual Installation**
_______________________

Add the following lines in the ```components``` section of your ```web.php``` file.

```
return [
    'class'=>'\nickcv\encrypter\components\Encrypter',
    'globalPassword'=>'YourPassword',
    'iv'=>'YourIV',
    'useBase64Encoding'=>true,
    'use256BitesEncoding'=>false,
];
```

I recommend using base64 encoding to make it easier to store the encrypted string.

**The IV must always be 16 bites long**, keep that in mind in case you use multibyte characters.

_______________________

**Automatic Installation**
_______________________

Modify your ```console.php``` config file, adding the encrypter to the list of bootstrapped modules

```
'bootstrap' => ['log','encrypter'],
```

Add then the module to the module list

```
'modules' => [
    'encrypter' => 'nickcv\encrypter\Module',
],
```

At this point you will be able to simply execute from the root of your app directory the command ```./yii encrypter/setup```

The command will automatically generate the config file ```encrypter.php``` in your application ```config``` directory. The generated config file will contain a randomly generated password and IV.

You will now only have to add the extension to your ```web.php``` config file

```
'encrypter' => require(__DIR__ . DIRECTORY_SEPARATOR . 'encrypter.php'),
```

_______________________

Basic Usage
-----

You can now use the encrypter manually in any part of the application to either encrypt a string

```
\Yii::$app->encrypter->encrypt('string to encrypt');
```

or decrypt and encrypted string

```
\Yii::$app->encrypter->decrypt('string to decrypt');
```


Behavior
--------

The extension also comes with a behavior that you can easily attach to any ActiveRecord Model.

Use the following syntax to attach the behavior.

```
public function behaviors()
{
    return [
        'encryption' => [
            'class' => '\nickcv\encrypter\behaviors\EncryptionBehavior',
            'attributes' => [
                'attributeName',
                'otherAttributeName',
            ],
        ],
    ];
}
```

The behavior will automatically encrypt all the data before saving it on the database and decrypt it after the retrieve.

**Keep in mind that the behavior will use the current configuration of the extension for the encryption.**

Console Commands
----------------

If the console module is installed you can also use the ```./yii encrypter/encrypt``` and ```./yii encrypter/decrypt``` console commands.  

To find out how to install the console module follow the "**Automatic Installation**" instructions in this document.

Unit Testing
------------

The entire extension was built with TDD.
To launch the tests just go inside the extension directory and execute the ```codecept run``` command.

Warnings
--------

It is extremely hard to decrypt the data without the password and IV, keep a copy of them to avoid losing all your data.

**Two-way encryption should not be used to store passwords: you should use a one-way encryption function like sha1 and a SALT**