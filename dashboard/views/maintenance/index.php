<?php

use common\models\Maintenance;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\MaintenanceSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'مواد قيد الصيانة';
$this->params['breadcrumbs'][] = $this->title;
$itemCountSum = $dataProvider->query->sum('item_count');
$costDifferenceSum = $dataProvider->query->sum('cost_difference');
?>
<div class="maintenance-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('انشاء طلب صيانة', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'showFooter' => Yii::$app->user->can('اظهار المجاميع في مواد الصيانة'),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
           
            'id',
            [
                'attribute' => 'client_id',
                'value' => function ($model) {
                    return $model->client->name ?? '';
                },
            ],
            [
                'attribute' => 'item_count',
                'footer' => $itemCountSum, // Format the total sum
                'footerOptions' => ['style' => 'font-weight: bold;'], // Optional: make the footer bold
            ],
            'client_note:ntext',
            [
                'attribute'=> 'status',
                'value' => function ($model) {
                    return $model::statusArray[$model->status];
                }
            ],
            //'amount_paid',
            [
                'attribute' => 'service_center_id',
                'value' => function ($model) {
                    return $model->serviceCenter->name ?? '';
                },
            ],
            //'maintenance_note:ntext',
            //'maintenance_cost',
            [
                'attribute' => 'cost_difference',
                'footer' => $costDifferenceSum, // Format the total sum
                'footerOptions' => ['style' => 'font-weight: bold;'], // Optional: make the footer bold
            ],
            //'created_at',
            //'created_by',
            //'updated_at',
            //'updated_by',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Maintenance $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
