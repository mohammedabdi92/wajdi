<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

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
            [['order_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['product_id', 'count', 'amount'], 'number'],
            [[ 'product_id', 'order_id', 'amount', 'count' ,'returner_name'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {

        return [
            'id' => Yii::t('app', 'الرقم'),
            'order_id' => Yii::t('app', 'الطلب'),
            'product_id' => Yii::t('app', 'المادة'),
            'returner_name' => Yii::t('app', 'الشخص المرجع'),
            'note' => Yii::t('app', 'ملاحظة'),
            'count' => Yii::t('app', 'العدد'),
            'amount' => Yii::t('app', 'القيمة'),
            'created_at' => Yii::t('app', 'تاريخ الانشاء'),
            'created_by' => Yii::t('app', 'الشخص المنشئ'),
            'updated_at' => Yii::t('app', 'تاريخ التعديل'),
            'updated_by' => Yii::t('app', 'الشخص المعدل'),
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
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
    public function getProductTitle()
    {
        return $this->product ? $this->product->title : '';
    }
}
