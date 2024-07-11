<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\ReturnsGroup $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Returns Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<div class="returns-group-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    <?= Html::a(Yii::t('app', 'رجوع'), ['index'], ['class' => 'btn btn-primary']) ?>
    <?php
            if(Yii::$app->user->can('تعديل وحذف المرجع من العميل للمحل'))
            {
                Html::a(Yii::t('app', 'تعديل'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
            }
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'order_id',
            'returner_name:ntext',
            'total_count',
            'total_amount',
            
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
        ],
    ]) ?>

</div>
<div class="row">
        <div class="col-md-6">
            <label>المواد</label>
            <?php
            $returns = $model->returns;
            foreach ($returns as $item) {
                echo "<br><div>اسم المادة :   ". $item->productTitle." </div>";
                echo DetailView::widget([
                    'model' => $item,
                    'attributes' => [
                        'count',
                        'amount',
                        

                    ],
                ]);
            }


            ?>
        </div>
</div>