<?php

    namespace dashboard\admin\components;
    
    use common\models\Notification;
    use Yii;
    use yii\filters\AccessControl;
    use yii\filters\VerbFilter;

/**
 * BaseController is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class BaseController extends \yii\web\Controller
{   
    public function beforeAction($action)
    {
        if(!Yii::$app->user->isGuest)
        {
            $notifications =  Notification::find()->where(['user_id'=>\Yii::$app->user->identity->id])->orderBy(" created_at DESC")->limit(10)->all();
            $notifications_count =  Notification::find()->where(['user_id'=>\Yii::$app->user->identity->id ])->andWhere(['<>','is_seen', 1])->count();
            Yii::$app->params['notifications_count'] = $notifications_count;
            Yii::$app->params['notifications'] = $notifications;

            // save last access date to check active admins
            $session = Yii::$app->session;
            if (!$session->has('UPDATE_LAST_ACCESS_DATE')) {
                try {
                    Yii::$app->db->createCommand()
                        ->update('admin', ['last_access_date' => date("Y-m-d H:i:s")], ['id' => \Yii::$app->user->identity->id])
                        ->execute();
                } catch (\Exception $e) {
                    // ignore
                }
                $session->set('UPDATE_LAST_ACCESS_DATE', 'DONE');
            }

        }


        if(Yii::$app->user->isGuest  && $action->id != 'login' )
        {
            $user = \Yii::$app->user;
            $user->loginRequired();
            return false;
        }
        return parent::beforeAction($action);
    }
}