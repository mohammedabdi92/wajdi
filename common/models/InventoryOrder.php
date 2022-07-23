<?php

namespace common\models;

use common\components\Constants;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use yii2tech\ar\softdelete\SoftDeleteQueryBehavior;

/**
 * This is the model class for table "inventory_order".
 *
 * @property int $id
 * @property int $supplier_id
 * @property int $store_id
 * @property float $total_cost
 * @property float $total_count
 * @property int $created_at
 * @property int|null $inventory_order_id
 * @property int|null $inventory_order_date
 * @property int|null $created_by
 * @property int $updated_at
 * @property int|null $updated_by
 * @property int|null $isDeleted
 * @property float|null $tax
 * @property float|null $tax_percentage
 * @property float|null $discount_percentage
 * @property float|null $discount
 */
class InventoryOrder extends \yii\db\ActiveRecord
{
    public $supplier_name ;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inventory_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'store_id', 'total_cost'], 'required'],
            [['store_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'isDeleted'], 'integer'],
            [['tax','discount','total_cost','tax_percentage','discount_percentage'], 'double'],
            [['supplier_id'],'validateSupplierRequired'],
            [['supplier_name','inventory_order_id','inventory_order_date','total_count'],'safe']
        ];
    }

    public function validateSupplierRequired($attr) {

        if(empty($this->supplier_id) && empty($this->supplier_name))
        {
            $this->addError($attr,'يجب اختيرا مرد');
        }
    }
    public function beforeSave($insert)
    {
        if(!empty($this->supplier_name))
        {
            $supplier = new Supplier();
            $supplier->name = $this->supplier_name;
            $supplier->save();
            $this->supplier_id = $supplier->id;
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
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
            'supplier_id' => Yii::t('app', 'المورد'),
            'supplier_name' => Yii::t('app', 'اسم المورد'),
            'inventory_order_id' => Yii::t('app', 'رقم الفاتورة الورقي'),
            'inventory_order_date' => Yii::t('app', 'تاريخ الفاتورة الورقي'),
            'store_id' => Yii::t('app', 'المحل'),
            'tax' => Yii::t('app', 'قيمة الضريبة'),
            'total_count' => Yii::t('app', 'العدد الاجمالي'),
            'tax_percentage' => Yii::t('app', 'نسبة الضريبة %'),
            'discount_percentage' => Yii::t('app', ' نسبة الخصم %'),
            'discount' => Yii::t('app', 'قيمة الخصم'),
            'total_cost' => Yii::t('app', 'التكلفة الاجمالية'),
            'created_at' => Yii::t('app', 'تاريخ الانشاء'),
            'created_by' => Yii::t('app', 'الشخص المنشئ'),
            'updated_at' => Yii::t('app', 'تاريخ التعديل'),
            'updated_by' => Yii::t('app', 'الشخص المعدل'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\InventoryOrderQuery the active query used by this AR class.
     */
    public static function find()
    {
        $query = new \common\models\query\InventoryOrderQuery(get_called_class());
        $query->attachBehavior('softDelete', SoftDeleteQueryBehavior::className());
        return $query;
    }

    public function getSupplier()
    {
        return $this->hasOne(Supplier::className(), ['id' => 'supplier_id']);
    }

    public function getProducts()
    {
        return $this->hasMany(InventoryOrderProduct::className(), ['inventory_order_id' => 'id']);
    }


    public function getSupplierTitle()
    {
        return $this->supplier ? $this->supplier->name : '';
    }

    public function getStoreTitle()
    {
        return Constants::getStoreName($this->store_id);
    }

}
