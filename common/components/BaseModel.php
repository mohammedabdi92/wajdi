<?php

namespace common\components;

use common\models\CountType;
use common\models\User;

class BaseModel extends \yii\db\ActiveRecord
{

    public function getDate($att)
    {
        return date('s:i:H d-m-Y ', $this->$att);
    }
    public function getUserName($att): string
    {
        $user =  User::findOne($this->$att);
        return $user->full_name??'';
    }

    public function getCountTypeName($att): string
    {
        $CountType =  CountType::findOne($this->$att);
        return $CountType->name??'';
    }

}