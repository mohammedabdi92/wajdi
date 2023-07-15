<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Customer */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="customer-view">

    <h1 style="padding-bottom: 10px;padding-top: 10px;"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'رجوع'), ['index'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'تعديل'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'حذف'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'هل انت متاكد من الحذف ؟'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name:ntext',
            [
                'attribute' => 'phone_number',
                'format' => 'html',
                'value' => function($model){
                    return $model->phone_number?'<a href="tel:'.$model->phone_number.'">'.$model->phone_number.'</a>':'';
                },
            ],
            'email:ntext',
            'address:ntext',
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
        ],
    ]) ?>
    <p>
        <h2>
        حركات الدين
    </h2>
    </p>
    <?= \yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

            'id',
            [
                'attribute' => 'customer_id',
                'value' => function ($model) {
                    return $model->customer->name ?? '';
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'order_id',
                'value' => function($model)
                {
                    return Html::a($model->order_id, '/order/view?id='.$model->order_id,['target'=>'_blank'] );
                },
                'format' => 'raw',
            ],
            'amount',
            [
                'attribute' => 'type',
                'value' => function($model){
                    return $model->getTypeText();
                },
                'format' => 'raw',
            ],
            'note:ntext',
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return \common\components\CustomFunc::getFullDate($model->created_at);
                },

            ],
            [
                'attribute' => 'created_by',
                'value' => function ($model) {
                    return \common\components\CustomFunc::getUserName($model->created_by);
                },
            ],
            [
                'attribute' => 'updated_at',
                'value' => function ($model) {
                    return \common\components\CustomFunc::getFullDate($model->updated_at);
                },

            ],
            [
                'attribute' => 'updated_by',
                'value' => function ($model) {
                    return \common\components\CustomFunc::getUserName($model->updated_by);
                },

            ],
        ],
    ]); ?>

</div>
