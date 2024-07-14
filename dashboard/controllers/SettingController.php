<?php

namespace dashboard\controllers;

use Yii;
use common\models\ConfigurationForm;
use dashboard\components\BaseController;
use yii\debug\models\search\User;
use yii\filters\VerbFilter;

class SettingController extends BaseController
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionRate(){
        $settings = Yii::$app->settings;
        $model =  new ConfigurationForm();
        $model->price_profit_rate_1 = $settings->get('price', 'price_profit_rate_1');
        $model->price_profit_rate_2 = $settings->get('price', 'price_profit_rate_2');
        $model->price_profit_rate_3 = $settings->get('price', 'price_profit_rate_3');
        $model->price_profit_rate_4 = $settings->get('price', 'price_profit_rate_4');
        if(Yii::$app->request->isPost )
        {
            $model->load(Yii::$app->request->bodyParams);
            if($model->validate())
            {
                $settings->set('price', 'price_profit_rate_1',$model->price_profit_rate_1);
                $settings->set('price', 'price_profit_rate_2',$model->price_profit_rate_2);
                $settings->set('price', 'price_profit_rate_3',$model->price_profit_rate_3);
                $settings->set('price', 'price_profit_rate_4',$model->price_profit_rate_4);
                Yii::$app->session->setFlash('success', "تم تعدبل النسب");

            }
        }

        return $this->render('rate', [
            'model'=>$model
        ]);
    }
}