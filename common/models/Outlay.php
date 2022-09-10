<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "outlays".
 *
 * @property int $id
 * @property float|null $amount
 * @property string|null $note
 * @property string|null $image_name
 * @property int|null $user_id
 * @property dateTime|null $pull_date
 * @property int $created_at
 * @property int|null $created_by
 * @property int $updated_at
 * @property int|null $updated_by
 */
class Outlay extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'outlays';
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
            [['amount'], 'number'],
            [['note'], 'string'],
            [['amount','user_id','pull_date'], 'required'],
            [['created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['image_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'الرقم'),
            'amount' => Yii::t('app', 'القيمة'),
            'note' => Yii::t('app', 'الملاحظة'),
            'image_name' => Yii::t('app', 'الصورة'),
            'created_at' => Yii::t('app', 'تاريخ الانشاء'),
            'created_by' => Yii::t('app', 'الشخص المنشئ'),
            'updated_at' => Yii::t('app', 'تاريخ التعديل'),
            'updated_by' => Yii::t('app', 'الشخص المعدل'),
            'user_id' => Yii::t('app', 'الساحب'),
            'pull_date' => Yii::t('app', 'تاريخ السحب'),
        ];
    }



    /**
     * {@inheritdoc}
     * @return \common\models\query\OutlayQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\OutlayQuery(get_called_class());
    }
}
