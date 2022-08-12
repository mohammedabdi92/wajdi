<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "outlays".
 *
 * @property int $id
 * @property float|null $amount
 * @property string|null $note
 * @property string|null $image_name
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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['amount'], 'number'],
            [['note'], 'string'],
            [['created_at', 'updated_at'], 'required'],
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
            'id' => Yii::t('app', 'ID'),
            'amount' => Yii::t('app', 'Amount'),
            'note' => Yii::t('app', 'Note'),
            'image_name' => Yii::t('app', 'Image Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
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
