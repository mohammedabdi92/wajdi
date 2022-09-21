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
        ];
    }
}
