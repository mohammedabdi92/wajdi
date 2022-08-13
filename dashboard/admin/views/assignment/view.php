<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use dashboard\admin\AdminAsset;
use yii\helpers\Json;
use yii\helpers\Url;

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
                                    'class'=>'btn btn-info',
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
                                    'class'=>'btn btn-primary',
                                    'style'=>'margin-right:10px'
                        ]); ?>
        
    </div>
    
    <div class="row">
        <div class="col-lg-5">
            <?= Yii::t('rbac-admin', 'Permissions Avaliable') ?>:
            <input id="search-avaliable"><br>
            <select id="list-avaliable" multiple size="20" style="width: 100%">
            </select>
        </div>
        <div class="col-lg-1">
            <br><br>
            <a href="#" id="btn-assign" class="btn btn-success">&gt;&gt;</a><br>
            <a href="#" id="btn-revoke" class="btn btn-danger">&lt;&lt;</a>
        </div>
        <div class="col-lg-5">
            <?= Yii::t('rbac-admin', 'Permissions Assigned') ?>:
            <input id="search-assigned"><br>
            <select id="list-assigned" multiple size="20" style="width: 100%">
            </select>
        </div>
    </div>
</div>
<?php
AdminAsset::register($this);
$properties = Json::htmlEncode([
        'userId' => $model->{$idField},
        'assignUrl' => Url::to(['assign']),
        'searchUrl' => Url::to(['search']),
    ]);
$js = <<<JS
yii.admin.initProperties({$properties});

$('#search-avaliable').keydown(function () {
    yii.admin.searchAssignmet('avaliable');
});
$('#search-assigned').keydown(function () {
    yii.admin.searchAssignmet('assigned');
});
$('#btn-assign').click(function () {
    yii.admin.assign('assign');
    return false;
});
$('#btn-revoke').click(function () {
    yii.admin.assign('revoke');
    return false;
});

yii.admin.searchAssignmet('avaliable', true);
yii.admin.searchAssignmet('assigned', true);
JS;
$this->registerJs($js);

