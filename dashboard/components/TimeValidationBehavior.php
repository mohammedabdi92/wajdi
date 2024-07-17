<?php
namespace dashboard\components;

use Yii;
use yii\base\ActionFilter;;
use yii\base\Event;

class TimeValidationBehavior extends ActionFilter
{
 

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $model = Yii::$app->user->identity ;// Adjust as needed

            if ($model && !$model->isCurrentTimeWithinRange()) {
                Yii::$app->user->logout(); // Log out the user
                Yii::$app->session->setFlash('error', 'انت خارج اوقات العمل');
                Yii::$app->response->redirect(['site/login'])->send(); // Redirect to login page and send response
                Yii::$app->end(); // Terminate the application
            }

            return true; // Continue with the action
        }
        return false; // Skip the action if parent fails
    }

    public function validateTimes(Event $event)
    {
        $model = $event->sender; // Get the model instance
        
        if ($model->in_time && $model->out_time) {
            $inTime = strtotime($model->in_time);
            $outTime = strtotime($model->out_time);

            if ($outTime <= $inTime) {
                $model->addError('out_time', 'Out time must be after in time.');
            }
        }
    }
}
