<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use yii2tech\ar\softdelete\SoftDeleteQueryBehavior;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $title
 * @property string $image_name
 * @property int $category_id
 * @property int $count_type
 * @property int $created_at
 * @property float $price
 * @property float $price_1
 * @property float $price_2
 * @property float $price_3
 * @property float $price_4
 * @property float $min_number
 * @property int|null $created_by
 * @property int $updated_at
 * @property int|null $updated_by
 * @property int|null $isDeleted
 * @property float|null $price_discount_percent
 * @property float|null $vat
 * @property float|null $price_pf_vat
 * @property float|null $price_discount_amount
 */
class Product extends \common\components\BaseModel
{
    public $imageFile;
    public $last_price;
    public $min_price;

    public $min_counts = [];
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    const statusArray = [
        self::STATUS_ACTIVE=>"فعال",
        self::STATUS_INACTIVE=>"غير فعال",
    ];
    public  function getStatusText(){
        return self::statusArray[$this->status];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }




    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['price_1','price_2','price_3','price_4','price','title', 'category_id', 'count_type','status'], 'required'],
            [['title'], 'string'],
            [['title'], 'unique'],
            [['title'], 'trim'],
            [['category_id', 'count_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'isDeleted'], 'integer'],
            [['price_pf_vat','vat','price_discount_percent','price_discount_amount','min_counts','last_price','min_price','item_code'], 'safe'],
        ];
    }

    public function behaviors()
    {
        return [
            'softDeleteBehavior' => [
                'class' => SoftDeleteBehavior::className(),
                'softDeleteAttributeValues' => [
                    'status' => 2
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
            'title' => Yii::t('app', 'الاسم'),
            'category_id' => Yii::t('app', 'قسم'),
            'count_type' => Yii::t('app', 'نوع العد'),
            'item_code' => Yii::t('app', 'الكود'),
            'price' => Yii::t('app', 'السعر'),
            'min_number' => Yii::t('app', 'الحد الادنى للعدد'),
            'last_price' => Yii::t('app', 'اخر سعر'),
            'min_price' => Yii::t('app', 'اقل سعر'),
            'price_1' => Yii::t('app', 'السعر الاول'),
            'price_2' => Yii::t('app', 'السعر الثاني'),
            'price_3' => Yii::t('app', 'السعر الثالث'),
            'price_4' => Yii::t('app', 'السعر الرابع'),
            'image_name' => Yii::t('app', 'الصورة'),
            'created_at' => Yii::t('app', 'تاريخ الانشاء'),
            'created_by' => Yii::t('app', 'الشخص المنشئ'),
            'updated_at' => Yii::t('app', 'تاريخ التعديل'),
            'updated_by' => Yii::t('app', 'الشخص المعدل'),
            'min_counts' => Yii::t('app', 'الحد الادنى'),
            'status' => Yii::t('app', 'الحالة'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\ProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        $query = new \common\models\query\ProductQuery(get_called_class());
        $query->attachBehavior('softDelete', SoftDeleteQueryBehavior::className());
        return $query;
    }
    public function afterFind()
    {
        $min_counts = [];
        $MinProductCount=   MinProductCount::find()->where(['product_id'=>$this->id])->all();
        foreach ($MinProductCount as $item) {
            $min_counts[$item->store_id] = $item->count;
       }
        $this->min_counts =$min_counts;
        parent::afterFind(); // TODO: Change the autogenerated stub
    }

    public  function getCategory(){
        return $this->hasOne(ProductCategory::className(), ['id' => 'category_id']);
    }
    public function afterSave($insert, $changedAttributes)
    {
        foreach ($this->min_counts as $store_id=>$count) {

            $MinProductCount=   MinProductCount::find()->where(['product_id'=>$this->id,'store_id'=>$store_id])->one();
            if(empty($MinProductCount))
            {
                $MinProductCount = new MinProductCount();
                $MinProductCount->product_id = $this->id;
                $MinProductCount->store_id = $store_id;

            }
            $MinProductCount->count = $count;
            $MinProductCount->save(false);
        }

        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }


    public  function getCategoryTitle(){
        return $this->category?$this->category->name:'';
    }
    public function upload()
    {
        if ($this->validate()) {

            if(!empty($this->imageFile))
            {
                $dir = dirname(dirname(__DIR__)) . '/dashboard'.'/web/uploads/main-category' ;
                if(!file_exists($dir)){
                    mkdir("$dir", 0777, true);
                }
                $this->imageFile->saveAs($dir .'/'. $this->id.'-'.time() . '.' . $this->imageFile->extension);
                $this->image_name =   $this->id.'-'.time() . '.' . $this->imageFile->extension;
                $this->save(false);
            }
            return true;
        } else {
            return false;
        }
    }
    public function getImageUrl()
    {
        return $this->image_name?'/uploads/main-category/'.$this->image_name:null;
    }
    public function getPriceList()
    {
        $prices = [];
        if(Yii::$app->user->can('سعر بيع 1')){
            $prices[1]= 1;
        }
        if(Yii::$app->user->can('سعر بيع 2')){
            $prices[2]= 2;
        }
        if(Yii::$app->user->can('سعر بيع 3')){
            $prices[3]= 3;
        }
        if(Yii::$app->user->can('سعر بيع 4')){
            $prices[4]= 4;
        }


            return $prices;

    }


}
