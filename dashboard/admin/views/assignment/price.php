<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model yii\web\IdentityInterface */
/* @var $fullnameField string */

$userName = $model->{$usernameField};
if (!empty($fullnameField)) {
    $userName .= ' (' . ArrayHelper::getValue($model, $fullnameField) . ')';
}
$userName = Html::encode($userName);

$this->title = Yii::t('rbac-admin', 'Assignments') . ' : ' . $userName;;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Assignments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $userName;
?>
<div class="assignment-index">

    <div class="row" style='margin-bottom:30px'>
        
    <?= Html::a('Assign Permession', ['assignment/view', 'id' => $model->id], [
                                    'title' => \Yii::t('yii', 'Assign Permession'),
                                    'data-pjax' => '0',
                                    'class'=>'btn btn-primary',
                                    'style'=>'margin-right:10px'
                        ]);
    ?>
        <?= 
    Html::a('Assign Dealer', ['assignment/dealer', 'id' => $model->id], [
                                    'title' => \Yii::t('yii', 'Assign Dealer'),
                                    'data-pjax' => '0',
                                     'class'=>'btn btn-primary',
                                    'style'=>'margin-right:10px'
                        ]);
     ?>
    <?= Html::a('Assign Channel', ['assignment/channel', 'id' => $model->id], [
                                    'title' => \Yii::t('yii', 'Assign Channel'),
                                    'data-pjax' => '0',
                                    'class'=>'btn btn-primary',
                                    'style'=>'margin-right:10px'
                        ]); ?>
    <?= Html::a('Assign Price', ['assignment/price', 'id' => $model->id], [
                                    'title' => \Yii::t('yii', 'Assign Price'),
                                    'data-pjax' => '0',
                                    'class'=>'btn btn-info',
                                    'style'=>'margin-right:10px'
                        ]); ?>
        
    </div>
    
    
    <div class="row cchannel-cost-form">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($modelCostAssignment, 'cost_per_post')->textInput() ?>
            <?= $form->field($modelCostAssignment, 'cost_per_update')->textInput() ?>
        
            <div class="form-group">
                <?= Html::submitButton($modelCostAssignment->isNewRecord ? 'Set price' : 'Update price', ['class' => $modelCostAssignment->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
   
    </div>
</div>


