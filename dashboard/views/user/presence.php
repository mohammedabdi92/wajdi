<?php
?>
<h2 style="text-align: center;">بصمة دوام الموظف</h2>
<?php if($last_login):?>
<h3 style="text-align: center;">  اخر تسجيل <?=$last_login->getTypeText()?><br>
 <?=$last_login->time?>
<?php endif;?>
</h3>
<form id="myForm" action="" method="get">

    <div class="bs-glyphicons" style=" padding-right: 45%; ">

            <?= \yii\helpers\Html::submitButton('<span class="glyphicon glyphicon-log-out" aria-hidden="true"></span><span class="glyphicon-class">خروج</span>', [ 'name'=>'submit', 'value'=>'out','class' => 'submit','disabled' => (!empty($last_login) && $last_login->type == \common\models\Presence::TYPE_IN)?false:true]) ?>
            <?= \yii\helpers\Html::submitButton('<span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> <span class="glyphicon-class">دخول</span>', ['name'=>'submit', 'value'=>'in','class' => 'submit','disabled' => (empty($last_login) || (!empty($last_login) && $last_login->type == \common\models\Presence::TYPE_OUT) )?false:true]) ?>

    </div>
</form>


