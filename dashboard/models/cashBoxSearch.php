<?php
namespace dashboard\models ;

use common\base\Model;

class cashBoxSearch extends Model
{
    public $date;
    public $date_from;
    public $date_to;
    public $store_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date','date_from', 'date_to','store_id'], 'safe'],

        ];
    }


}