<?php

namespace common\models;

use common\components\Constants;
use common\components\CustomFunc;
use Yii;
use yii\behaviors\AttributeTypecastBehavior;
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
 * @property float $orignal_cost
 * @property int $count_type
 * @property int $price_number
 * @property int $amount
 * @property int $created_at
 * @property int|null $created_by
 * @property int $updated_at
 * @property int|null $updated_by
 * @property int|null $isDeleted
 */
class ArOrderProduct extends \common\components\BaseModel
{
    public $ready_to_deliver ;
    public $count_type_name ;

    public $items_cost ;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ar_order_product';
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
            [['count_type_name','items_cost'], 'safe'],
            [['ready_to_deliver'], 'checkReady'],
            [['product_id'], 'checkInventory'],
            [['product_id'], 'checkDuplicate'],
            [['total_product_amount', 'discount','orignal_cost'], 'double'],
        ];
    }

    public function checkInventory($attr, $params) {

        if (   isset($this->product_id)) {
            $store_id = $this->store_id ;
            $product_id = $this->product_id;

            $item_inventory_count = InventoryOrderProduct::find()->select('count')->where(['store_id'=>$store_id,'product_id'=>$product_id])->sum('count') ?? 0;

            $item_order_count = OrderProduct::find()->select('count')->where(['store_id'=>$store_id,'product_id'=>$product_id])->andWhere(['<>','order_id',$this->order_id])->sum('count')?? 0 ;

            $returned = Returns::find()->select('count')->joinWith('order')->where(['order.store_id'=>$store_id,'product_id'=>$product_id])->sum('count');
            $damaged_returned = Damaged::find()->select('count')->joinWith('order')->where(['order.store_id'=>$store_id,'product_id'=>$product_id,'status'=>Damaged::STATUS_RETURNED])->sum('count');
            $damaged_inactive = Damaged::find()->select('count')->joinWith('order')->where(['order.store_id'=>$store_id,'product_id'=>$product_id])->andWhere(['<>','status',Damaged::STATUS_REPLACED]) ->sum('count');
            $transformTo =  TransferOrder::find()->select('count')->where(['to'=>$store_id,'product_id'=>$product_id])->sum('count');
            $transformFrom =  TransferOrder::find()->select('count')->where(['from'=>$store_id,'product_id'=>$product_id])->sum('count');

            $total = $item_inventory_count +$returned -$item_order_count  +$damaged_returned - $damaged_inactive - $transformFrom+$transformTo;

            $current_count = !empty($this->count)?$this->count:0;
            if (($total - $current_count)<0) {

                $this->addError($attr, 'لا توجد هذه الكمية (الموجود هو '.$total.')');
            }
        }
    }
    public function checkReady($attr, $params) {

        if (   empty($this->ready_to_deliver) && $this->isNewRecord) {
            $this->addError($attr, 'يجب تجهيز هذه المادة');
        }
    }

    public function checkDuplicate($attr, $params) {

        if (isset($this->product_id)) {
            $item_order = ArOrderProduct::find()->where(['order_id'=>$this->order_id,'product_id'=>$this->product_id])->andWhere(['<>','id',$this->id??0])->one() ;

            if ($item_order) {
                $this->addError($attr, 'لا يمكنك اضافة نفس المادة اكثر من مرة');
            }
        }
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
            'typecast' => [
                'class' => AttributeTypecastBehavior::class,
                'attributeTypes' => [
                    'product_id' => AttributeTypecastBehavior::TYPE_INTEGER,
                    'count' => AttributeTypecastBehavior::TYPE_FLOAT,
                ],
                'typecastAfterValidate' => true,
                'typecastBeforeSave' => false,
                'typecastAfterFind' => false,
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
            'id' =>  'الرقم',
            'order_id' =>  'رقم الطلب',
            'product_id' =>  'المادة',
            'price_number' =>  'السعر',
            'amount' =>  'السعر الفردي',
            'count' =>  'العدد',
            'store_id' =>  'المحل',
            'count_type' =>  'نوع العدد',
            'created_at' =>  'تاريخ الانشاء',
            'created_by' =>  'الشخص المنشئ',
            'updated_at' =>  'تاريخ التعديل',
            'updated_by' =>  'الشخص المعدل',
            'discount' =>  'الخصم',
            'total_product_amount' =>  'السعر الاجمالي',
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

    public function getProductCountType()
    {
        return $this->product ? $this->product->getCountTypeName('count_type') : '';
    }
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    public function getPriceList()
    {
        $prices = [];
        if(Yii::$app->user->can('سعر بيع 1')){
            $prices[1]= 1;
        }
        if(Yii::$app->user->can('سعر بيع 2')){
            $prices[2]= 2;
        }
        if(Yii::$app->user->can('سعر بيع 3')){
            $prices[3]= 3;
        }
        if(Yii::$app->user->can('سعر بيع 4')){
            $prices[4]= 4;
        }


        return $prices;

    }
    public function getStoreTitle()
    {
        return Store::findOne($this->store_id)->name;
    }

}
