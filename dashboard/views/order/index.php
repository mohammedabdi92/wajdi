<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'فواتير المبيعات');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1 style="padding-bottom: 10px;padding-top: 10px;"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'انشاء فاتورة بيع'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [


            'id',
            [
                'attribute' => 'customer_id',
                'value' => function($model){
                    return $model->customerTitle;
                },
                'format' => 'raw',
                'filter'=>\yii\helpers\ArrayHelper::map(\common\models\Customer::find()->all(), 'id', 'name')
            ],
            [
                'attribute' => 'store_id',
                'value' => function($model){
                    return $model->storeTitle;
                },
                'format' => 'raw',
            ],
            'total_amount',
            'created_at',
            //'created_by',
            //'updated_at',
            //'updated_by',
            //'isDeleted',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, \common\models\Order $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
                'template' => '{view} {update} {delete} {pdf}',  // the default buttons + your custom button
                'buttons' => [
                    'pdf' => function ($url, $model, $key) {     // render your custom button
                        return Html::a('<i class="fa fa-file-pdf-o" aria-hidden="true"></i>',"report?id=".$model->id,['target'=>'_blank']);
                    }
                ]
            ],
        ],
    ]); ?>


</div>
