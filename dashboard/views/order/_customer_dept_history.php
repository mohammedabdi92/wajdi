<?php

?>
<?php if(!empty($dept_data)):?>
<p>
    <h4>
        <?= \yii\helpers\Html::a("(".$dept_data['customer_name'].")",'/customer/view?id='.$dept_data['customer_id'],['target'=>'_blank'] ) ?>
         |
        مجموع الديون :
        <?= $dept_data['debt_amount']?>
         |
        مجموع السداد :
        <?= $dept_data['repayment_amount']?>
         |
        المتبقي لسداد :
        <?= $dept_data['debt_amount'] -$dept_data['repayment_amount']?>


    </h4>

</p>
<?php endif;?>
