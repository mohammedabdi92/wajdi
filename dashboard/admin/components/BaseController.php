<?php

    namespace dashboard\admin\components;
    
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

        if(Yii::$app->user->isGuest  && $action->id != 'login' )
        {
            $user = \Yii::$app->user;
            $user->loginRequired();
            return false;
        }
        return parent::beforeAction($action);
    }
}