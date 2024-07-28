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
    public function cloneModel($className) {
        $attributes = $this->attributes;
        $excludeArray =  ['id','created_at','created_by','updated_at','updated_by'];
        foreach ($excludeArray as $item) {
            if(isset($attributes[$item]))
            {
                unset($attributes[$item]);
            }
        }


        $newObj = new $className;
        foreach($attributes as  $attribute => $val) {
            $newObj->{$attribute} = $val;
        }
        return $newObj;
    }
    public function isAttributeChanged($name, $identical = true)
    {
        if (isset($this->attributes[$name], $this->oldAttributes[$name])) {
            if ($identical) {
                return $this->attributes[$name] != $this->oldAttributes[$name];
            }

            return $this->attributes[$name] != $this->oldAttributes[$name];
        }

        return isset($this->attributes[$name]) || isset($this->oldAttributes[$name]);
    }

}