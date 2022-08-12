<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "returns".
 *
 * @property int $id
 * @property int|null $order_id
 * @property float|null $product_id
 * @property float|null $count
 * @property float|null $amount
 * @property int $created_at
 * @property int|null $created_by
 * @property int $updated_at
 * @property int|null $updated_by
 */
class Returns extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'returns';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['product_id', 'count', 'amount'], 'number'],
            [['created_at', 'updated_at'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_id' => Yii::t('app', 'Order ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'count' => Yii::t('app', 'Count'),
            'amount' => Yii::t('app', 'Amount'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\ReturnsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\ReturnsQuery(get_called_class());
    }
}
