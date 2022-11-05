<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-dashboard',
    'timezone' => 'Asia/Bahrain',
    // set target language to be Russian
    'language' => 'ar',

    // set source language to be English
    'sourceLanguage' => 'en-US',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'dashboard\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'settings' => [
            'class' => 'yii2mod\settings\Module',
        ],
        'gridview' => ['class' => 'kartik\grid\Module'],
//        'admin' => [
//            'class' => 'mdm\admin\Module',
//            'layout' => 'left-menu', // it can be '@path/to/your/layout'.
//            'controllerMap' => [
//                'assignment' => [
//                    'class' => 'mdm\admin\controllers\AssignmentController',
//                    'userClassName' => 'common\models\User',
//                    'idField' => 'id'
//                ],
//            ],
//            'menus' => [
//                'assignment' => [
//                    'label' => 'Grand Access' // change label
//                ],
//                'route' => null, // disable menu route
//            ]
//        ],
        'admin' => [
            'class' => 'dashboard\admin\Module',
            'layout' => 'left-menu',
            'mainLayout' => '@app/views/layouts/main.php',
            'controllerMap' => [
                'assignment' => [
                    'class' => 'dashboard\admin\controllers\AssignmentController',
                    'userClassName' => 'common\models\User',  // fully qualified class name of your User model
                    'idField' => 'id',        // id field of your User model that corresponds to Yii::$app->user->id
                    'usernameField' => 'username', // username field of your User model
                    //'searchClass' => 'app\models\Admin'    // fully qualified class name of your User model for searching
                ]
            ],
        ],
    ],
    'defaultRoute' => 'site/index',
    'components' => [
        'authManager' => [
            'class' => 'dashboard\admin\components\DbManager', // or use 'yii\rbac\DbManager'
        ],
        'request' => [
            'csrfParam' => '_csrf-dashboard',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-dashboard', 'httpOnly' => true],
        ],
        'settings' => [
            'class' => 'yii2mod\settings\components\Settings',
        ],
        'i18n' => [
            'translations' => [
                'yii2mod.settings' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@yii2mod/settings/messages',
                ],
                'kvexport' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@vendor/kartik-v/yii2-export/messages',
                ],
            ],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the dashboard
            'name' => 'advanced-dashboard',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'reports/<action:\w+>' => 'reports/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
            ],
        ],

    ],
    'params' => $params,
];
