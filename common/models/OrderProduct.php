<?php

namespace common\models;

use common\components\Constants;
use common\components\CustomFunc;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use yii2tech\ar\softdelete\SoftDeleteQueryBehavior;

/**
 * This is the model class for table "order_product".
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property int $store_id
 * @property float $count
 * @property float $total_product_amount
 * @property float $discount
 * @property int $count_type
 * @property int $price_number
 * @property int $amount
 * @property int $created_at
 * @property int|null $created_by
 * @property int $updated_at
 * @property int|null $updated_by
 * @property int|null $isDeleted
 */
class OrderProduct extends \common\components\BaseModel
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'count', 'price_number', 'amount'], 'required'],
            [['order_id', 'product_id', 'count_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'isDeleted','store_id'], 'integer'],
            [['count'], 'number'],
            [['product_id'], 'checkInventory'],
            [['product_id'], 'checkDuplicate'],
            [['total_product_amount', 'discount'], 'double'],
        ];
    }

    public function checkInventory($attr, $params) {

        if (   isset($this->product_id)) {
            $item_inventory_count = InventoryOrderProduct::find()->where(['store_id'=>$this->store_id,'product_id'=>$this->product_id])->sum('count') ?? 0;
            $item_order_count = OrderProduct::find()->where(['store_id'=>$this->store_id,'product_id'=>$this->product_id])->andWhere(['<>','id',$this->id??0])->sum('count')?? 0 ;

            $total = $item_inventory_count- $item_order_count;

            if (($total-$this->count)<0) {

                $this->addError($attr, 'لا توجد هذه الكمية (الموجود هو '.$total.')');
            }
        }
    }

    public function checkDuplicate($attr, $params) {

        if (isset($this->product_id)) {
            $item_order = OrderProduct::find()->where(['order_id'=>$this->order_id,'product_id'=>$this->product_id])->andWhere(['<>','id',$this->id??0])->one() ;

            if ($item_order) {
                $this->addError($attr, 'لا يمكنك اضافة نفس المادة اكثر من مرة');
            }
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        CustomFunc::calculateProductCount($this->store_id, $this->product_id);
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
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
    public function beforeValidate()
    {
        if (!empty($this->price_number) && !empty($this->product)) {
            $price_feild = 'price_' . $this->price_number;
            $this->amount = $this->product->$price_feild;
        }
        return parent::beforeValidate(); // TODO: Change the autogenerated stub
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'الرقم'),
            'order_id' => Yii::t('app', 'رقم الطلب'),
            'product_id' => Yii::t('app', 'المادة'),
            'price_number' => Yii::t('app', 'السعر'),
            'amount' => Yii::t('app', 'السعر الفردي'),
            'count' => Yii::t('app', 'العدد'),
            'store_id' => Yii::t('app', 'المحل'),
            'count_type' => Yii::t('app', 'نوع العدد'),
            'created_at' => Yii::t('app', 'تاريخ الانشاء'),
            'created_by' => Yii::t('app', 'الشخص المنشئ'),
            'updated_at' => Yii::t('app', 'تاريخ التعديل'),
            'updated_by' => Yii::t('app', 'الشخص المعدل'),
            'discount' => Yii::t('app', 'الخصم'),
            'total_product_amount' => Yii::t('app', 'السعر الاجمالي'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\OrderProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        $query = new \common\models\query\OrderProductQuery(get_called_class());
        $query->attachBehavior('softDelete', SoftDeleteQueryBehavior::className());
        return $query;
    }

    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
    public function getProductTitle()
    {
        return $this->product ? $this->product->title : '';
    }
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    public function getPriceList()
    {
        if ($this->product) {
            return [
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4,
            ];
        }
        return [];
    }
    public function getStoreTitle()
    {
        return Constants::getStoreName($this->store_id);
    }

}
