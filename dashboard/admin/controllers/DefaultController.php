<?php

namespace dashboard\admin\controllers;

use dashboard\admin\components\BaseController;

/**
 * DefaultController
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class DefaultController extends BaseController
{

    /**
     * Action index
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
