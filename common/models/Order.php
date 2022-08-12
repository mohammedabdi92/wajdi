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
 * @property float $paid
 * @property float $total_amount
 * @property float $total_count
 * @property float $total_discount
 * @property float $total_price_discount_product
 * @property float|null $debt
 * @property float|null $repayment
 * @property int $created_at
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
            [['customer_id'],'validateCustomerRequired'],
            [['total_price_discount_product','total_count','note','phone_number','customer_name'],'safe'],
            [['total_discount','total_amount_without_discount','debt','repayment','remaining','paid'], 'double'],
        ];
    }
    public function validateCustomerRequired($attr) {

        if(empty($this->customer_id) && empty($this->customer_name))
        {
            $this->addError($attr,'يجب اختيرا العميل');
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
            'customer_id' => Yii::t('app', 'العميل'),
            'phone_number' => Yii::t('app', 'رقم الهاتف'),
            'customer_name' => Yii::t('app', 'اسم العميل'),
            'store_id' => Yii::t('app', 'المحل'),
            'total_amount_without_discount' => Yii::t('app', 'السعر الاجمالي'),
            'total_amount' => Yii::t('app', 'السعر النهائي المطلوب'),
            'total_discount' => Yii::t('app', 'الخصم الاجمالي'),
            'total_count' => Yii::t('app', 'العدد الاجمالي'),
            'paid' => Yii::t('app', 'المدفوع'),
            'remaining' => Yii::t('app', 'الباقي للمبلغ للعميل'),
            'debt' => Yii::t('app', 'الدين'),
            'note' => Yii::t('app', 'ملاحظة'),
            'repayment' => Yii::t('app', 'السداد'),
            'total_price_discount_product' => Yii::t('app', 'مجموع الخصم الافرادي'),
            'created_at' => Yii::t('app', 'تاريخ الانشاء'),
            'created_by' => Yii::t('app', 'الشخص المنشئ'),
            'updated_at' => Yii::t('app', 'تاريخ التعديل'),
            'updated_by' => Yii::t('app', 'الشخص المعدل'),
        ];
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
        return Constants::getStoreName($this->store_id);
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

    public function getCustomerTitle()
    {
        return $this->customer ? $this->customer->name : '';
    }
}
