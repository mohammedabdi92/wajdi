<?php

use common\models\ServiceCenter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\ServiceCenterSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'مراكز الصيانة';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-center-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('انشاء مركز صيانة', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'phone',
            'location',
            'responsible_person',
            //'created_at',
            //'created_by',
            //'updated_at',
            //'updated_by',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, ServiceCenter $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
