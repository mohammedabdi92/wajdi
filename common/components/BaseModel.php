<?php

namespace common\components;

use common\models\User;

class BaseModel extends \yii\db\ActiveRecord
{

    public function getDate($att)
    {
        return date('Y-m-d H:i:s', $this->$att);
    }
    public function getUserName($att): string
    {
        $user =  User::findOne($this->$att);
        return $user->full_name??'';
    }


}