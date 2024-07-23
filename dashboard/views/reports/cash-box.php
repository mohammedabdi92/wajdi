<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\InventorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'تقرير الصندوق');
$this->params['breadcrumbs'][] = $this->title;



?>
<div class="inventory-index row">
    <h1 style="padding-bottom: 10px;padding-top: 10px;"><?= Html::encode($this->title) ?></h1>
    <?php $form = \yii\widgets\ActiveForm::begin([
        'method' => 'get',
    ]); ?>
    <?= $form->field($modelSearch, 'store_id')->dropDownList( [''=>'اختر ....'] + \yii\helpers\ArrayHelper::map(\common\models\Store::find()->where(['status'=>1])->all(), 'id', 'name'))->label("المحل"); ?>
    <label> التاريخ</label>
    <?= \kartik\daterange\DateRangePicker::widget([
        'model' => $modelSearch,
        'attribute' => 'date',
        'language' => 'en',
        'convertFormat' => true,
        'startAttribute' => 'date_from',
        'endAttribute' => 'date_to',
        'pluginOptions' => [
            'timePicker' => true,
            'locale' => [
                'applyLabel' => 'تطبيق',
                'cancelLabel' => 'الغاء',
                'format' => 'Y-m-d H:i:s',
            ],
            'startDate' => date('Y-m-d 00:00:00'), // Start of the day (12:00 AM)
            'endDate' => date('Y-m-d 23:59:59'), // 12:00 PM of the same day

        ]
    ]); ?>
    <br>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'بحث'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php \yii\widgets\ActiveForm::end(); ?>


    <div class="col-xs-12 col-md-6">
        <p class="lead">الصندوق</p>
        <div class="table-responsive">
            <table class="table">
                <tbody>
                <tr>
                    <th style="width:50%">صافي الصندوق:</th>
                    <td><?= $cash_amount?></td>
                </tr>
                <tr>
                    <th style="width:50%">الداخل على الصندوق :</th>
                    <td><?= $box_in?></td>
                </tr>
                <tr>
                    <th style="width:50%">الخارج من الصندوق :</th>
                    <td><?= $box_out?></td>
                </tr>
                <tr>
                    <th style="width:50%">المبيعات :</th>
                    <td><?= $order_pluse?></td>
                </tr>
                <tr>
                    <th style="width:50%">الدين:</th>
                    <td><?= $debt_sum?></td>
                </tr>
                <tr>
                    <th style="width:50%">السداد :</th>
                    <td><?= $transactions_r_plus?></td>
                </tr>
                <tr>
                    <th style="width:50%"> المدخلات على الصندوق :</th>
                    <td><?= $entries_pluse?></td>
                </tr>
                <tr>
                    <th style="width:50%">التوالف المرجعة :</th>
                    <td><?= $damaged_plus?></td>
                </tr>

                <tr>
                    <th style="width:50%">المشتريات :</th>
                    <td><?= $inventory_order_mince?></td>
                </tr>
                <tr>
                    <th style="width:50%">المصروفات :</th>
                    <td><?= $outlay_mince?></td>
                </tr>
                <tr>
                    <th style="width:50%">المسحوبات :</th>
                    <td><?= $financial_withdrawal_mince?></td>
                </tr>
                <tr>
                    <th style="width:50%">التوالف الغير مرجعة :</th>
                    <td><?= $damaged_mince?></td>
                </tr>
                <tr>
                    <th style="width:50%"> المرجعة :</th>
                    <td><?= $returns_mince?></td>
                </tr>
                <tr>
                    <th style="width:50%">صافي الصندوق بدون المشتريات:</th>
                    <td><?= $cash_amount_without_inventory_order?></td>
                </tr>
               
                <tr>
                    <th style="width:50%">الربح الفواتير:</th>
                    <td><?= $total_profit?></td>
                </tr>
                <tr>
                    <th style="width:50%">الربح الصافي :</th>
                    <td><?= $total_profit_without_damaged_outlay + $debt_sum?></td>
                </tr>
                
                <tr>
                    <th style="width:50%">الربح الصافي مع خصم الدين:</th>
                    <td><?= $total_profit_without_damaged_outlay?></td>
                </tr>


                </tbody>
            </table>
        </div>
    </div>

</div>
