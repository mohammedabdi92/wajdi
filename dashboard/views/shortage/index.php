<?php

use common\models\Shortage;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\ShortageSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'النقص';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shortage-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('انشاء نقص', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'note:ntext',
            [
                'attribute' => 'store_id',
                'value' => function($model){

                    return $model->storeTitle??'';
                },

            ],
            [
                'attribute' => 'product_id',
                'value' => function($model){
                    return $model->productTitle;
                },
                'format' => 'raw',
            ],
            'count',
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
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Shortage $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
