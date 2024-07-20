<?php
/* @var $this yii\web\View */
/* @var $model \common\models\InventoryOrder */
/* @var $products array */

use yii\helpers\Html;
use common\components\Arabic;

$Arabic = new Arabic();

?>
<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">

</head>

<body>


    <table class="invoice-info">
        <tr>
            <td style="width: 33.3%;">
                <table style=" font-size: small; ">
                    <tr>
                        <th style="text-align: right;width: 32.3%;font-size: small;">رقم الفاتورة</th>
                        <td style=" font-size: small; "><?= Html::encode($model->id); ?></td>
                    </tr>
                    <tr>
                        <th style="text-align: right; font-size: small;">اسم المشتري</th>
                        <td style=" font-size: small; "><?= Html::encode($model->customerTitle); ?></td>
                    </tr>
                    <tr>
                        <th style="text-align: right; font-size: small;">رقم المشتري</th>
                        <td style=" font-size: small; "><?= Html::encode($model->customer->phone_number ?? ''); ?></td>
                    </tr>
                </table>
            </td>
            <td class="center" style="width: 33.3%;">
                <div class="invoice-header">
                    <p>اهلا وسهلا بكم </p><br>
                    <p>كل ما يلزم البناء تحت سقف واحد</p><br>
                </div>
                <?= Html::img('@web/images/site/icons/wajdi.png',[  'width' => 100,
    'height' => 100]); ?>

                <div class="invoice-header">
                    <p><?= Html::encode($model->storeTitle ?? ''); ?></p>
                </div>
            </td>
            <td style="width: 33.3%;">
                <table s>
                    <tr>
                        <th style="text-align: right;width: 32.3%;font-size: small;"> مصدر الفاتورة</th>
                        <td style=" font-size: small; "><?= Html::encode($model->getUserName('created_by')); ?></td>
                    </tr>
                    <tr>
                        <th style="text-align: right;font-size: small;">تاريخ الاصدار</th>
                        <td style=" font-size: 10px; "><?= Html::encode($model->getDate('created_at')); ?></td>
                    </tr>
                    <tr>
                        <th style="text-align: right;font-size: small;">اسم المحل</th>
                        <td style=" font-size: 10px; "><?= Html::encode($model->storeTitle ?? ''); ?></td>
                    </tr>
                   
                </table>
            </td>
        </tr>
    </table>
    <table class="invoice-products">
        <thead>
            <tr class="summary-item">
                <th>الرقم</th>
                <th>المادة</th>
                <th>العدد</th>
                <th>الوحدة</th>
                <th>السعر الافرادي</th>
                <th>السعر الاجمالي</th>
                <?php if (!empty($model->total_price_discount_product)): ?>
                    <th>الخصم</th>
                    <th>السعر بعد الخصم</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $key => $product): ?>
                <?php if ($product->discount) {
                    $model->total_discount += $product->discount;
                } ?>
                <tr>
                    <td><?= Html::encode($key + 1); ?></td>
                    <td><?= Html::encode($product->product->title ?? ''); ?></td>
                    <td><?= Html::encode($product->count); ?></td>
                    <td><?= Html::encode($product->product->getCountTypeName('count_type')); ?></td>
                    <td><?= Html::encode($product->amount); ?></td>
                    <td><?= Html::encode($product->total_product_amount); ?></td>
                    <?php if (!empty($product->discount)): ?>
                        <td><?= Html::encode($product->discount); ?></td>
                        <td><?= Html::encode($product->total_product_amount - $product->discount); ?></td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <table class="invoice-summary" style="text-align: left; width:100%">

        <tr class="summary-item">
            <td style="text-align: right; width:25%"><b>عدد القطع الاجمالي:</b> </td>
            <td style="text-align: right; width:15%"> <?= Html::encode($model->total_count); ?></td>
        </tr>

        <?php if (!empty($model->total_discount)): ?>
            <tr class="summary-item">
                <td style="text-align: right;"><b>السعر قبل الخصم:</b> </td>
                <td style="text-align: right;"><?= Html::encode($model->total_amount_without_discount); ?></td>
             
            </tr>

            <tr class="summary-item">
                <td style="text-align: right;"><b>مجموع الخصم:</b></td>
                <td style="text-align: right;"> <?= Html::encode($model->total_discount); ?></td>
            </tr>
        <?php endif; ?>

        <tr class="summary-item">
            <td style="text-align: right;"><b>السعر النهائي:</b> </td>
            <td style="text-align: right;"><?= Html::encode($model->total_amount); ?></td>
            <td class="center" style="text-align: right;">
                <?php echo $Arabic->money2str($model->total_amount, 'KWD', 'ar') ?></td>
        </tr>

    </table>
    <p>استلمت المواد كاملة وخالية من اي عيوب &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  اسم وتوقيع المستلم _________________________</p>



</body>

</html>