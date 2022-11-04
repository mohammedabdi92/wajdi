<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use kartik\daterange\DateRangePicker;

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

    <label> التاريخ</label>
    <?= DateRangePicker::widget([
        'model' => $searchModel,
        'attribute' => 'time',
        'language' => 'en',
        'convertFormat' => true,
        'startAttribute' => 'time_from',
        'endAttribute' => 'time_to',
        'pluginOptions' => [
            'timePicker' => true,
            'timePickerIncrement' => 30,
            'locale' => [
                'format' => 'Y-m-d'
            ]
        ]
    ]); ?>
    <br>




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
    <div class="row">
        <div class="col-xs-6">
            <p class="lead">المجموع</p>
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                    <tr>
                        <th style="width:50%">وقت الدوام :</th>
                        <td><?= $searchModel->total_diff_time_out_mints?></td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
