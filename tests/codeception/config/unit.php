<?php

return yii\helpers\ArrayHelper::merge(
    require(__DIR__ . DIRECTORY_SEPARATOR .'web.php'),
    [
        'components' => [
            'db' => [
                'dsn' => 'sqlite:'. __DIR__ . DIRECTORY_SEPARATOR . 'test.db',
            ],
            'mailer' => [
                'useFileTransport' => true,
            ],
            'urlManager' => [
                'showScriptName' => true,
            ],
        ],
    ]
);