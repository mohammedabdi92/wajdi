<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * This is the model class for table "transactions".
 *
 * @property int $id
 * @property int $customer_id
 * @property int|null $order_id
 * @property float|null $amount
 * @property int|null $type
 * @property string|null $note
 * @property int $created_at
 * @property int|null $created_by
 * @property int $updated_at
 * @property int|null $updated_by
 * @property int|null $isDeleted
 */
class Transactions extends \yii\db\ActiveRecord
{

    const  TYPE_DEBT = 1;
    const TYPE_REPAYMENT = 2;
    const typeArray = [
        self::TYPE_DEBT=>"دين",
        self::TYPE_REPAYMENT=>"سداد",
    ];
    public  function getTypeText(){
        return self::typeArray[$this->type];
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
    public static function tableName()
    {
        return 'transactions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id','amount'], 'required'],
            [['customer_id', 'order_id', 'type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'isDeleted'], 'integer'],
            [['amount'], 'number'],
            [['note'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'الرقم',
            'customer_id' => 'العميل',
            'order_id' => 'الطلب',
            'amount' => 'القيمة',
            'type' => 'نوع الحركة',
            'note' => 'ملاحظة',
            'created_at' =>  'تاريخ الانشاء',
            'created_by' =>  'الشخص المنشئ',
            'updated_at' =>  'تاريخ التعديل',
            'updated_by' =>  'الشخص المعدل',
            'isDeleted' => 'محذوف',
        ];
    }
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }


    /**
     * {@inheritdoc}
     * @return TransactionsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TransactionsQuery(get_called_class());
    }
}
