<?php

namespace common\models;

use common\components\CustomFunc;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "damaged".
 *
 * @property int $id
 * @property int|null $status
 * @property int|null $status_note_id
 * @property int|null $inventory_order_id
 * @property int|null $supplyer_total_amount
 * @property int|null $customer_note
 * @property int|null $supplier_note
 * @property int|null $order_id
 * @property float|null $product_id
 * @property float|null $count
 * @property float|null $amount
 * @property int $created_at
 * @property int|null $created_by
 * @property int $updated_at
 * @property int|null $updated_by
 */
class Damaged extends \yii\db\ActiveRecord
{
    public $store_id;
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_RETURNED = 3;
    const STATUS_REPLACED = 4;
    const statusArray = [
        self::STATUS_ACTIVE=>"بانتظار التواصل مع المورد",
        self::STATUS_INACTIVE=>"بانتظار زيارة المورد",
        self::STATUS_RETURNED=>"بانتضار الرد من المورد",
        self::STATUS_REPLACED=>"مكتمل",
    ];
    public  function getStatusText(){
        return self::statusArray[$this->status];
    }

    const STATUS_NOTE_REPLACED_SAME = 1;
    const STATUS_NOTE_REPLACED_DEFFRENT = 2;
    const STATUS_NOTE_RETURN_CASH = 3;
    const STATUS_NOTE_RETURN_WITH_PAY = 4;
    const STATUS_NOTE_NOT_RETURND = 5;
    const statusNoteArray = [
        self::STATUS_NOTE_REPLACED_SAME=>"تم التبديل بنفس المادة",
        self::STATUS_NOTE_REPLACED_DEFFRENT=>"تم التبديل بمادة اخرى",
        self::STATUS_NOTE_RETURN_CASH => "تم ارجاع ثمن المادة",
        self::STATUS_NOTE_RETURN_WITH_PAY => "تم التبديل بدفع الفرق",
        self::STATUS_NOTE_NOT_RETURND => "لم يتم التبديل او الارجاع"
    ];
    public  function getStatusNoteText(){
        return self::statusNoteArray[$this->status_note_id];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'damaged';
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
            [['product_id', 'count', 'order_id'], 'required'],
            [['cost_value', 'total_amount', 'status_note_id', 'supplyer_pay_amount', 'supplyer_price', 'supplyer_total_amount'], 'safe'],
            [['status', 'order_id', 'created_by', 'updated_by'], 'integer'],
            [['product_id', 'count', 'amount'], 'number'],
            [['count'], 'checkOrderCount'],
            [['inventory_order_id'], 'checkInventoryOrder', 'on' => 'scenario_supplyer'],
            [['status_note_id','status'], 'checkStatus', 'on' => 'scenario_supplyer'],
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            [['order_id', 'product_id', 'count', 'amount', 'total_amount'], 'required', 'on' => 'scenario_agent'],
            [['inventory_order_id', 'supplyer_price', 'status','supplyer_total_amount'], 'required', 'on' => 'scenario_supplyer'],
        ];
    }


    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['scenario_agent'] = ['order_id', 'product_id', 'count', 'amount', 'total_amount', 'cost_value', 'customer_note'];
        $scenarios['scenario_supplyer'] = ['customer_note', 'status', 'inventory_order_id', 'supplyer_price', 'supplyer_pay_amount', 'supplier_note', 'status_note_id', 'supplyer_total_amount'];
        return $scenarios;
    }

    public function checkOrderCount($attr, $params) {

        if ($this->isNewRecord ) {

            $order_product = OrderProduct::find()->where(['order_id'=>$this->order_id,'product_id'=>$this->product_id])->one();
            $returned = Returns::find()->where(['order_id'=>$this->order_id,'product_id'=>$this->product_id])->sum('count');
            $damaged = Damaged::find()->where(['order_id'=>$this->order_id,'product_id'=>$this->product_id])->sum('count');

            if($order_product){
                $total = $order_product->count;
                $total -= $returned??0;
                $total -= $damaged??0;
                if ( ($total - $this->count)<0) {

                    $this->addError($attr, 'لا توجد هذه الكمية (الموجود هو '.$total.')');
                }
            }

        }
    }


    public function checkInventoryOrder($attr, $params) {

        $model = InventoryOrderProduct::find()->where(['inventory_order_id'=>$this->inventory_order_id,'product_id'=> $this->product_id])->one();
      

        if(!$model){
            $this->addError($attr, 'لا توجد هذه المادة في فاتورة المشتريات المدخلة');
        }
        
    }
    public function checkStatus($attr, $params) {

        if(empty($this->status_note_id) && $this->status == self::STATUS_REPLACED)
        {
            $this->addError('status_note_id', 'يجب اختيار الاجراء المتخذ');
        }
        if(!empty($this->status_note_id) && $this->status != self::STATUS_REPLACED)
        {
            $this->addError('status_note_id', 'لا يمكنك اختيار الاجراء وحالة الطلب ليسة مكتملة');
        }
        
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'الرقم'),
            'store_id' => Yii::t('app', 'المحل'),
            'status' => Yii::t('app', 'الحالة'),
            'order_id' => Yii::t('app', 'رقم فاتورة المبيعات'),
            'product_id' => Yii::t('app', 'المادة'),
            'count' => Yii::t('app', 'العدد'),
            'amount' => Yii::t('app', 'قيمة المادة'),
            'supplyer_total_amount' => Yii::t('app', 'قيمة المرجع للمحل'),
            'status_note_id' => Yii::t('app', ' الاجراء المتخذ'),
            'supplyer_price' => Yii::t('app', ' سعر الشراء'),
            'supplyer_pay_amount' => Yii::t('app', ' فرقية التبديل للمورد'),
            'inventory_order_id' => Yii::t('app', 'رقم فاتورة المشتريات'),
            'customer_note' => Yii::t('app', 'ملاحظة العميل'),
            'supplier_note' => Yii::t('app', 'ملاحظة المورد'),
            'cost_value' => Yii::t('app', 'قيمة  التكاليف من العميل'),
            'total_amount' => Yii::t('app', ' قيمة المرجعة للعميل'),
            'created_at' => Yii::t('app', 'تاريخ الانشاء'),
            'created_by' => Yii::t('app', 'الشخص المنشئ'),
            'updated_at' => Yii::t('app', 'تاريخ التعديل'),
            'updated_by' => Yii::t('app', 'الشخص المعدل'),

            



        ];
    }
    public function beforeSave($insert)
    {

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\DamagedQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\DamagedQuery(get_called_class());
    }
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
    public function getInventoryOrder()
    {
        return $this->hasOne(InventoryOrder::className(), ['id' => 'inventory_order_id']);
    }

    public function getStoreTitle()
    {
        return Store::findOne($this->store_id)->name;
    }
    public function getProductTitle()
    {
        return $this->product ? $this->product->title : '';
    }
    public function afterSave($insert, $changedAttributes)
    {
        $order = $this->order ;
        $old_product = $changedAttributes['product_id']??null;
        CustomFunc::calculateProductCount($order->store_id,$this->product_id,$old_product);

        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }
    public function afterDelete()
    {
        $order = $this->order ;
        CustomFunc::calculateProductCount($order->store_id,$this->product_id);
        parent::afterDelete(); // TODO: Change the autogenerated stub
    }
}
