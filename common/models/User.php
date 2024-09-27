<?php

namespace common\models;

use common\components\BaseModel;
use common\models\WorkDays;
use common\components\Constants;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * User model
 *
 * @property integer $id
 * @property integer $in_time
 * @property integer $out_time
 * @property string $username
 * @property string $full_name
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $type
 * @property integer $isDeleted
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends BaseModel implements IdentityInterface
{
    public $stores = [] ;
    public $days = [] ;
    public $password_text;
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;
    const daysArray = [
        '1'=>'الاحد',
        '2'=>'الاثنين',
        '3'=>'الثلاثاء',
        '4'=>'الاربعاء',
        '5'=>'الخميس',
        '6'=>'الجمعة',
        '7'=>'السبت',
    ] ;
    const statusArray = [
        self::STATUS_DELETED=>"محذوف",
        self::STATUS_INACTIVE=>"غير فعال",
        self::STATUS_ACTIVE=>"فعال",
        ];
    public  function getStatusText(){
        return self::statusArray[$this->status];
    }
    const ADMIN = 1;
    const ADMIN_ASSISTANT = 2;
    const EMPLOYEE = 3;
    const typeArray = [
        self::ADMIN=>"مدير",
        self::ADMIN_ASSISTANT=>"مساعد مدير",
        self::EMPLOYEE=>"موضف",
    ];
    public  function getTypeText(){
        return self::typeArray[$this->type];
    }


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    public function behaviors()
    {
        return [
            'softDeleteBehavior' => [
                'class' => SoftDeleteBehavior::className(),
                'softDeleteAttributeValues' => [
                    'isDeleted' => true
                ],
            ],
            TimestampBehavior::className(),

        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'رقم المستخدم'),
            'username' => Yii::t('app', 'اسم المستخدم'),
            'full_name' => Yii::t('app', 'الاسم'),
            'status' => Yii::t('app', 'الحالة'),
            'created_at' => Yii::t('app', 'تاريخ الانشاء'),
            'updated_at' => Yii::t('app', 'تاريخ التعديل'),
            'email' => Yii::t('app', 'الايميل'),
            'type' => Yii::t('app', 'صلاحيات'),
            'stores' => Yii::t('app', 'المحلات'),
            'in_time' => Yii::t('app', 'وقت الدخول الى المحل'),
            'out_time' => Yii::t('app', 'وقت الخروج من المحل'),
            'days' => Yii::t('app', 'ايام الدوام'),
            'password_text' => Yii::t('app', 'كلمة السر'),
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {

        return [
            [['full_name','username','type','password_text','stores','days','in_time','out_time'], 'safe'],
            [['full_name','username','type'], 'required'],
            [['email'], 'email'],
            [['email','username'], 'unique'],
            [['username'], 'trim'],
            ["password_text", "required", "on" => ['create']],
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
        ];
    }

    public function beforeSave($insert)
    {
        
        if($this->isNewRecord)
        {
            $this->generateAuthKey();
        }
        if(!empty($this->password_text))
        {
            $this->setPassword($this->password_text);
            $this->generateAuthKey();
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id,['or','status' => self::STATUS_ACTIVE, 'id'=>1]]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, ['or','status' => self::STATUS_ACTIVE, 'id'=>1]]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }


    public function afterSave($insert, $changedAttributes)
    {
        UserStore::deleteAll(['user_id'=>$this->id]);
        if(!empty($this->stores)){
            foreach ($this->stores as $store_id) {
                $userStore = new UserStore();
                $userStore->user_id = $this->id;
                $userStore->store_id = $store_id;
                $userStore->save(false);
            }
        }

        WorkDays::deleteAll(['user_id'=>$this->id]);
        if(!empty($this->days)){
            foreach ($this->days as $day) {
                $WorkDays= new WorkDays();
                $WorkDays->user_id = $this->id;
                $WorkDays->day_of_week = $day;
                $WorkDays->save(false);
            }
        }

        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }
    public function afterFind()
    {
        $Stores = [];
        $userStores=   UserStore::find()->where(['user_id'=>$this->id])->all();
        foreach ($userStores as $userStore) {
            $Stores[] = $userStore->store_id;
        }
        $this->stores =$Stores;
        $Days = [];
        $userDays=   WorkDays::find()->where(['user_id'=>$this->id])->all();
        foreach ($userDays as $userDay) {
            $Days[] = $userDay->day_of_week;
        }
        $this->days =$Days;
        parent::afterFind(); // TODO: Change the autogenerated stub
    }

    public function isCurrentTimeWithinRange()
    {
        $currentTime = time(); // Get the current time
        $currentDay = date('N', $currentTime)+1; // Get the current day of the week (1 for Monday, 7 for Sunday)
        $allowedDays = $this->days; // Example array of allowed days (Monday to Friday)
    
        if($allowedDays)
        {
            if (!in_array($currentDay, $allowedDays)) {
                return false;
            }
        }
        if (!empty($this->in_time) && !empty($this->out_time)) {
            // print_r($currentTime);die;
            $inTime = strtotime($this->in_time);
            $outTime = strtotime($this->out_time);
    
            if (!($currentTime >= $inTime && $currentTime <= $outTime)) {
                return false;
            }
        }
    
        return true;
    }
    
}
