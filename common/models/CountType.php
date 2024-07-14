<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * This is the model class for table "count_type".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $status
 * @property int $created_at
 * @property int|null $created_by
 * @property int $updated_at
 * @property int|null $updated_by
 */
class CountType extends \common\components\BaseModel
{
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;
    const statusArray = [
        self::STATUS_ACTIVE=>"فعال",
        self::STATUS_INACTIVE=>"غير فعال",
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'count_type';
    }
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            BlameableBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'الرقم'),
            'name' => Yii::t('app', 'الاسم'),
            'status' => Yii::t('app', 'الحالة'),
            'created_at' => Yii::t('app', 'تاريخ الانشاء'),
            'created_by' => Yii::t('app', 'الشخص المنشئ'),
            'updated_at' => Yii::t('app', 'تاريخ التعديل'),
            'updated_by' => Yii::t('app', 'الشخص المعدل'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\CouQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\CouQuery(get_called_class());
    }
    public  function getStatusText(){
        return self::statusArray[$this->status];
    }
}
