<?php

namespace common\models;

use Yii;
use common\models\Customer;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "maintenance".
 *
 * @property int $id
 * @property int $client_id
 * @property int $item_count
 * @property string|null $client_note
 * @property string $status
 * @property float|null $amount_paid
 * @property int $service_center_id
 * @property string|null $maintenance_note
 * @property float $maintenance_cost
 * @property float|null $cost_difference
 * @property int $created_at
 * @property int $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 */
class Maintenance extends \yii\db\ActiveRecord
{
    
    const STATUS_ACTIVE = 1;
    const STATUS_WAIGHT = 2;
    const STATUS_CONFIRM = 3;
    const STATUS_COMPLET = 4;
    const statusArray = [
        self::STATUS_ACTIVE=>'بانتظار الصيانة',
        self::STATUS_WAIGHT=>'قيد الصيانة',
        self::STATUS_CONFIRM=>'تم الاستلام',
        self::STATUS_COMPLET=>'تم التسليم مكتمل',
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'maintenance';
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
            [['client_id','product_name', 'item_count', 'status', 'service_center_id'], 'required'],
            [['client_id', 'item_count', 'service_center_id', 'created_by'], 'integer'],
            [['amount_paid', 'maintenance_cost', 'cost_difference'], 'number'],
            [['client_note', 'maintenance_note'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
            [['updated_by','status'], 'integer'],
            [['amount_paid'],'required', 'when' => function($model) {
                if($this->status == 4) 
                {
                    return true;
                }
                return false;
            }],
            [['maintenance_cost'],'required', 'when' => function($model) {
                if($this->status >= 3) 
                {
                    return true;
                }
                return false;
            }],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_id'  => 'اسم العميل',
            'product_name' => 'اسم المادة',
            'item_count' => 'العدد',
            'client_note' => ' ملاحظة العميل  ',
            'status' => 'حالة الطلب',
            'amount_paid' => ' المبلغ المدفوع من العميل ',
            'service_center_id' => 'مركز الصيانة',
            'maintenance_note' => 'ملاحظة الصيانة',
            'maintenance_cost' => 'تكلفة الصيانة',
            'cost_difference' => 'فرق التكلفة',
            'created_at' =>  'تاريخ الانشاء',
            'created_by' =>  'الشخص المنشئ',
            'updated_at' =>  'تاريخ التعديل',
            'updated_by' =>  'الشخص المعدل',
        ];
    }
    public function getServiceCenter()
    {
        return $this->hasOne(ServiceCenter::className(), ['id' => 'service_center_id']);
    }
    public function getClient()
    { 
        return $this->hasOne(Customer::class, ['id' => 'client_id']);
    }
}
