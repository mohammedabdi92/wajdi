<?php

namespace common\models;

use common\components\Constants;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use yii2tech\ar\softdelete\SoftDeleteQueryBehavior;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property int $customer_id
 * @property int $store_id
 * @property float $remaining
 * @property float $order_cost
 * @property float $paid
 * @property float $total_amount
 * @property float $total_count
 * @property float $total_discount
 * @property float $total_price_discount_product
 * @property float|null $debt
 * @property float|null $repayment
 * @property int $created_at
 * @property int $product_count
 * @property string $dept_note
 * @property string $note
 * @property string $customer_name
 * @property string $phone_number
 * @property int|null $created_by
 * @property int $updated_at
 * @property int|null $updated_by
 * @property int|null $isDeleted
 * @property int|null $total_amount_without_discount
 */
class Order extends \common\components\BaseModel
{
    public $customer_name ;
    public $phone_number ;
    public $returns_amount ;
    public $customerName ;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'store_id', 'total_amount' ], 'required'],
            [['customer_id', 'store_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'isDeleted'], 'integer'],
            [['total_amount'], 'number'],
            [['product_count'], 'number'],
            [['earn_the_bill'], 'validateEarnTheBill'],
            [['customer_id'],'required','when'=>function($model){
                return empty($model->customer_name);
            }],
            [['customer_name'],'validateCustomerRequired' ],
            [['total_price_discount_product','total_count','note','phone_number','customer_name','product_count','returns_amount','dept_note','customerName','earn_the_bill','order_cost'],'safe'],
            [['total_discount','total_amount_without_discount','debt','repayment','remaining','paid'], 'double'],
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
            'id' =>  'الرقم',
            'customer_id' =>  'العميل',
            'phone_number' =>  ' رقم الهاتف جديد',
            'customer_name' =>  ' اسم العميل جديد',
            'store_id' =>  'المحل',
            'total_amount_without_discount' =>  'السعر الاجمالي',
            'total_amount' =>  'السعر النهائي المطلوب',
            'total_discount' =>  'الخصم الاجمالي',
            'total_count' =>  'عدد القطع الاجمالي',
            'paid' =>  'المدفوع',
            'remaining' =>  'الباقي للمبلغ للعميل',
            'debt' =>  'الدين',
            'note' =>  'ملاحظة',
            'repayment' =>  'السداد',
            'total_price_discount_product' =>  'مجموع الخصم الافرادي',
            'created_at' =>  'تاريخ الانشاء',
            'created_by' =>  'الشخص المنشئ',
            'updated_at' =>  'تاريخ التعديل',
            'updated_by' =>  'الشخص المعدل',
            'product_count' =>  'عدد المواد',
            'returns_amount' =>  'قيمة المرجع',
            'dept_note' =>  'ملاحظة الدين',
        ];
    }
    public function validateCustomerRequired($attr) {

        $cond = ['name'=>$this->customer_name];
        if(!empty($this->phone_number))
        {
            $cond['phone_number'] =$this->phone_number;
        }
        if(empty($this->customer_id) && empty($this->customer_name))
        {
            $this->addError('customer_id','يجب اختيرا العميل');
        }else if(Customer::find()->where($cond)->count() != 0)
        {
            $this->addError('customer_name','العميل موجود مسبقا');
        }
    }

    public function validateEarnTheBill($attr) {

        if($this->earn_the_bill < 0 && Yii::$app->user->can('منع حفظ الفاتورة الخسرانة') && empty( $this->debt))
        {
            $this->addError('earn_the_bill','لا يمكن حفظ الفاتورة الخسرانة');
            Yii::$app->session->setFlash('error', "لا يمكن حفظ الفاتورة الخسرانة"); 
        }
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\OrderQuery the active query used by this AR class.
     */
    public static function find()
    {
        $query = new \common\models\query\OrderQuery(get_called_class());
        $query->attachBehavior('softDelete', SoftDeleteQueryBehavior::className());
        return $query;
    }
    public function getStoreTitle()
    {
        return Store::findOne($this->store_id)->name;
    }
    public function beforeSave($insert)
    {

        if(!empty($this->customer_name))
        {
            $customer = new Customer();
            $customer->name = $this->customer_name;
            $customer->phone_number = $this->phone_number;

            $customer->save(false);
            $this->customer_id = $customer->id;
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
    public function getProducts()
    {
        return $this->hasMany(OrderProduct::className(), ['order_id' => 'id']);
    }
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }
    public function getStore()
    {
        return $this->hasOne(Store::className(), ['id' => 'store_id']);
    }

    public function beforeDelete()
    {
        $products =  $this->products;
        foreach ($products as $product)
        {
            $product->delete();
        }

        return parent::beforeDelete();
    }

    public function getCustomerTitle()
    {
        return $this->customer ? $this->customer->name : '';
    }

    public function updateOrderCost()
    {
        $order_cost = OrderProduct::find()->where(['order_id'=> $this->id])->sum('items_cost');
        Order::updateAll(['order_cost' => $order_cost],['id' => $this->id]);
    }
}
