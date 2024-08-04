<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Transactions $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="transactions-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'رجوع'), ['index'], ['class' => 'btn btn-primary']) ?>
        <?php
        if( $model->type == \common\models\Transactions::TYPE_REPAYMENT && Yii::$app->user->can('تعديل وحذف السداد من حركات الدين'))
        {
            echo Html::a(Yii::t('app', 'تعديل'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
            echo Html::a(Yii::t('app', 'حذف'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'هل انت متاكد من الحذف ؟'),
                    'method' => 'post',
                ],
            ]);
        }
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'customer_id',
                'value' => function ($model) {
                    return $model->customer->name ?? '';
                },
            ],
            [
                'attribute' => 'order_id',
                'value' => function($model)
                {
                    return Html::a($model->order_id, '/order/view?id='.$model->order_id,['target'=>'_blank'] );
                },
                'format' => 'raw',
            ],
            'amount',
            [
                'attribute' => 'type',
                'value' => function($model){
                    return $model->getTypeText();
                },
            ],
            'note:ntext',
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return \common\components\CustomFunc::getFullDate($model->created_at);
                },
            ],
            [
                'attribute' => 'created_by',
                'value' => function ($model) {
                    return \common\components\CustomFunc::getUserName($model->created_by);
                },
            ],
            [
                'attribute' => 'updated_at',
                'value' => function ($model) {
                    return \common\components\CustomFunc::getFullDate($model->updated_at);
                },
            ],
            [
                'attribute' => 'updated_by',
                'value' => function ($model) {
                    return \common\components\CustomFunc::getUserName($model->updated_by);
                },
            ],
        ],
    ]) ?>

</div>
