<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Damaged */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Damageds'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="damaged-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'رجوع'), ['index'], ['class' => 'btn btn-primary']) ?>
        <?php 
        if(Yii::$app->user->can('تعديل وحذف بضاعة تالفة من العميل للمحل'))
        {
          echo  Html::a(Yii::t('app', 'تعديل'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
        }
         ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'order_id',
            [
                'attribute' => 'product_id',
                'value' => function($model){
                    return $model->productTitle;
                },

            ],
            'count',
            'amount',
            'cost_value',
            'total_amount',
            [
                'attribute' => 'status',
                'value'=> function($model){ 
                    return $model->getStatusText();
                },
            ],
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

</div>
