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
 */
class Product extends \common\components\BaseModel
{
    public $imageFile;
    public $price_pf_vat;
    public $vat;
    public $price_discount_percent;
    public $price_discount_amount;
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
            [['price_1','price_2','price_3','price_4','price','title', 'category_id', 'count_type','min_number'], 'required'],
            [['title'], 'string'],
            [['min_number'], 'double'],
            [['title'], 'unique'],
            [['title'], 'trim'],
            [['category_id', 'count_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'isDeleted'], 'integer'],
            [['price_pf_vat','vat','price_discount_percent','price_discount_amount'], 'safe'],
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
            'title' => Yii::t('app', 'الاسم'),
            'category_id' => Yii::t('app', 'قسم'),
            'count_type' => Yii::t('app', 'نوع العد'),
            'price' => Yii::t('app', 'السعر'),
            'min_number' => Yii::t('app', 'الحد الادنى للعدد'),
            'price_1' => Yii::t('app', 'السعر الاول'),
            'price_2' => Yii::t('app', 'السعر الثاني'),
            'price_3' => Yii::t('app', 'السعر الثالث'),
            'price_4' => Yii::t('app', 'السعر الرابع'),
            'image_name' => Yii::t('app', 'الصورة'),
            'created_at' => Yii::t('app', 'تاريخ الانشاء'),
            'created_by' => Yii::t('app', 'الشخص المنشئ'),
            'updated_at' => Yii::t('app', 'تاريخ التعديل'),
            'updated_by' => Yii::t('app', 'الشخص المعدل'),
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

    public  function getCategory(){
        return $this->hasOne(ProductCategory::className(), ['id' => 'category_id']);
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

            return [
                1=>1,
                2=>2,
                3=>3,
                4=>4,
            ];

    }
}
