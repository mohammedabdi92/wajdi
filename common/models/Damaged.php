<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "damaged".
 *
 * @property int $id
 * @property int|null $status
 * @property int|null $order_id
 * @property float|null $product_id
 * @property float|null $count
 * @property float|null $amount
 * @property int $created_at
 * @property int|null $created_by
 * @property int $updated_at
 * @property int|null $updated_by
 */
class Damaged extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_RETURNED = 3;
    const statusArray = [
        self::STATUS_ACTIVE=>"لم يتم التحديد",
        self::STATUS_INACTIVE=>"عير قابل للارجاع",
        self::STATUS_RETURNED=>"تم الارجاع",
    ];
    public  function getStatusText(){
        return self::statusArray[$this->status];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'damaged';
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
            [['status', 'order_id', 'created_by', 'updated_by'], 'integer'],
            [['product_id', 'count', 'amount'], 'number'],
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'الرقم'),
            'status' => Yii::t('app', 'الحالة'),
            'order_id' => Yii::t('app', 'الطلب'),
            'product_id' => Yii::t('app', 'المادة'),
            'count' => Yii::t('app', 'العدد'),
            'amount' => Yii::t('app', 'القيمة'),
            'created_at' => Yii::t('app', 'تاريخ الانشاء'),
            'created_by' => Yii::t('app', 'الشخص المنشئ'),
            'updated_at' => Yii::t('app', 'تاريخ التعديل'),
            'updated_by' => Yii::t('app', 'الشخص المعدل'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\DamagedQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\DamagedQuery(get_called_class());
    }
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
    public function getProductTitle()
    {
        return $this->product ? $this->product->title : '';
    }
}
