<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "service_center".
 *
 * @property int $id
 * @property string $name
 * @property string|null $phone
 * @property string|null $location
 * @property string|null $responsible_person
 * @property int $created_at
 * @property int $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 */
class ServiceCenter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'service_center';
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
            [['name', 'phone'], 'required'],
            [['created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
            [['name', 'phone', 'location', 'responsible_person'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'الرقم',
            'name' => 'اسم المركز',
            'phone' => 'رقم الهاتف',
            'location' => 'الموقع',
            'responsible_person' => 'اسم مسؤول الصيانة',
            'created_at' =>  'تاريخ الانشاء',
            'created_by' =>  'الشخص المنشئ',
            'updated_at' =>  'تاريخ التعديل',
            'updated_by' =>  'الشخص المعدل',
        ];
    }
}
