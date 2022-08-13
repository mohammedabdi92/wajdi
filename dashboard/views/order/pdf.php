<?php
/* @var $this yii\web\View */

/* @var $model \common\models\InventoryOrder */

use yii\helpers\Html;
use common\components\Arabic;


$Arabic = new  Arabic();
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
            رقم الفاتورة : <?= $model->id; ?><br>
            اسم المشتري : <?= $model->customerTitle; ?><br>
            رقم المشتري : <?= $model->customer->phone_number ?? ''; ?><br>

        </td>
        <td align="center" width="33.3%">
            <?php echo Html::img('@web/images/site/icons/vip.svg'); ?>
        </td>
        <td bgcolor="#eee" width="33.3%">
            اسم مصدر الفاتورة : <?= $model->getUserName('created_by'); ?><br>
            تاريخ الاصدار : <?= $model->getDate('created_at'); ?><br>
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
        <?php if (!empty($model->total_price_discount_product)): ?>
            <th align="center">الخصم</th>
            <th align="center">السعر بعد الخصم</th>
        <?php endif; ?>

    </tr>
    <?php foreach ($products as $product) : ?>
        <tr>
            <td align="center"><?= $product->id; ?></td>
            <td align="center"><?= $product->product->title ?? ''; ?></td>
            <td align="center"><?= $product->count; ?></td>
            <td align="center"><?= $product->product->getCountTypeName('count_type'); ?></td>
            <td align="center"><?= $product->amount; ?></td>
            <td align="center"><?= $product->total_product_amount; ?></td>
            <?php if (!empty($model->total_price_discount_product)): ?>
                <td align="center"><?= $product->discount; ?></td>
                <td align="center"><?= $product->total_product_amount - $product->discount; ?></td>
            <?php endif; ?>


        </tr>

    <?php endforeach; ?>
    <tr bgcolor="#eee">
        <td colspan="2" align="right"><b> العدد الاجمالي</b></td>
        <td align="center"><b><?= $model->total_count ?></b></td>


    </tr>

    <?php if (!empty($model->total_price_discount_product)): ?>
    <tr bgcolor="#eee">
        <td colspan="5"></td>
        <td colspan="2" align="center"><b> القمة المطلوبة</b></td>
        <td align="center"><b><?= $model->debt + $model->total_amount ?></b></td>
    </tr>
    <?php endif; ?>

    <?php if (!empty($model->total_price_discount_product)): ?>
        <tr bgcolor="#eee">
            <td colspan="5"></td>
            <td colspan="2" align="center"><b> مجموع الخصم</b></td>
            <td align="center"><b><?= $model->total_price_discount_product ?></b></td>
        </tr>
    <?php endif; ?>
    <?php if (!empty($model->debt)): ?>
        <tr bgcolor="#eee">
            <td colspan="5"></td>
            <td colspan="2" align="center"><b> الدين</b></td>
            <td align="center"><b><?= $model->debt ?></b></td>
        </tr>
    <?php endif; ?>

    <?php if (!empty($model->debt)): ?>
        <tr bgcolor="#eee">
            <td colspan="5"></td>
            <td colspan="2" align="center"><b> القيمة الدين</b></td>
            <td align="center"><b><?= $model->debt ?></b></td>
        </tr>



    <?php endif; ?>

    <tr bgcolor="#eee">
        <td colspan="3"></td>
        <td colspan="<?=(!empty($model->total_price_discount_product))?2:1?>" align="center"><b><?=$Arabic->money2str($model->total_amount, 'KWD', 'ar')  ?></b></td>
        <td colspan="<?=(!empty($model->total_price_discount_product))?2:1?>" align="center"><b> السعر النهائي</b></td>
        <td colspan="1" align="center"><b><?=$model->total_amount ?></b></td>
    </tr>

</table>
