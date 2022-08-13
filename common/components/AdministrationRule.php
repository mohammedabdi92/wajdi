<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\components;

use yii\rbac\Rule;
use yii\filters\AccessRule;

/**
 * Checks if authorID matches user passed via params
 */
//class AdministrationRule extends  \yii\filters\AccessRule
class AdministrationRule extends Rule
{
    //public $name = 'isAuthor';

    /**
     * @param string|integer $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return boolean a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {

        return TRUE;
    }

}