<?php
use yii\helpers\Json;

/** @var yii\web\View $this */
$this->title = 'وجدي لمواد البناء';

?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php
$days = Json::encode($days);
$orderAmounts = Json::encode($orderAmounts);
$inventoryAmounts = Json::encode($inventoryAmounts);
$outlaysAmounts = Json::encode($outlaysAmounts);
$debtsAmounts = Json::encode($debtsAmounts);
$repaymentsAmounts = Json::encode($repaymentsAmounts);

$js = <<<JS
    var ctx = document.getElementById('chart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: $days,
            datasets: [
                {
                    label: 'المبيعات',
                    data: $orderAmounts,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: false
                },
                {
                    label: 'المشتريات',
                    data: $inventoryAmounts,
                    borderColor: 'rgba(153, 102, 255, 1)',
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    fill: false
                }
            ]
        },
        options: {
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    var ctx2 = document.getElementById('chart2').getContext('2d');
    var chart2 = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: $days,
            datasets: [
                {
                    label: 'المصاريف',
                    data: $outlaysAmounts,
                    // borderColor: 'rgba(75, 192, 192, 1)',
                    // backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: false
                },
              
            ]
        },
        options: {
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    var ctx3 = document.getElementById('chart3').getContext('2d');
    var chart3 = new Chart(ctx3, {
        type: 'line',
        data: {
            labels: $days,
            datasets: [
                {
                    label: 'الدين',
                    data: $debtsAmounts,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: false
                },
                {
                    label: 'السداد',
                    data: $repaymentsAmounts,
                    borderColor: 'rgba(153, 102, 255, 1)',
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    fill: false
                }
            ]
        },
        options: {
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });
JS;

$this->registerJs($js);
?>
<div class="" role="main">

    <div class="">

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title" style="text-align: center;    font-size: large; font-weight: bold;">
                        بسم الله الرحمن الرحيم
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="dashboard-widget-content"
                            style="font-size: large;    font-family: 'Glyphicons Halflings'; text-align: center;">
                            ﴿ وَلَقَدْ مَكَّنَّٰكُمْ فِى ٱلْأَرْضِ وَجَعَلْنَا لَكُمْ فِيهَا مَعَٰيِشَ ۗ قَلِيلًا مَّا
                            تَشْكُرُونَ ﴾

                            <br>
                            <br>
                            "أنا ثالِثُ الشرِيكيْنِ ما لَمْ يَخُنْ أحدُهُما صاحِبَهُ ؛ فإذا خانَ خَرَجْتُ من بينِهِما
                            وجاءَ الشَّيطانُ "
                        </div>
                    </div>
                </div>
            </div>
            <?php if (Yii::$app->user->can('ظهور المنشورات على الرئيسية')): ?>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>
                                المنشورات
                            </h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>

                                <li><a class="close-link"><i class="fa fa-close"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="dashboard-widget-content">
                                <ul class="list-unstyled timeline widget">
                                    <?php foreach (\common\models\Posts::find()->all() as $post): ?>
                                        <li>
                                            <div class="block">
                                                <div class="block_content">
                                                    <h2 class="title">
                                                        <a><?= $post->title ?></a>
                                                    </h2>

                                                    <p class="excerpt">
                                                        <?= $post->subject ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (Yii::$app->user->can('اظهار مخطط التقارير بالرئيسية')): ?>

            <div class="row tile_count">
                <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-dollar"></i> البيع</span>
                    <div class="count"><?= round($orderAmount ?? 0, 2) ?></div>

                </div>
                <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-dollar"></i> مجموع الخصم </span>
                    <div class="count"><?= round($totalDiscount ?? 0, 2) ?></div>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-dollar"></i> قيمة المرجع</span>
                    <div class="count"> <?= round($returnsAmount ?? 0 , 2)?></div>

                </div>
                <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-dollar"></i> المصروف</span>
                    <div class="count"><?= round($outlayAmount ?? 0, 2) ?></div>

                </div>

                <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-dollar"></i> الدين</span>
                    <div class="count"><?= round($debtAmount ?? 0, 2) ?></div>

                </div>
                <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-dollar"></i> السداد</span>
                    <div class="count"><?= round($repaymentAmount ?? 0, 2) ?></div>

                </div>
                <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-dollar"></i> الشراء</span>
                    <div class="count"><?= round($inventoryOrderAmount ?? 0, 2) ?></div>

                </div>


            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="dashboard_graph x_panel">
                    <div class="row x_title">
                        <div class="col-md-12">
                            <h3>البيع والشراء الشهري
                            </h3>
                        </div>

                    </div>
                    <div class="x_content">
                        <div class="demo-container">

                            <canvas id="chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="dashboard_graph x_panel">
                    <div class="row x_title">
                        <div class="col-md-12">
                            <h3>المصاريف
                            </h3>
                        </div>

                    </div>
                    <div class="x_content">
                        <div class="demo-container">

                            <canvas id="chart2"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="dashboard_graph x_panel">
                    <div class="row x_title">
                        <div class="col-md-12">
                            <h3>الدين والسداد
                            </h3>
                        </div>

                    </div>
                    <div class="x_content">
                        <div class="demo-container">

                            <canvas id="chart3"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>


        </div>

    </div>
</div>