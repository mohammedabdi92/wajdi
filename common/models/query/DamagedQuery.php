<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\Damaged]].
 *
 * @see \common\models\Damaged
 */
class DamagedQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\Damaged[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Damaged|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
