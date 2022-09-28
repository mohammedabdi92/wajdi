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
        <?php
        if(Yii::$app->user->can('تعديل المواد'))
        {
            echo Html::a(Yii::t('app', 'انشاء مادة'), ['create'], ['class' => 'btn btn-success']);
        }
        ?>

    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [


            'id',
            [
                'attribute'=>'image_name',
                'value'=>function($model){
                    return $model->getImageUrl();
                },
                'format' => ['image',['width'=>'100','height'=>'100']],
            ],
            'title:ntext',
            [
                'attribute' => 'status',
                'value' => function($model){
                    return $model->getStatusText();
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'category_id',
                'value' => function($model){
                    return $model->categoryTitle;
                },
                'filter'=>\yii\helpers\ArrayHelper::map(\common\models\ProductCategory::find()->all(), 'id', 'name'),
                'format' => 'raw',
            ],
            [
                'attribute' => 'count_type',
                'value' => function($model){
                    return $model->getCountTypeName('count_type');
                },
                'filter'=>\yii\helpers\ArrayHelper::map(\common\models\CountType::find()->all(), 'id', 'name'),
                'format' => 'raw',
            ],
            [
                'attribute' => 'min_number',
                'format' => 'raw',
            ],
            'price_1',
            'price_2',
            'price_3',
            'price_4',
            [
                'attribute' => 'updated_by',
                'value' => function($model){
                    return \common\components\CustomFunc::getUserName($model->updated_by);
                },
                'filter'=>\yii\helpers\ArrayHelper::map(\common\models\User::find()->all(), 'id', 'full_name')
            ],
//            'isDeleted',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, \common\models\Product $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 },
                'visibleButtons' => [
                    'update' => function ($model) {
                        return Yii::$app->user->can('تعديل المواد');
                    },
                    'view' => function ($model) {
                        return Yii::$app->user->can('تعديل المواد');
                    },
                ]
            ],
        ],
    ]); ?>


</div>
