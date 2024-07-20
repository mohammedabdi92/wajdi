<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "store".
 *
 * @property int $id
 * @property string|null $name
 */
class Store extends \yii\db\ActiveRecord
{
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
        return 'store';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['inv_footer_left','inv_footer_right'], 'required'],
            [['name','status'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'الرقم',
            'name' => 'الاسم',
            'inv_footer_left' => 'ملاحظة اسفل الفاتورة شمال',
            'inv_footer_right' => 'ملاحظة اسفل الفاتورة يمين',
            'status' => Yii::t('app', 'الحالة'),

        ];
    }
}
