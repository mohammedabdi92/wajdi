<?php

namespace common\components;

use common\models\User;

class CustomFunc
{
    public static function getRandomString($length = 4 , $numberOnly = false) {
        $str = "";
        $characters = ( $numberOnly ) ? range('0','9') : array_merge(range('a','z'), range('0','9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }
    public static function getFullDate($date){
        return $date? date('Y-m-d H:i:s', $date):'';
    }
    public static function getUserName($id){
        $user = User::findOne($id);
        return  $user? $user->full_name:'';
    }
}