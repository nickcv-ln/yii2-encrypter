<?php

return [
    'class' => 'yii\db\Connection',
    #'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    #'username' => 'root',
    #'password' => '',
    'dsn' => 'sqlite:'.__DIR__.DIRECTORY_SEPARATOR.'../vendor/nickcv/yii2-encrypter/tests/codeception/config/test.db',
    'charset' => 'utf8',
];
