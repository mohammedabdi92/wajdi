<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use yii2tech\ar\softdelete\SoftDeleteQueryBehavior;

/**
 * This is the model class for table "inventory_order_product".
 *
 * @property int $id
 * @property int $inventory_order_id
 * @property int $product_id
 * @property float $product_total_cost
 * @property float $product_cost
 * @property float $count
 * @property int $created_at
 * @property int|null $created_by
 * @property int $updated_at
 * @property int|null $updated_by
 * @property int|null $isDeleted
 */
class InventoryOrderProduct extends \yii\db\ActiveRecord
{
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
            [['inventory_order_id', 'product_id', 'product_total_cost', 'product_cost', 'count', 'created_at', 'updated_at'], 'required'],
            [['inventory_order_id', 'product_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'isDeleted'], 'integer'],
            [['product_total_cost', 'product_cost', 'count'], 'number'],
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
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'inventory_order_id' => Yii::t('app', 'Inventory Order ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'product_total_cost' => Yii::t('app', 'Product Total Cost'),
            'product_cost' => Yii::t('app', 'Product Cost'),
            'count' => Yii::t('app', 'Count'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'isDeleted' => Yii::t('app', 'Is Deleted'),
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
}
