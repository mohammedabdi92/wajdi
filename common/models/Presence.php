<?php

namespace common\models;

use Mpdf\Tag\P;
use Yii;

/**
 * This is the model class for table "presence".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $time
 * @property int|null $type
 */
class Presence extends \yii\db\ActiveRecord
{
    const TYPE_IN = 1;
    const TYPE_OUT = 2;
    const typesArray = [
        self::TYPE_IN=>"دخول",
        self::TYPE_OUT=>"خروج",
    ];
    public $time_out ;
    public $time_from ;
    public $time_to;
    public $diff_time_out ;
    public $diff_time_out_mints ;
    public $total_diff_time_out_mints ;

    public  function getTypeText(){
        return self::typesArray[$this->type];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'presence';
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'type'], 'integer'],
            [['time','time_out','diff_time_out','diff_time_out_mints','total_diff_time_out_mints','time_from','time_to'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'الرقم'),
            'user_id' => Yii::t('app', 'المستخدم'),
            'time' => Yii::t('app', 'الوقت'),
            'time_out' => Yii::t('app', 'الوقت الخروج'),
            'type' => Yii::t('app', 'نوع البصمة'),
            'diff_time_out' => Yii::t('app', 'وقت الدوام'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\PresenceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\PresenceQuery(get_called_class());
    }
    public function afterFind()
    {
        if(!empty($this->diff_time_out_mints))
        {
            $mins =$this->diff_time_out_mints;
            $hours= floor($mins/(60));
            $mints_last =   fmod($mins, 60);
            $this->diff_time_out=$hours.':'.$mints_last;
        }

        parent::afterFind(); // TODO: Change the autogenerated stub
    }
}
