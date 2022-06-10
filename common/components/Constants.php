<?php

namespace common\components;

class Constants
{

    const STORE_1 = 1;
    const STORE_2 = 2;
    const STORE_3 = 3;
    const STORE_4 = 4;
    const storeArray = [
        self::STORE_1 => "مستودع وجدي للاعمار",
        self::STORE_2 => "مؤسسة وجدي الحوية",
        self::STORE_3 => "وجدي للاعمار الحوية",
        self::STORE_4 => "نؤسسة وجدي فرع الشهابية",
    ];

    public static function getStoreName($store)
    {
        return self::storeArray[$store] ?? '';
    }

}