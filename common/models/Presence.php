<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "presence".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $time
 * @property int|null $type
 */
class Presence extends \yii\db\ActiveRecord
{
    const TYPE_IN = 1;
    const TYPE_OUT = 2;
    const typesArray = [
        self::TYPE_IN=>"دخول",
        self::TYPE_OUT=>"خروج",
    ];
    public  function getTypeText(){
        return self::typesArray[$this->type];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'presence';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'type'], 'integer'],
            [['time'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'الرقم'),
            'user_id' => Yii::t('app', 'المستخدم'),
            'time' => Yii::t('app', 'الوقت'),
            'type' => Yii::t('app', 'نوع البصمة'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\PresenceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\PresenceQuery(get_called_class());
    }
}
