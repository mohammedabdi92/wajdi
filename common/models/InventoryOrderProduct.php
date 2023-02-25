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
 * This is the model class for table "inventory_order_product".
 *
 * @property int $id
 * @property int $inventory_order_id
 * @property int $product_id
 * @property int $store_id
 * @property float $product_total_cost
 * @property float $product_cost
 * @property float $count
 * @property int $created_at
 * @property int|null $created_by
 * @property int $updated_at
 * @property int|null $updated_by
 * @property int|null $isDeleted
 */
class InventoryOrderProduct extends \common\components\BaseModel
{
    public $ready_to_deliver ;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inventory_order_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'product_id', 'product_total_cost', 'product_cost', 'count' ], 'required'],
            [['inventory_order_id', 'product_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'isDeleted','store_id'], 'integer'],
            [[ 'count','tax_percentage'], 'number'],
            [['ready_to_deliver'], 'checkReady'],
            [[ 'store_id'], 'safe'],
            [['product_total_cost', 'product_cost','product_total_cost_final','product_cost_final'], 'double'],
        ];
    }
    public function checkReady($attr, $params) {

        if (   empty($this->ready_to_deliver) && $this->isNewRecord) {
            $this->addError($attr, 'يجب استلام هذه المادة');
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

    public function afterSave($insert, $changedAttributes)
    {
        $old_product = $changedAttributes['product_id']??null;
        CustomFunc::calculateProductCount($this->store_id,$this->product_id,$old_product);

        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }
    public function afterDelete()
    {
        CustomFunc::calculateProductCount($this->store_id,$this->product_id);
        parent::afterDelete(); // TODO: Change the autogenerated stub
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'الرقم'),
            'inventory_order_id' => Yii::t('app', 'رقم طلب المشتريات'),
            'product_id' => Yii::t('app', 'المادة'),
            'store_id' => Yii::t('app', 'المحل'),
            'product_total_cost' => Yii::t('app', 'السعر الاجمالي'),
            'product_cost_final' => Yii::t('app', 'سعر الوحدة النهائي'),
            'product_total_cost_final' => Yii::t('app', 'السعر الاجمالي النهائي'),
            'product_cost' => Yii::t('app', 'سعر الوحدة'),
            'count' => Yii::t('app', 'العدد'),
            'created_at' => Yii::t('app', 'تاريخ الانشاء'),
            'created_by' => Yii::t('app', 'الشخص المنشئ'),
            'updated_at' => Yii::t('app', 'تاريخ التعديل'),
            'updated_by' => Yii::t('app', 'الشخص المعدل'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\InventoryOrderProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        $query = new \common\models\query\InventoryOrderProductQuery(get_called_class());
        $query->attachBehavior('softDelete', SoftDeleteQueryBehavior::className());
        return $query;
    }
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
    public function getProductCountType()
    {
        return $this->product ? $this->product->getCountTypeName('count_type') : '';
    }
    public function getProductTitle()
    {
        return $this->product ? $this->product->title : '';
    }
    public function getInventoryOrder()
    {
        return $this->hasOne(InventoryOrder::className(), ['id' => 'inventory_order_id']);
    }

    public function getStoreTitle()
    {
        return Store::findOne($this->store_id)->name;
    }
}
