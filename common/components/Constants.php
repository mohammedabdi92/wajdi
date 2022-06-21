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

    public static function getStoreName($id): string
    {
        return self::storeArray[$id] ?? '';
    }

    const TYPE_PIECE = 1;
    const TYPE_WُEIGHT = 2;
    const TYPE_BOX = 3;

    const countTypesArray = [
        self::TYPE_PIECE => "قطعة",
        self::TYPE_WُEIGHT => "وزن",
        self::TYPE_BOX => "صندوق",
    ];

    public static function getCountTypesName($id): string
    {
        return self::countTypesArray[$id] ?? '';
    }

}