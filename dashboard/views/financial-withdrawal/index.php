<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\FinancialWithdrawalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'المسحوبات من الصندوق');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="financial-withdrawal-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'انشاء مسحوبات من الصندوق'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'amount',
            [
                'attribute' => 'status',
                'value' => function($model){
                    return $model->getStatusText();
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'user_id',
                'value' => function ($model) {
                    return \common\components\CustomFunc::getUserName($model->user_id);
                },
                'format' => 'raw',
                'filter' => \yii\helpers\ArrayHelper::map(\common\models\User::find()->all(), 'id', 'full_name')
            ],
            'note:ntext',
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
                'urlCreator' => function ($action, \common\models\FinancialWithdrawal $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
