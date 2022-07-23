<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'المواد');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1 style="padding-bottom: 10px;padding-top: 10px;"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'انشاء مادة'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title:ntext',
            [
                'attribute' => 'category_id',
                'value' => function($model){
                    return $model->categoryTitle;
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'count_type',
                'value' => function($model){
                    return \common\components\Constants::getCountTypesName($model->count_type);
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'min_number',
                'format' => 'raw',
            ],
            'price_1',
            'price_2',
            'price_3',
            [
                'attribute' => 'updated_by',
                'value' => function($model){
                    return \common\components\CustomFunc::getUserName($model->updated_by);
                },
            ],
//            'isDeleted',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, \common\models\Product $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
