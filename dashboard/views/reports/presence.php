<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel common\models\Presence */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'تقرير البصمة');
$this->params['breadcrumbs'][] = $this->title;



?>
<div class="inventory-index">

    <h1 style="padding-bottom: 10px;padding-top: 10px;"><?= Html::encode($this->title) ?></h1>

    <p>
    </p>

    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'id'=>'products'
    ]); ?>


    <?= $form->field($searchModel, 'user_id')->dropDownList( [''=>'اختر ....'] + \yii\helpers\ArrayHelper::map(\common\models\User::find()->all(), 'id', 'full_name')); ?>





    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'بحث'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'حذف'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

            'id',
            [
                'attribute' => 'user_id',
                'value' => function ($model) {
                    return \common\components\CustomFunc::getUserName($model->user_id);
                },
                'filter' => \yii\helpers\ArrayHelper::map(\common\models\User::find()->all(), 'id', 'full_name')
            ],
            'time',
            'time_out',
            'diff_time_out',


        ],
    ]); ?>


</div>
