<?php

namespace common\models;

use common\components\CustomFunc;
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
    const STATUS_REPLACED = 4;
    const statusArray = [
        self::STATUS_ACTIVE=>"لم يتم التحديد",
        self::STATUS_INACTIVE=>"غير قابل للارجاع",
        self::STATUS_RETURNED=>"تم الارجاع",
        self::STATUS_REPLACED=>"تم التبديل",
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
            [['product_id', 'count', 'order_id'], 'required'],
            [['status', 'order_id', 'created_by', 'updated_by'], 'integer'],
            [['product_id', 'count', 'amount'], 'number'],
            [['count'], 'checkOrderCount'],
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
        ];
    }

    public function checkOrderCount($attr, $params) {

        if ($this->isNewRecord ) {

            $order_product = OrderProduct::find()->where(['order_id'=>$this->order_id,'product_id'=>$this->product_id])->one();
            $returned = Returns::find()->where(['order_id'=>$this->order_id,'product_id'=>$this->product_id])->sum('count');
            $damaged = Damaged::find()->where(['order_id'=>$this->order_id,'product_id'=>$this->product_id])->sum('count');

            if($order_product){
                $total = $order_product->count;
                $total -= $returned??0;
                $total -= $damaged??0;
                if ( ($total - $this->count)<0) {

                    $this->addError($attr, 'لا توجد هذه الكمية (الموجود هو '.$total.')');
                }
            }

        }
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
    public function beforeSave($insert)
    {

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
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
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
    public function getProductTitle()
    {
        return $this->product ? $this->product->title : '';
    }
    public function afterSave($insert, $changedAttributes)
    {
        $order = $this->order ;
        CustomFunc::calculateProductCount($order->store_id,$this->product_id);

        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }
    public function afterDelete()
    {
        $order = $this->order ;
        CustomFunc::calculateProductCount($order->store_id,$this->product_id);
        parent::afterDelete(); // TODO: Change the autogenerated stub
    }
}
