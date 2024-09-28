<?php
namespace dashboard\components;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use dashboard\components\TimeValidationBehavior;

class BaseController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login','login-without-password'],
                        'allow' => true,
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    
                    if (Yii::$app->user->isGuest) {
                        return Yii::$app->response->redirect(['site/login']);
                    } else {
                        throw new \yii\web\ForbiddenHttpException('You are not allowed to access this page');
                    }
                },
            ],
            'timeValidation' => [
                'class' => TimeValidationBehavior::class,
                'except' => ['login', 'logout', 'error'], // Exclude these actions from the time validation
            ],
        ];
    }
}