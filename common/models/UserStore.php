<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_store".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $store_id
 */
class UserStore extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_store';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'store_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'store_id' => 'Store ID',
        ];
    }
}
