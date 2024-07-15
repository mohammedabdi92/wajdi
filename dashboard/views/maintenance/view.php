<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Maintenance $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Maintenances', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="maintenance-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    <?php
        
        echo Html::a(Yii::t('app', 'رجوع'), ['index'], ['class' => 'btn btn-primary']) ;
            echo Html::a(Yii::t('app', 'تعديل'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
            echo Html::a(Yii::t('app', 'حذف'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'هل انت متاكد من الحذف ؟'),
                    'method' => 'post',
                ],
            ]);
    ?>
        
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'store_id',
                'value' => function($model){
                    return $model->storeTitle;
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'client_id',
                'value' => function ($model) {
                    return $model->client->name ?? '';
                },
                'format' => 'raw',
            ],
            'item_count',
            'client_note:ntext',
            'status',
            'amount_paid',
            [
                'attribute' => 'service_center_id',
                'value' => function ($model) {
                    return $model->serviceCenter->name ?? '';
                },
                'format' => 'raw',
            ],
            'maintenance_note:ntext',
            'maintenance_cost',
            'cost_difference',
            [
                'attribute' => 'created_at',
                'value' => function($model){
                    return \common\components\CustomFunc::getFullDate($model->created_at);
                },
            ],
            [
                'attribute' => 'created_by',
                'value' => function($model){
                    return \common\components\CustomFunc::getUserName($model->created_by);
                },
            ],
            [
                'attribute' => 'updated_at',
                'value' => function($model){
                    return \common\components\CustomFunc::getFullDate($model->updated_at);
                },
            ],
            [
                'attribute' => 'updated_by',
                'value' => function($model){
                    return \common\components\CustomFunc::getUserName($model->updated_by);
                },
            ],
        ],
    ]) ?>

</div>
