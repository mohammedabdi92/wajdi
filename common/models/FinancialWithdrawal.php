<?php

namespace common\models;

use common\components\Constants;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "financial_withdrawals".
 *
 * @property int $id
 * @property float|null $amount
 * @property int|null $status
 * @property int|null $user_id
 * @property int|null $store_id
 * @property dateTime|null $pull_date
 * @property string|null $note
 * @property int $created_at
 * @property int|null $created_by
 * @property int $updated_at
 * @property int|null $updated_by
 */
class FinancialWithdrawal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'financial_withdrawals';
    }

    const STATUS_NOT_PAYED = 1;
    const STATUS_PAYED = 2;
    const statusArray = [
        self::STATUS_NOT_PAYED=>"غير مدفوع",
        self::STATUS_PAYED=>"مدفوع",
    ];

    public  function getStatusText(){
        return self::statusArray[$this->status];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['amount','user_id'], 'number'],
            [['amount','user_id','pull_date','store_id'], 'required'],
            [['status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['note'], 'string'],
            ['status', 'default', 'value' => self::STATUS_NOT_PAYED],
        ];
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
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'الرقم'),
            'amount' => Yii::t('app', 'القيمة'),
            'store_id' => Yii::t('app', 'المحل'),
            'status' => Yii::t('app', 'الحالة'),
            'user_id' => Yii::t('app', 'الساحب'),
            'pull_date' => Yii::t('app', 'تاريخ السحب'),
            'note' => Yii::t('app', 'الملاحظة'),
            'created_at' => Yii::t('app', 'تاريخ الانشاء'),
            'created_by' => Yii::t('app', 'الشخص المنشئ'),
            'updated_at' => Yii::t('app', 'تاريخ التعديل'),
            'updated_by' => Yii::t('app', 'الشخص المعدل'),
        ];

    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\FinancialWithdrawalQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\FinancialWithdrawalQuery(get_called_class());
    }
    public function getStoreTitle()
    {
        return Constants::getStoreName($this->store_id);
    }
}
