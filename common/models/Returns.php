<?php

namespace common\models;

use common\components\CustomFunc;
use Yii;
use yii\behaviors\AttributeTypecastBehavior;
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
 * @property float|null $returns_group_id
 * @property float|null $old_amount
 * @property float|null $old_single_amount
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
            'typecast' => [
                'class' => AttributeTypecastBehavior::class,
                'attributeTypes' => [
                    'product_id' => AttributeTypecastBehavior::TYPE_INTEGER,
                ],
                'typecastAfterValidate' => true,
                'typecastBeforeSave' => true,
                'typecastAfterFind' => true,
            ],
        ];
    }



    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'created_at', 'created_by', 'updated_at', 'updated_by','returns_group_id'], 'integer'],
            [['product_id'], 'uniqueProduct'],
            [['product_id', 'count', 'amount'], 'number'],
            [['count'], 'validateCountExist'],
            [['old_amount','old_single_amount'], 'double'],
            [[  'order_id', 'amount', 'count' ], 'required'],
          
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
            'old_single_amount' => Yii::t('app', 'الافرادي '),
            'old_amount' => Yii::t('app', 'الاجمالي'),
            'created_at' => Yii::t('app', 'تاريخ الانشاء'),
            'created_by' => Yii::t('app', 'الشخص المنشئ'),
            'updated_at' => Yii::t('app', 'تاريخ التعديل'),
            'updated_by' => Yii::t('app', 'الشخص المعدل'),
        ];
    }

    public function uniqueProduct($attr)
    {
        if (isset($this->product_id) && $this->returns_group_id) {
            $item_order = self::find()->where(['product_id'=>$this->product_id,'returns_group_id'=>$this->returns_group_id])->andWhere(['<>','id',$this->id??0])->one() ;

            if ($item_order) {
                $this->addError($attr, 'لا يمكنك اضافة نفس المادة اكثر من مرة');
            }
        }

    
    }

    public function validateCountExist($attr)
    {
        $count = $this->orderProduct->count;
        $q = self::find()->where(['order_id'=>$this->order_id,'product_id'=>$this->product_id]);
        if(!$this->isNewRecord)
        {
            $q->andWhere(['<>', 'returns_group_id',$this->returns_group_id]);
        }
        $return = $q->sum('count');
        $remaining =$count - $return ;
        if (!empty($this->count) && $this->count > $remaining ) {
            $this->addError($attr, 'الكمية اكبر من الطلب  ' . $remaining);
        }


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
    public function getOrderProduct()
    {
        $order = $this->order;
        return $this->hasOne(OrderProduct::className(), ['product_id' => 'product_id'])->andOnCondition(['order_id' => $order->id])->andOnCondition(['store_id' => $order->store_id]);
    }
    public function getProductTitle()
    {
        return $this->product ? $this->product->title : '';
    }
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // Get the old attributes from the oldAttributes property
        $oldAttributes = $this->oldAttributes;


        // Check for attributes that haven't changed but were included in $changedAttributes
        foreach ($oldAttributes as $attribute => $value) {
            if (array_key_exists($attribute, $changedAttributes) && $this->$attribute == $value) {
                unset($changedAttributes[$attribute]);
            }
        }
        $old_product = $changedAttributes['product_id']??null;
        $order = $this->order ;
        CustomFunc::calculateProductCount($order->store_id,$this->product_id,$old_product);



    }

    public function afterDelete()
    {
        $order = $this->order ;
        CustomFunc::calculateProductCount($order->store_id,$this->product_id);
        parent::afterDelete(); // TODO: Change the autogenerated stub
    }
}
