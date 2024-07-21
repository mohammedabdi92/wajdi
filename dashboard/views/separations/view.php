<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Separations $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Separations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="separations-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    <?= Html::a(Yii::t('app', 'رجوع'), ['index'], ['class' => 'btn btn-primary']) ?>

        <?php 
        if(Yii::$app->user->can('حذف طلب فرط وجمع المواد'))
        {
            echo Html::a(Yii::t('app', 'حذف'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'هل انت متاكد من الحذف ؟'),
                    'method' => 'post',
                ],
            ]) ;
        }
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'store_id',
                'value' => function($model){
                    return $model->storeTitle;
                },
                'format' => 'raw',
            ],
            'store_id',
            'product_id_from',
            [
                'attribute' => 'product_id_from',
                'value' => function($model){
                    return $model->productFromTitle;
                },
                'label'=>"المادة",
                'format' => 'raw',

            ],
            'count_from',
            'product_id_to',
            [
                'attribute' => 'product_id_to',
                'value' => function($model){
                    return $model->productToTitle;
                },
                'label'=>"المادة",
                'format' => 'raw',

            ],
            'count_to',
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
