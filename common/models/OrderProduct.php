<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use yii2tech\ar\softdelete\SoftDeleteQueryBehavior;

/**
 * This is the model class for table "order_product".
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property float $count
 * @property float $total_product_amount
 * @property float $discount
 * @property int $count_type
 * @property int $price_number
 * @property int $amount
 * @property int $created_at
 * @property int|null $created_by
 * @property int $updated_at
 * @property int|null $updated_by
 * @property int|null $isDeleted
 */
class OrderProduct extends \common\components\BaseModel
{
    public $price_number;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'product_id', 'count', 'price_number', 'amount'], 'required'],
            [['order_id', 'product_id', 'count_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'isDeleted'], 'integer'],
            [['count'], 'number'],
            [['total_product_amount','discount'], 'double'],
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
    public function beforeValidate()
    {
        if (!empty($this->price_number) && !empty($this->product)) {
            $price_feild = 'price_' . $this->price_number;
            $this->amount = $this->product->$price_feild;
        }
        return parent::beforeValidate(); // TODO: Change the autogenerated stub
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'الرقم'),
            'order_id' => Yii::t('app', 'رقم الطلب'),
            'product_id' => Yii::t('app', 'المادة'),
            'price_number' => Yii::t('app', 'السعر'),
            'amount' => Yii::t('app', 'السعر الفردي'),
            'count' => Yii::t('app', 'العدد'),
            'count_type' => Yii::t('app', 'نوع العدد'),
            'created_at' => Yii::t('app', 'تاريخ الانشاء'),
            'created_by' => Yii::t('app', 'الشخص المنشئ'),
            'updated_at' => Yii::t('app', 'تاريخ التعديل'),
            'updated_by' => Yii::t('app', 'الشخص المعدل'),
            'discount' => Yii::t('app', 'الخصم'),
            'total_product_amount' => Yii::t('app', 'السعر الاجمالي'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\OrderProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        $query = new \common\models\query\OrderProductQuery(get_called_class());
        $query->attachBehavior('softDelete', SoftDeleteQueryBehavior::className());
        return $query;
    }

    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    public function getPriceList()
    {
        if($this->product)
        {
           return [
                1=>1,
                2=>2,
                3=>3,
            ];
        }
        return [];
    }

}
