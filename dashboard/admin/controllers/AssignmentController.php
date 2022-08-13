<?php

namespace dashboard\admin\controllers;


use Yii;
use dashboard\admin\models\Assignment;
use dashboard\admin\models\searchs\Assignment as AssignmentSearch;
use dashboard\admin\components\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use dashboard\admin\components\MenuHelper;
use yii\web\Response;
use yii\rbac\Item;
use yii\filters\AccessControl;
use common\models\CChannelDealerAssignment;
use common\models\CChannelAssignment;
use common\models\CChannelCostAssignment;
use common\models\CDealer;
use common\models\CChannel;
/**
 * AssignmentController implements the CRUD actions for Assignment model.
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class AssignmentController extends BaseController
{
    public $userClassName;
    public $idField = 'id';
    public $usernameField = 'username';
    public $fullnameField;
    public $searchClass;
    public $extraColumns = [];

    
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
//            'access' => [
//                'class' => AccessControl::className(),
//                //'ruleConfig' => [
//                //    'class' => AdministrationRule::className(),
//                //],
//                'only' => ['search','index','assign','view','assigndealer','assignchannel','searchchannel','searchdealer','dealer','channel'],
//                'rules' => [
//                    [
//                        'actions' => ['search','index','assign','view' ,'assigndealer','assignchannel','assignprice','searchchannel','searchdealer','dealer','channel'],
//                        'allow' => true,
//                        'roles' => [
//                            'Admin',
//                        ],
//                    ],
//                    [
//                        'actions' => ['search','assign','view','index','assigndealer','assignchannel','assignprice','searchchannel','searchdealer','dealer','channel'],
//                        'allow' => false,
//                        'roles' => ['?'],
//                    ],
//                ],
//            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->userClassName === null) {
            $this->userClassName = Yii::$app->getUser()->identityClass;
            $this->userClassName = $this->userClassName ? : 'common\models\User';
        }
    }

    /**
     * Lists all Assignment models.
     * @return mixed
     */
    public function actionIndex()
    {

        if ($this->searchClass === null) {
            $searchModel = new AssignmentSearch;
            $dataProvider = $searchModel->search(\Yii::$app->request->getQueryParams(), $this->userClassName, $this->usernameField);
        } else {
            $class = $this->searchClass;
            $searchModel = new $class;
            $dataProvider = $searchModel->search(\Yii::$app->request->getQueryParams());
        }

        return $this->render('index', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'idField' => $this->idField,
                'usernameField' => $this->usernameField,
                'extraColumns' => $this->extraColumns,
        ]);
    }

    /**
     * Displays a single Assignment model.
     * @param  integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', [
                'model' => $model,
                'idField' => $this->idField,
                'usernameField' => $this->usernameField,
                'fullnameField' => $this->fullnameField,
        ]);
    }

     public function actionDealer($id)
    {
        $model = $this->findModel($id);

        return $this->render('dealer', [
                'model' => $model,
                'idField' => $this->idField,
                'usernameField' => $this->usernameField,
                'fullnameField' => $this->fullnameField,
        ]);
    }
    
    
    public function actionChannel($id)
    {
        $model = $this->findModel($id);

        return $this->render('channel', [
                'model' => $model,
                'idField' => $this->idField,
                'usernameField' => $this->usernameField,
                'fullnameField' => $this->fullnameField,
        ]);
    }
        
    public function actionPrice($id)
    {
        $model = $this->findModel($id);
        
        $modelCostAssignment = CChannelCostAssignment::find()->where(['=', 'user_id', $id ])->orderBy('id DESC')->one();
        
        if(isset($modelCostAssignment)){
            if ($modelCostAssignment->load(Yii::$app->request->post()) && $modelCostAssignment->save()) {

                    return $this->redirect(['/admin/assignment']);

            } else {
                return $this->render('price', [
                        'model' => $model,
                        'modelCostAssignment' => $modelCostAssignment,
                        'idField' => $this->idField,
                        'usernameField' => $this->usernameField,
                        'fullnameField' => $this->fullnameField,
                ]);
            }
        } else {
            
            $modelCostAssignment = new CChannelCostAssignment();
            $modelCostAssignment->user_id = $id;
            
            if ($modelCostAssignment->load(Yii::$app->request->post()) && $modelCostAssignment->save()) {

                    return $this->redirect(['/admin/assignment']);

            } else {
                return $this->render('price', [
                        'model' => $model,
                        'modelCostAssignment' => $modelCostAssignment,
                        'idField' => $this->idField,
                        'usernameField' => $this->usernameField,
                        'fullnameField' => $this->fullnameField,
                ]);
            }
        }

    }
    /**
     * Assign or revoke assignment to user
     * @param  integer $id
     * @param  string  $action
     * @return mixed
     */
    public function actionAssign()
    {
        $post = Yii::$app->request->post();
        $id = $post['id'];
        $action = $post['action'];
        $roles = $post['roles'];
        $manager = Yii::$app->authManager;
        $error = [];
        if ($action == 'assign') {
            foreach ($roles as $name) {
                try {
                    $item = $manager->getRole($name);
                    $item = $item ? : $manager->getPermission($name);
                    $manager->assign($item, $id);
                } catch (\Exception $exc) {
                    $error[] = $exc->getMessage();
                }
            }
        } else {
            foreach ($roles as $name) {
                try {
                    $item = $manager->getRole($name);
                    $item = $item ? : $manager->getPermission($name);
                    $manager->revoke($item, $id);
                } catch (\Exception $exc) {
                    $error[] = $exc->getMessage();
                }
            }
        }
        MenuHelper::invalidate();
        Yii::$app->response->format = Response::FORMAT_JSON;
        return[
            'type' => 'S',
            'errors' => $error,
        ];
    }
    
    /**
     * Assign or revoke assignment to user
     * @param  integer $id
     * @param  string  $action
     * @return mixed
     */
    public function actionAssigndealer()
    {
        $post = Yii::$app->request->post();
        $id = $post['id'];
        $action = $post['action'];
        $roles = $post['roles'];               
        
        $error = [];
        if ($action == 'assign') {
            foreach ($roles as $name) {
                try {
                    
                    $assignment = new CChannelDealerAssignment();
                    $assignment->user_id = $id;
                    $assignment->dealer_id = $name;
                    $assignment->save();
                    
                } catch (\Exception $exc) {
                    $error[] = $exc->getMessage();
                }
            }
        } else {
            foreach ($roles as $name) {
                try {
                   
                    $assignment = CChannelDealerAssignment::find()->where('user_id = '.$id.' and dealer_id = '.$name)->one();
                    $assignment->delete();
                    
                } catch (\Exception $exc) {
                    $error[] = $exc->getMessage();
                }
            }
        }
        MenuHelper::invalidate();
        Yii::$app->response->format = Response::FORMAT_JSON;
        return[
            'type' => 'S',
            'errors' => $error,
        ];
    }
    
    /**
     * Assign or revoke assignment to user
     * @param  integer $id
     * @param  string  $action
     * @return mixed
     */
    public function actionAssignchannel()
    {
        $post = Yii::$app->request->post();
        $id = $post['id'];
        $action = $post['action'];
        $roles = $post['roles'];
        $manager = Yii::$app->authManager;
        $error = [];
        if ($action == 'assign') {
            foreach ($roles as $name) {
                try {
                   
                    $assignment = new CChannelAssignment();
                    $assignment->user_id = $id;
                    $assignment->channel_id  = $name;
                    $assignment->save();
                    
                } catch (\Exception $exc) {
                    $error[] = $exc->getMessage();
                }
            }
        } else {
            foreach ($roles as $name) {
                try {
                    $assignment = CChannelAssignment::find()->where('user_id = '.$id.' and channel_id = '.$name)->one();
                    $assignment->delete();
                    
                } catch (\Exception $exc) {
                    $error[] = $exc->getMessage();
                }
            }
        }
        MenuHelper::invalidate();
        Yii::$app->response->format = Response::FORMAT_JSON;
        return[
            'type' => 'S',
            'errors' => $error,
        ];
    }

    /**
     * Search roles of user
     * @param  integer $id
     * @param  string  $target
     * @param  string  $term
     * @return string
     */
    public function actionSearch($id, $target, $term = '')
    {
        Yii::$app->response->format = 'json';
        $authManager = Yii::$app->authManager;
        $roles = $authManager->getRoles();
        $permissions = $authManager->getPermissions();

        $avaliable = [];
        $assigned = [];
        foreach ($authManager->getAssignments($id) as $assigment) {
            if (isset($roles[$assigment->roleName])) {
                if (empty($term) || strpos($assigment->roleName, $term) !== false) {
                    $assigned['Dealers'][$assigment->roleName] = $assigment->roleName;
                }
                unset($roles[$assigment->roleName]);
            } elseif (isset($permissions[$assigment->roleName]) && $assigment->roleName[0] != '/') {
                if (empty($term) || strpos($assigment->roleName, $term) !== false) {
                    $assigned['Dealers'][$assigment->roleName] = $assigment->roleName;
                }
                unset($permissions[$assigment->roleName]);
            }
        }

        if ($target == 'avaliable') {
            if (count($roles)) {
                foreach ($roles as $role) {
                    if (empty($term) || strpos($role->name, $term) !== false) {
                        $avaliable['Dealers'][$role->name] = $role->name;
                    }
                }
            }
            if (count($permissions)) {
                foreach ($permissions as $role) {
                    if ($role->name[0] != '/' && (empty($term) || strpos($role->name, $term) !== false)) {
                        $avaliable['Dealers'][$role->name] = $role->name;
                    }
                }
            }
            return $avaliable;
        } else {
            return $assigned;
        }
    }
    /**
     * Search roles of user
     * @param  integer $id
     * @param  string  $target
     * @param  string  $term
     * @return string
     */
    public function actionSearchdealer($id, $target, $term = '')
    {
        Yii::$app->response->format = 'json';
        $dealers_assigned = CChannelDealerAssignment::find()->where('user_id = '.$id)->all();
       
        $avaliable = [];
        $assigned = [];
        
        foreach($dealers_assigned as $dealer) {
            
            if (isset($dealer->dealer))
                $assigned['Dealers'][$dealer->dealer->id] = $dealer->dealer->company_name;    
            
            $ids[] = $dealer->dealer->id;
        }

        if(isset($ids) && count($ids) > 0){
            $ids = implode(',', $ids);
            $dealer_available = CDealer::find()->where('active = 1 and id not in ('.$ids.')')->all();
            
        }else{
            
            $dealer_available = CDealer::find()->where('active = 1')->all();
            
        }
        
        
        
        if ($target == 'avaliable') {
            foreach($dealer_available as $dealer)                
                $avaliable['Dealers'][$dealer->id] = $dealer->company_name;                
            
            return $avaliable;
        } else {
            return $assigned;
        }
    }
    /**
     * Search roles of user
     * @param  integer $id
     * @param  string  $target
     * @param  string  $term
     * @return string
     */
    public function actionSearchchannel($id, $target, $term = '')
    {
        Yii::$app->response->format = 'json';
        $dealers_assigned = CChannelAssignment::find()->where('user_id = '.$id)->all();
        $dealer_available = CChannel::find()->where('active = 1')->all();
        $avaliable = [];
        $assigned = [];
        
        foreach($dealers_assigned as $dealer) {
            
            if (isset($dealer->channel))
                $assigned['Dealers'][$dealer->channel->id] = $dealer->channel->name;   
            
            $ids[] = $dealer->channel->id;
        }

        if(isset($ids) && count($ids) > 0){
            $ids = implode(',', $ids);
            $dealer_available = CChannel::find()->where('active = 1 and id not in ('.$ids.')')->all();
            
        }else{
            $dealer_available = CChannel::find()->where('active = 1')->all();                        
        }

        if ($target == 'avaliable') {
            foreach($dealer_available as $dealer)                
                $avaliable['Dealers'][$dealer->id] = $dealer->name;                
            
            return $avaliable;
        } else {
            return $assigned;
        }
    }

    
    
    
    /**
     * Finds the Assignment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param  integer $id
     * @return Assignment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $class = $this->userClassName;
        if (($model = $class::findIdentity($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}