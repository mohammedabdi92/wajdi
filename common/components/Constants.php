<?php

namespace common\components;

class Constants
{

    const STORE_1 = 1;
    const STORE_2 = 2;
    const STORE_3 = 3;
    const STORE_4 = 4;
    const storeArray = [
        self::STORE_3 => "وجدي للاعمار الحوية",
        self::STORE_2 => "مؤسسة وجدي فرع الحوية",
        self::STORE_4 => "مؤسسة وجدي فرع الشهابية",
        self::STORE_1 => "مستودع وجدي للاعمار",

    ];

    public static function getStoreName($id): string
    {
        return self::storeArray[$id] ?? '';
    }


    const TYPE_PIECE =1 ;
    const TYPE_METIR =2;
    const TYPE_KELO =3;
    const TYPE_BAKET =4;
    const TYPE_JOZ =5;
    const TYPE_TAKEM =6;
    const TYPE_LAFA =7;
    const TYPE_DZENH =8;
    const TYPE_KES =9;
    const TYPE_SHWAL =10;
    const TYPE_KRTONH =11;
    const TYPE_TON =12;
    const TYPE_KLAB =13;

    const countTypesArray = [
        self::TYPE_PIECE => "قطعة",
        self::TYPE_METIR => "متر",
        self::TYPE_KELO => "كيلو",
        self::TYPE_BAKET => "باكيت",
        self::TYPE_JOZ => "جوز",
        self::TYPE_TAKEM => "طقم",
        self::TYPE_LAFA => "لفة",
        self::TYPE_DZENH => "دزينة",
        self::TYPE_KES => "كيس",
        self::TYPE_SHWAL => "شوال",
        self::TYPE_KRTONH => "كرتونة",
        self::TYPE_TON => "طن",
        self::TYPE_KLAB => "قلاب",
    ];

    public static function getCountTypesName($id): string
    {
        return self::countTypesArray[$id] ?? '';
    }

}