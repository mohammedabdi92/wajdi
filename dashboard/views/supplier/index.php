<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SupplierSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'الموردين');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-index">

    <h1 style="padding-bottom: 10px;padding-top: 10px;"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'انشاء المورد'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [


            'id',
            'name:ntext',
            [
                'attribute' => 'phone_number',
                'format' => 'html',
                'value' => function($model){
                    return $model->phone_number?'<a href="tel:'.$model->phone_number.'">'.$model->phone_number.'</a>':'';
                },
            ],
            'name_2:ntext',
            [
                'attribute' => 'phone_number_2',
                'format' => 'html',
                'value' => function($model){
                    return $model->phone_number_2?'<a href="tel:'.$model->phone_number_2.'">'.$model->phone_number_2.'</a>':'';
                },
            ],
            'email:ntext',
            'address:ntext',
            //'created_at',
            //'created_by',
            //'updated_at',
            //'updated_by',
            //'isDeleted',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, \common\models\Supplier $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
