<?php

use common\models\Presence;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\PresenceSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'ادارة البصمة';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="presence-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('انشاء تسجيل دخول', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'type',
                'value' => function($model){
                    return $model->getTypeText();
                },
                'format' => 'raw',
                'filter' => Presence::typesArray,
            ],
            [
                'attribute' => 'user_id',
                'value' => function ($model) {
                    return \common\components\CustomFunc::getUserName($model->user_id);
                },
                'format' => 'raw',
                'filter' => \yii\helpers\ArrayHelper::map(\common\models\User::find()->all(), 'id', 'full_name')
            ],
            'time',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Presence $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
