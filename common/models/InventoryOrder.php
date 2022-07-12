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
 * @property int $created_at
 * @property int|null $created_by
 * @property int $updated_at
 * @property int|null $updated_by
 * @property int|null $isDeleted
 */
class InventoryOrder extends \yii\db\ActiveRecord
{
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
            [['supplier_id', 'store_id', 'total_cost'], 'required'],
            [['supplier_id', 'store_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'isDeleted'], 'integer'],
            [['total_cost'], 'number'],
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
            'supplier_id' => Yii::t('app', 'المورد'),
            'store_id' => Yii::t('app', 'المحل'),
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
