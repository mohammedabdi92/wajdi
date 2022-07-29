<?php

namespace common\models;

use Yii;
use yii\base\Model;

class ConfigurationForm extends Model
{
    /**
     * @var string application name
     */
    public $price_profit_rate_1;
    public $price_profit_rate_2;
    public $price_profit_rate_3;
    public $price_profit_rate_4;

    public function rules(): array
    {
        return [
            [['price_profit_rate_1','price_profit_rate_2','price_profit_rate_3','price_profit_rate_4'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'price_profit_rate_1' => Yii::t('app', 'النسبة الاولى'),
            'price_profit_rate_2' => Yii::t('app', 'النسبة الثانية'),
            'price_profit_rate_3' => Yii::t('app', 'النسبة الثالثة'),
            'price_profit_rate_4' => Yii::t('app', 'النسبة الرابعة'),
        ];
    }
}