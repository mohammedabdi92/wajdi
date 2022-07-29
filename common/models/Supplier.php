<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use yii2tech\ar\softdelete\SoftDeleteQueryBehavior;

/**
 * This is the model class for table "supplier".
 *
 * @property int $id
 * @property string $name
 * @property string|null $phone_number
 * @property string|null $email
 * @property string|null $phone_number_2
 * @property string|null $address
 * @property int $created_at
 * @property int|null $created_by
 * @property int $updated_at
 * @property int|null $updated_by
 * @property int|null $isDeleted
 */
class Supplier extends \common\components\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'supplier';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name' ], 'required'],
            [['email' ], 'email'],
            [['name', 'phone_number','phone_number_2', 'email', 'address'], 'string'],
            [['created_at', 'created_by', 'updated_at', 'updated_by', 'isDeleted'], 'integer'],
        ];
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
            BlameableBehavior::className(),
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
            'phone_number' => Yii::t('app', 'رقم هاتف'),
            'phone_number_2' => Yii::t('app', 'رقم هاتف بديل'),
            'email' => Yii::t('app', 'الايميل'),
            'address' => Yii::t('app', 'العنوان'),
            'created_at' => Yii::t('app', 'تاريخ الانشاء'),
            'created_by' => Yii::t('app', 'الشخص المنشئ'),
            'updated_at' => Yii::t('app', 'تاريخ التعديل'),
            'updated_by' => Yii::t('app', 'الشخص المعدل'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\SupplierQuery the active query used by this AR class.
     */
    public static function find()
    {
        $query = new \common\models\query\SupplierQuery(get_called_class());
        $query->attachBehavior('softDelete', SoftDeleteQueryBehavior::className());
        return $query;
    }
}
