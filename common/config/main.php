<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => false, // Set to false to send real emails
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.office365.com', // Microsoft 365 SMTP server
                'username' => 'info@wajdi.top', // Your Microsoft 365 email address
                'password' => 'qusayjamilsham123', // Your Microsoft 365 email password
                'port' => '587', // Port for TLS
                'encryption' => 'tls', // Use TLS encryption
            ],
        ],
    ],
];
