<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TransferOrder */

$this->title = Yii::t('app', 'انشاء طلب نقل');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Transfer Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transfer-order-create">

    <h1 style="padding-bottom: 10px;padding-top: 10px;"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
