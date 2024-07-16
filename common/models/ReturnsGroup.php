<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use common\models\Returns;

/**
 * This is the model class for table "returns_group".
 *
 * @property int $id
 * @property int|null $order_id
 * @property string|null $note
 * @property string|null $returner_name
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $total_amount
 * @property int|null $total_count
 * @property int|null $total_old_amount
 */
class ReturnsGroup extends \yii\db\ActiveRecord
{
    public $store_id;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'returns_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'created_at', 'created_by', 'updated_at', 'updated_by','total_count','order_id'], 'integer'],
            [['note', 'returner_name'], 'string'],
            [['total_amount','total_old_amount'], 'double'],
            [[  'order_id', 'returner_name' ], 'required'],
            
        ];
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

    public function attributeLabels()
    {

        return [
            'id' => Yii::t('app', 'الرقم'),
            'order_id' => Yii::t('app', 'الطلب'),
            'product_id' => Yii::t('app', 'المادة'),
            'store_id' => Yii::t('app', 'المحل'),
            'returner_name' => Yii::t('app', 'الشخص المرجع'),
            'note' => Yii::t('app', 'ملاحظة'),
            'total_amount' => Yii::t('app', ' مجموع المرجع الصافي للعميل'),
            'total_old_amount' => Yii::t('app', 'مجموع المرجع قبل الخصم'),
            'total_count' => Yii::t('app', 'عدد القطع'),
            'created_at' => Yii::t('app', 'تاريخ الانشاء'),
            'created_by' => Yii::t('app', 'الشخص المنشئ'),
            'updated_at' => Yii::t('app', 'تاريخ التعديل'),
            'updated_by' => Yii::t('app', 'الشخص المعدل'),
        ];
    }
    public function getReturns()
    {
        return $this->hasMany(Returns::className(), ['returns_group_id' => 'id']);
    }
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
    public function getStoreTitle()
    {
        return Store::findOne($this->store_id)->name;
    }
    public function beforeDelete()
    {
       $returns =  $this->returns;
       foreach ($returns as $return)
       {
           $return->delete();

       }

        return parent::beforeDelete();
    }
}
