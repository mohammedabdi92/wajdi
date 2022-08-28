<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel mdm\admin\models\searchs\Assignment */
/* @var $usernameField string */
/* @var $extraColumns string[] */

$this->title = Yii::t('rbac-admin', 'Assignments');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="assignment-index">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php
    Pjax::begin([
        'enablePushState'=>false,
    ]);
    $columns = array_merge(
        [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'full_name',
            ],
        ],
        $extraColumns,
        [
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{permession}',
                'buttons' => [
                    'permession' => function ($url, $model, $key) {

                        return Html::a('ارفاق صلاحية', ['assignment/view', 'id' => $model->id], [
                                    'title' => \Yii::t('yii', 'ارفاق صلاحية'),
                                    'data-pjax' => '0',
                                    'class'=>'btn btn-primary',
                                    'style'=>'margin-right:10px'
                        ]);
                    },
                ],
            ],

        ]
    );
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
    ]);
    Pjax::end();
    ?>

</div>
