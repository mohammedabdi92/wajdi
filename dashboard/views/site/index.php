<?php

/** @var yii\web\View $this */
$this->title = 'وجدي لمواد البناء';
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
                        <div class="dashboard-widget-content" style="font-size: large;    font-family: 'Glyphicons Halflings'; text-align: center;">
                            ﴿ وَلَقَدْ مَكَّنَّٰكُمْ فِى ٱلْأَرْضِ وَجَعَلْنَا لَكُمْ فِيهَا مَعَٰيِشَ ۗ قَلِيلًا مَّا تَشْكُرُونَ ﴾

                            <br>
                            <br>
                            "أنا ثالِثُ الشرِيكيْنِ ما لَمْ يَخُنْ أحدُهُما صاحِبَهُ ؛ فإذا خانَ خَرَجْتُ من بينِهِما وجاءَ الشَّيطانُ "
                        </div>
                    </div>
                </div>
            </div>
            <?php if(Yii::$app->user->can('ظهور المنشورات على الرئيسية')):?>
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
                                <?php foreach (\common\models\Posts::find()->all() as $post):?>
                                    <li>
                                        <div class="block">
                                            <div class="block_content">
                                                <h2 class="title">
                                                    <a><?=$post->title ?></a>
                                                </h2>

                                                <p class="excerpt">
                                                    <?=$post->subject ?>
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach;?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <? endif;?>

        </div>

    </div>
</div>
