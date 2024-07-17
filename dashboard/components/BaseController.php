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
                        'actions' => [],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'timeValidation' => [
                'class' => TimeValidationBehavior::class,
                'except' => ['login', 'logout', 'error'], // Exclude these actions from the time validation
            ],
        ];
    }
}