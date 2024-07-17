<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "work_days".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int $day_of_week 0=Sunday, 1=Monday, ..., 6=Saturday
 */
class WorkDays extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'work_days';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'day_of_week'], 'integer'],
            [['day_of_week'], 'required'],
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
            'day_of_week' => 'Day Of Week',
        ];
    }
}