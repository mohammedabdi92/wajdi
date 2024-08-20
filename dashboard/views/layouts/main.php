<?php

/**
 * @var string $content
 * @var \yii\web\View $this
 */

use yii\helpers\Html;
use rmrevin\yii\fontawesome\FA;
use dashboard\admin\components\MenuHelper;




$bundle = mortezakarimi\gentelellartl\assets\Asset::register($this);
$user = Yii::$app->user->identity;
Yii::$app->view->registerCssFile( '/css/site.css',['position' => \yii\web\View::POS_END,'depends' => mortezakarimi\gentelellartl\assets\Asset::className()]);
Yii::$app->view->registerJsFile( '/js/yii.admin.js',['position' => \yii\web\View::POS_END,'depends' => mortezakarimi\gentelellartl\assets\Asset::className()]);
?>
<?php $this->beginPage(); ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" dir="rtl">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <?php
//    print_r("<pre>");
//    print_r(Yii::$app->requestedRoute);die;
    ?>
    <?php if( in_array(Yii::$app->requestedRoute,['inventory-order/create','inventory-order/update','order/create','order/update','ar-order/update','returns-group/create','outlay/create']) ) :?>
    <script>

        window.onbeforeunload = popup;
        function popup() {
            return 'هل تريد الخروج من الصفحة قبل حفظ المعلومات';
        }
    </script>
    <?php endif; ?>
    <!-- /header content -->
    <body  onload="clock()" class="nav-<?= !empty($_COOKIE['menuIsCollapsed']) && $_COOKIE['menuIsCollapsed'] == 'true' ? 'sm' : 'md' ?>">
    <?php $this->beginBody(); ?>
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col hidden-print">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                        <b><?= Html::a(FA::i(FA::_PAW) . Html::tag('span', ' مجموعة وجدي للاعمار '), Yii::$app->homeUrl,["class"=>"site_title"]) ?></b>
                    </div>

                    <div class="clearfix"></div>

                    <!-- menu profile quick info -->
                    <div class="profile clearfix">
                        <div class="profile_pic">
                            <img src="/images/site/icons/avatar.webp" alt="..." class="img-circle profile_img">
                        </div>
                        <div class="profile_info">
                            <span><?=$user->full_name?></span>

                        </div>
                    </div>
                    <!-- /menu profile quick info -->

                    <br/>
                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        <div class="menu_section">
                            <h3>القائمة</h3>
                            <?php
                            echo MenuHelper::renderMenu(MenuHelper::getAssignedMenuCustom(Yii::$app->user->id));
//                            echo mortezakarimi\gentelellartl\widgets\Menu::widget(
//                                [
//                                    "items" => [
//                                        ["label" => 'الرئيسية', "url" => "/", "icon" => "home"],
//                                        [
//                                            "label" => "التقارير",
//                                            "icon" => "th",
//                                            "items" => [
//                                                ["label" => "المخزن", "url" => ["reports/products"]],
//                                                ["label" => "البيع", "url" => ["reports/order-product"]],
//                                                ["label" => "المشتريات", "url" => ["reports/inventory-order-product"]],
//                                            ],
//                                        ],
//                                        ["label" => 'بصمة دوام', "url" => "/user/presence", "icon" => "hand-pointer-o"],
//                                        ["label" => 'المستخدمين', "url" => "/user/index", "icon" => "user"],
//                                        ["label" => 'اقسام المواد', "url" => "/product-category/index", "icon" => "archive"],
//
//                                        ["label" => ' المواد', "url" => "/product/index", "icon" => "archive"],
//                                        ["label" => 'العملاء', "url" => "/customer/index", "icon" => "male"],
//                                        ["label" => 'الموردين', "url" => "/supplier/index", "icon" => "briefcase"],
//                                        ["label" => ' فواتير المشتريات', "url" => "/inventory-order/index", "icon" => "handshake-o"],
//                                        ["label" => 'فواتير المبيعات', "url" => "/order/index", "icon" => "money"],
//                                        [
//                                            "label" => "المخزن",
//                                            "icon" => "th",
//                                            "items" => [
//                                                ["label" => "مواد الافرع", "url" => ["inventory/index"]],
//                                                ["label" => "النقليات", "url" => ["transfer-order/index"]],
////                                                ["label" => "پنل", "url" => ["site/panel"]],
//                                            ],
//                                        ],
//                                        [
//                                            "label" => "اعدادات",
//                                            "icon" => "th",
//                                            "items" => [
//                                                ["label" => "اعدادات النسب", "url" => ["setting/rate"]],
//                                                ["label" => 'انواع العد', "url" => "/count-type/index"],
////                                                ["label" => "پنل", "url" => ["site/panel"]],
//                                            ],
//                                        ],
//
//                                        ["label" => 'البضاعة التالفة', "url" => "/damaged/index", "icon" => "exclamation-triangle"],
//                                        ["label" => 'المرجع', "url" => "/returns/index", "icon" => "retweet"],
//                                        ["label" => 'المصروفات', "url" => "/outlay/index", "icon" => "money"],
//                                        ["label" => 'المسحوبات من الصندوق', "url" => "/financial-withdrawal/index", "icon" => "money"],
//                                    ],
//                                ]
//                            )
                            ?>
                        </div>
                    </div>
                    <!-- /sidebar menu -->

                    <!-- /menu footer buttons -->
                    <div class="sidebar-footer hidden-small">
                        <a data-toggle="tooltip" data-placement="top" title="تمام صفحه" onclick="toggleFullScreen();">
                            <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="قفل" class="lock_btn">
                            <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                        </a>
                        <?= Html::a('<span class="glyphicon glyphicon-off" aria-hidden="true"></span>', ['site/logout'], ['data' => ['method' => 'post', 'toggle' => "tooltip", 'placement' => "top", 'title' => "خروج"]]) ?>
                    </div>
                    <!-- /menu footer buttons -->
                </div>
            </div>

            <!-- top navigation -->
            <div class="top_nav hidden-print">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>

                        <ul class="nav navbar-nav navbar-right">
                            <li class="">
                                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown"
                                   aria-expanded="false">
                                    <img src="/images/site/icons/avatar.webp" alt=""><?=$user->full_name?>

                                    <span class=" fa fa-angle-down"></span>


                                </a>
                                <ul class="dropdown-menu dropdown-usermenu pull-right">
<!--                                    <li><a href="javascript:;"> نمایه</a></li>-->
<!--                                    <li>-->
<!--                                        <a href="javascript:;">-->
<!--                                            <span class="badge bg-red pull-right">50%</span>-->
<!--                                            <span>تنظیمات</span>-->
<!--                                        </a>-->
<!--                                    </li>-->
<!--                                    <li><a href="javascript:;">کمک</a></li>-->
                                    <li><?= Html::a(FA::i(FA::_SIGN_OUT)->pullRight() . 'خروج', ['site/logout'], ['data' => ['method' => 'post']]) ?></li>
                                </ul>
                            </li>
                            <li style=" text-align: center; background: #767676; color: white; padding: 0px 10px; ">
                                <h3><div id="clock"></div></h3>
                                <h5><div id="date"></div></h5>
                            </li>
                            <?php if(\Yii::$app->user->can('اظهار الانتقال لصفحة الوهمية')):?>
                            <li style=" text-align: center;  padding: 0px 10px; ">

                                <?= Html::a(Html::tag('span', '...'), ['order/create-fk'],["class"=>"btn "]) ?>
                            </li>
                            <?php endif;?>
<!--                            notification -->
<!--                            <li role="presentation" class="dropdown">-->
<!--                                <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown"-->
<!--                                   aria-expanded="false">-->
<!--                                    <i class="fa fa-envelope-o"></i>-->
<!--                                    <span class="badge bg-green">6</span>-->
<!--                                </a>-->
<!--                                <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">-->
<!--                                    <li>-->
<!--                                        <a>-->
<!--                                        <span class="image"><img src="http://placehold.it/128x128"-->
<!--                                                                 alt="Profile Image"/></span>-->
<!--                                            <span>-->
<!--                          <span>مرتضی کریمی</span>-->
<!--                          <span class="time">3 دقیقه پیش</span>-->
<!--                        </span>-->
<!--                                            <span class="message">-->
<!--                          فیلمای فستیوال فیلمایی که اجرا شده یا راجع به لحظات مرده ایه که فیلمسازا میسازن. آنها جایی بودند که....-->
<!--                        </span>-->
<!--                                        </a>-->
<!--                                    </li>-->
<!--                                    <li>-->
<!--                                        <a>-->
<!--                                        <span class="image"><img src="http://placehold.it/128x128"-->
<!--                                                                 alt="Profile Image"/></span>-->
<!--                                            <span>-->
<!--                          <span>مرتضی کریمی</span>-->
<!--                          <span class="time">3 دقیقه پیش</span>-->
<!--                        </span>-->
<!--                                            <span class="message">-->
<!--                          فیلمای فستیوال فیلمایی که اجرا شده یا راجع به لحظات مرده ایه که فیلمسازا میسازن. آنها جایی بودند که....-->
<!--                        </span>-->
<!--                                        </a>-->
<!--                                    </li>-->
<!--                                    <li>-->
<!--                                        <a>-->
<!--                                        <span class="image"><img src="http://placehold.it/128x128"-->
<!--                                                                 alt="Profile Image"/></span>-->
<!--                                            <span>-->
<!--                          <span>مرتضی کریمی</span>-->
<!--                          <span class="time">3 دقیقه پیش</span>-->
<!--                        </span>-->
<!--                                            <span class="message">-->
<!--                          فیلمای فستیوال فیلمایی که اجرا شده یا راجع به لحظات مرده ایه که فیلمسازا میسازن. آنها جایی بودند که....-->
<!--                        </span>-->
<!--                                        </a>-->
<!--                                    </li>-->
<!--                                    <li>-->
<!--                                        <a>-->
<!--                                        <span class="image"><img src="http://placehold.it/128x128"-->
<!--                                                                 alt="Profile Image"/></span>-->
<!--                                            <span>-->
<!--                          <span>مرتضی کریمی</span>-->
<!--                          <span class="time">3 دقیقه پیش</span>-->
<!--                        </span>-->
<!--                                            <span class="message">-->
<!--                          فیلمای فستیوال فیلمایی که اجرا شده یا راجع به لحظات مرده ایه که فیلمسازا میسازن. آنها جایی بودند که....-->
<!--                        </span>-->
<!--                                        </a>-->
<!--                                    </li>-->
<!--                                    <li>-->
<!--                                        <div class="text-center">-->
<!--                                            <a>-->
<!--                                                <strong>مشاهده تمام اعلان ها</strong>-->
<!--                                                <i class="fa fa-angle-right"></i>-->
<!--                                            </a>-->
<!--                                        </div>-->
<!--                                    </li>-->
<!--                                </ul>-->
<!--                            </li>-->
                        </ul>

                    </nav>

                </div>
            </div>
            <!-- /top navigation -->
            <!-- /header content -->
            <div class="right_col" role="main">
                <?php if (Yii::$app->session->hasFlash('success')): ?>
                    <div class="alert alert-success alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <h4><i class="icon fa fa-check"></i></h4>  
                        <?= Yii::$app->session->getFlash('success') ?>
                    </div>
                <?php endif; ?>
                <?php if (Yii::$app->session->hasFlash('error')): ?>
                    <div class="alert alert-danger alert-dismissable">  
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <h4><i class="icon fa fa-exclamation"> </i></h4> 
                         <?= Yii::$app->session->getFlash('error') ?>
                    </div>
                <?php endif; ?>
                <?= $content ?>
            </div>
            <!-- footer content -->

            <!-- /footer content-->
        </div>
    </div>
    <div id="custom_notifications" class="custom-notifications dsp_none">
        <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
        </ul>
        <div class="clearfix"></div>
        <div id="notif-group" class="tabbed_notifications"></div>
    </div>
    <div id="lock_screen">
        <table>
            <tr>
                <td>
                    <div class="clock"></div>
                    <span class="unlock">
                    <span class="fa-stack fa-5x">
                      <i class="fa fa-square-o fa-stack-2x fa-inverse"></i>
                      <i id="icon_lock" class="fa fa-lock fa-stack-1x fa-inverse"></i>
                    </span>
                </span>
                </td>
            </tr>
        </table>
    </div>
    <?php $this->endBody(); ?>
    </body>
    0


    <script>
        var nameOfDay = new Array('الاحد','الاثنين','الثلاثاء','الاربعاء','الخميس','الجمعة','السبت');
        var nameOfMonth = new Array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12');
        var data = calcTime('Bahrain','+3');
        function clock()
        {

            var hou = data.getHours();
            var min = data.getMinutes();
            var sec = data.getSeconds();
            if(hou<10){ hou= "0"+hou;}
            if(min<10){ min= "0"+min;}
            if(sec<10){ sec= "0"+sec;}

            document.getElementById('clock').innerHTML = hou+":"+min+":"+sec;
            data.setTime(data.getTime()+1000)
            setTimeout("clock();",1000);

            document.getElementById('date').innerHTML = nameOfDay[data.getDay()] + ",  " +   data.getDate() + "-" + nameOfMonth[data.getMonth()]+ "-" + data.getFullYear();
        }
        function calcTime(city, offset) {
            // create Date object for current location
            var d = new Date();

            // convert to msec
            // subtract local time zone offset
            // get UTC time in msec
            var utc = d.getTime() + (d.getTimezoneOffset() * 60000);

            // create new Date object for different city
            // using supplied offset
            var nd = new Date(utc + (3600000*offset));

            // return time as a string
            return nd;
        }

    </script>

    </html>
<?php $this->endPage(); ?>