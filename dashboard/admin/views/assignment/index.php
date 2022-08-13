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
                'attribute' => 'user_name',
            ],
        ],
        $extraColumns,
        [
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{permession}{dealer}{channel}{price}',
                'buttons' => [
                    'permession' => function ($url, $model, $key) {

                        return Html::a('Assign Permession', ['assignment/view', 'id' => $model->id], [
                                    'title' => \Yii::t('yii', 'Assign Permession'),
                                    'data-pjax' => '0',
                                    'class'=>'btn btn-primary',
                                    'style'=>'margin-right:10px'
                        ]);
                    },
                    'dealer' => function ($url, $model, $key) {

                        return Html::a('Assign Dealer', ['assignment/dealer', 'id' => $model->id], [
                                    'title' => \Yii::t('yii', 'Assign Dealer'),
                                    'data-pjax' => '0',
                                     'class'=>'btn btn-primary',
                                    'style'=>'margin-right:10px'
                        ]);
                    },
                    'channel' => function ($url, $model, $key) {

                        return Html::a('Assign Channel', ['assignment/channel', 'id' => $model->id], [
                                    'title' => \Yii::t('yii', 'Assign Channel'),
                                    'data-pjax' => '0',
                                    'class'=>'btn btn-primary',
                                    'style'=>'margin-right:10px'
                        ]);
                    },
                    'price' => function ($url, $model, $key) {

                        return Html::a('Assign Price', ['assignment/price', 'id' => $model->id], [
                                    'title' => \Yii::t('yii', 'Assign Price'),
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
