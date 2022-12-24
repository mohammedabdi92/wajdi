<?php

namespace common\models;

use common\components\Constants;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * This is the model class for table "shortage".
 *
 * @property int $id
 * @property string|null $note
 * @property int|null $store_id
 * @property int|null $product_id
 * @property int|null $count
 * @property int $created_at
 * @property int|null $created_by
 * @property int $updated_at
 * @property int|null $updated_by
 */
class Shortage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shortage';
    }
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            BlameableBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['note'], 'string'],
            [['store_id', 'product_id', 'count'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'الرقم',
            'note' => 'الملاحظة',
            'store_id' => 'المحل',
            'product_id' => 'المادة',
            'count' => 'العدد',
            'created_at' =>  'تاريخ الانشاء',
            'created_by' =>  'الشخص المنشئ',
            'updated_at' =>  'تاريخ التعديل',
            'updated_by' =>  'الشخص المعدل',
        ];
    }
    public function getStoreTitle()
    {
        return Constants::getStoreName($this->store_id);
    }
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
    public function getProductTitle()
    {
        return $this->product ? $this->product->title : '';
    }
}
