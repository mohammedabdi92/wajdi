<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use common\models\Damaged;

/* @var $this yii\web\View */
/* @var $searchModel common\models\DamagedSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = Yii::t('app', 'البضاعة التالفة');
$this->params['breadcrumbs'][] = $this->title;

$totalSum = $dataProvider->query->sum('total_amount');
$totalcount = $dataProvider->query->sum('count');

?>
<div class="damaged-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'انشاء طلب تالف'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'showFooter' => true,
        'columns' => [
            'id',

            [
                'attribute' => 'order_id',
                'value' => function ($model) {
                    return $model->order_id ? Html::a($model->order_id, '/order/view?id='.$model->order_id,['target'=>'_blank'] ) : '';
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'product_name',
                'value' => function($model){
                    return $model->productTitle;
                },
                'label'=>"المادة",
                'format' => 'raw',

            ],
            [
                'attribute' => 'count',
                'footer' => $totalcount, // Format the total sum
                'footerOptions' => ['style' => 'font-weight: bold;'], // Optional: make the footer bold
            ],
            [
                'attribute' => 'total_amount',
                'footer' => $totalSum, // Format the total sum
                'footerOptions' => ['style' => 'font-weight: bold;'], // Optional: make the footer bold
            ],
            //'amount',
            //'created_at',
            //'created_by',
            //'updated_at',
            //'updated_by',
            [
                'class' => ActionColumn::className(),'icons'=>[
                'eye-open' => '<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:2em;overflow:visible;vertical-align:-.125em;width:2em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M573 241C518 136 411 64 288 64S58 136 3 241a32 32 0 000 30c55 105 162 177 285 177s230-72 285-177a32 32 0 000-30zM288 400a144 144 0 11144-144 144 144 0 01-144 144zm0-240a95 95 0 00-25 4 48 48 0 01-67 67 96 96 0 1092-71z"/></svg>',
                'pencil' => '<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:2em;overflow:visible;vertical-align:-.125em;width:2em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M498 142l-46 46c-5 5-13 5-17 0L324 77c-5-5-5-12 0-17l46-46c19-19 49-19 68 0l60 60c19 19 19 49 0 68zm-214-42L22 362 0 484c-3 16 12 30 28 28l122-22 262-262c5-5 5-13 0-17L301 100c-4-5-12-5-17 0zM124 340c-5-6-5-14 0-20l154-154c6-5 14-5 20 0s5 14 0 20L144 340c-6 5-14 5-20 0zm-36 84h48v36l-64 12-32-31 12-65h36v48z"/></svg>',
                'trash' => '<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:2em;overflow:visible;vertical-align:-.125em;width:2em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M32 464a48 48 0 0048 48h288a48 48 0 0048-48V128H32zm272-256a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zM432 32H312l-9-19a24 24 0 00-22-13H167a24 24 0 00-22 13l-9 19H16A16 16 0 000 48v32a16 16 0 0016 16h416a16 16 0 0016-16V48a16 16 0 00-16-16z"/></svg>',
            ],
                'urlCreator' => function ($action, \common\models\Damaged $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 },
                'visibleButtons' => [
                    'update' => function ($model) {
                        return Yii::$app->user->can('تعديل وحذف بضاعة تالفة من العميل للمحل');
                    },
                    'delete' => function ($model) {
                        return Yii::$app->user->can('تعديل وحذف بضاعة تالفة من العميل للمحل');
                    },
                ]
            ],
        ],
    ]); ?>


</div>
