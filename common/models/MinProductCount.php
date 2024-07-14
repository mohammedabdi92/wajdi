<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "min_product_count".
 *
 * @property int $id
 * @property int|null $product_id
 * @property int|null $store_id
 * @property float|null $count
 */
class MinProductCount extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'min_product_count';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'store_id'], 'integer'],
            [['count'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'store_id' => 'Store ID',
            'count' => 'Count',
        ];
    }
}
