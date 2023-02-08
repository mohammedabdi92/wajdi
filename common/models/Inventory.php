<?php

namespace common\models;

use common\components\Constants;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use yii2tech\ar\softdelete\SoftDeleteQueryBehavior;

/**
 * This is the model class for table "inventory".
 *
 * @property int $id
 * @property int $product_id
 * @property int $store_id
 * @property float $last_product_cost
 * @property float $count
 * @property int $created_at
 * @property int|null $created_by
 * @property int $updated_at
 * @property int|null $updated_by
 * @property int|null $isDeleted
 */
class Inventory extends \common\components\BaseModel
{
    const STATUS_ACTIVE = 1;
    const STATUS_FEW = 2;
    const STATUS_NOT_ACTIVE = 3;
    const statusArray = [
        self::STATUS_ACTIVE=>"متوفرة",
        self::STATUS_NOT_ACTIVE=>"غير متوفرة",
        self::STATUS_FEW=>"تحت الحد الادنى",
    ];

    public $available_status;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inventory';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'store_id', 'count' ], 'required'],
            [['product_id', 'store_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'isDeleted'], 'integer'],
            [['last_product_cost', 'count','available_status'], 'number'],
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
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'الرقم'),
            'product_id' => Yii::t('app', 'المادة'),
            'store_id' => Yii::t('app', 'المحل'),
            'last_product_cost' => Yii::t('app', 'Last Product Cost'),
            'count' => Yii::t('app', 'العدد'),
            'available_status' => Yii::t('app', 'حالة عدد المواد'),
            'created_at' => Yii::t('app', 'تاريخ الانشاء'),
            'created_by' => Yii::t('app', 'الشخص المنشئ'),
            'updated_at' => Yii::t('app', 'تاريخ التعديل'),
            'updated_by' => Yii::t('app', 'الشخص المعدل'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\InventoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        $query = new \common\models\query\InventoryQuery(get_called_class());
        $query->attachBehavior('softDelete', SoftDeleteQueryBehavior::className());
        return $query;
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
    public function getMinProductCount(){

            return $this->hasOne(MinProductCount::className(), ['product_id' => 'product_id','store_id'=>'store_id']);

    }

    public  function getStatusText(){
        return self::statusArray[$this->available_status];
    }
}
