<?php
/* @var $this yii\web\View */
/* @var $model \common\models\InventoryOrder */
use yii\helpers\Html;
?>

<table border="0" width="100%" style="padding-bottom: 20px">
    <tr>
        <td align="center">
           وجدي للاعمار ومواد البناء
        </td>
    </tr>
</table>

<table border="0" width="100%" dir="rtl">
    <tr>
        <td bgcolor="#eee" width="33.3%">
            رقم الفاتورة : <?= $model->id;?><br>
            اسم المورد : <?= $model->supplierTitle;?><br>
            رقم المورد : <?= $model->supplier->phone_number ?? '';?><br>

        </td>
        <td align="center" width="33.3%">
           <?php echo Html::img('@web/images/site/icons/vip.svg'); ?>
        </td>
        <td bgcolor="#eee" width="33.3%">
            اسم مصدر الفاتورة  : <?= $model->getUserName('created_by');?><br>
            تاريخ الاصدار : <?= $model->getDate('created_at');?><br>
            اسم المحل : <?= $model->storeTitle ?><br>
        </td>
    </tr>
</table>
<!-- silahkan update data penerbit invoice di bawah ini -->


<br><br>


<!--<h3>Transaction</h3>-->
<table width="100%" cellpadding="5" cellspacing="0" border="1" dir="rtl">
    <tr bgcolor="#eee">
        <th align="center">الرقم</th>
        <th align="center">المادة</th>
        <th align="center">العدد</th>
        <th align="center">الوحدة</th>
        <th align="center">السعر الافرادي</th>
        <th align="center">السعر الاجمالي</th>
        <th align="center">السعر الافرادي الشامل</th>
        <th align="center">السعر الاجمالي الشامل</th>
    </tr>
   <?php foreach ($products as $product) : ?>
        <tr>
            <td align="center"><?= $product->id;?></td>
            <td align="center"><?= $product->product->title ?? '';?></td>
            <td align="center"><?= $product->count;?></td>
            <td align="center"><?= $product->product->getCountTypeName('count_type');?></td>
            <td align="center"><?= $product->product_cost;?></td>
            <td align="center"><?= $product->product_total_cost;?></td>
            <td align="center"><?= $product->product_cost_final;?></td>
            <td align="center"><?= $product->product_total_cost_final;?></td>

        </tr>
    <?php endforeach;?>
    <?php if(!empty($model->discount_percentage)): ?>
        <tr bgcolor="#eee">
            <td colspan="3" align="right"><b> نسبة الخصم</b></td>
            <td align="center"><b>%<?=$model->discount_percentage  ?></b></td>
        </tr>
    <?php endif;?>
    <?php if(!empty($model->discount)): ?>
        <tr bgcolor="#eee">
            <td colspan="3" align="right"><b> قيمة الخصم</b></td>
            <td align="center"><b><?=$model->discount ?></b></td>
        </tr>
    <?php endif;?>
    <?php if(!empty($model->tax_percentage)): ?>
        <tr bgcolor="#eee">
            <td colspan="3" align="right"><b> نسبة الضريبة</b></td>
            <td align="center"><b><?=$model->tax_percentage ?></b></td>
        </tr>
    <?php endif;?>
    <?php if(!empty($model->tax)): ?>
        <tr bgcolor="#eee">
            <td colspan="3" align="right"><b> قيمة الضريبة</b></td>
            <td align="center"><b><?=$model->tax ?></b></td>
        </tr>
    <?php endif;?>
    <?php if(!empty($model->total_count)): ?>
        <tr bgcolor="#eee">
            <td colspan="3" align="right"><b> العدد الاجمالي</b></td>
            <td align="center"><b><?=$model->total_count ?></b></td>
        </tr>
    <?php endif;?>
    <?php if(!empty($model->debt)): ?>
        <tr bgcolor="#eee">
            <td colspan="3" align="right"><b> القيمة المتبقية</b></td>
            <td align="center"><b><?=$model->debt ?></b></td>
        </tr>
        <tr bgcolor="#eee">
            <td colspan="3" align="right"><b> القمة المطلوبة</b></td>
            <td align="center"><b><?=$model->debt+$model->total_cost ?></b></td>
        </tr>
    <?php endif;?>
    <?php if(!empty($model->total_cost)): ?>
        <tr bgcolor="#eee">
            <td colspan="3" align="right"><b> القيمة المدفوعة</b></td>
            <td align="center"><b><?=$model->total_cost ?></b></td>
        </tr>
    <?php endif;?>



</table>
