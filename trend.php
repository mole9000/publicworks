<?php
header("Content-Type: text/html; charset=utf-8"); //Hank 自編 《PHP專書P14-3》
require_once('Connections/connection.php');
date_default_timezone_set("Etc/GMT-8");
if (!isset($_SESSION)) {session_start();}

$database = "test";
$table = "scraperblogdata";
$table_opt = "analysisresult";

$starttime = '2002-01-01';
$endtime = date('Y-m-d');
if(isset($_GET['starttime'])&&$_GET['starttime']!=""){$starttime = $_GET['starttime'];$_SESSION['ewomyjs_trend_starttime'] = $starttime;}
if(isset($_GET['endtime'])&&$_GET['endtime']!=""){$endtime = $_GET['endtime'];$_SESSION['ewomyjs_trend_endtime'] = $endtime;}
if(isset($_SESSION['ewomyjs_trend_starttime'])&&$_SESSION['ewomyjs_trend_starttime']!=""){$starttime = $_SESSION['ewomyjs_trend_starttime'];}
if(isset($_SESSION['ewomyjs_trend_endtime'])&&$_SESSION['ewomyjs_trend_endtime']!=""){$endtime = $_SESSION['ewomyjs_trend_endtime'];}

$sqlstr = " WHERE daytime >= '".$starttime." 00:00:00' AND daytime <= '".$endtime." 23:59:59' ";

$searchinterval = "year";
if(isset($_GET['searchinterval'])&&$_GET['searchinterval']!=""){$searchinterval = $_GET['searchinterval'];$_SESSION['ewomyjs_trend_searchinterval'] = $searchinterval;}
if(isset($_SESSION['ewomyjs_trend_searchinterval'])&&$_SESSION['ewomyjs_trend_searchinterval']!=""){$searchinterval = $_SESSION['ewomyjs_trend_searchinterval'];}
switch($searchinterval){
	case "week":
		$search_interval = "7 day";
		break;  
	case "month":
		$search_interval = "1 month";
		break;
	default:
		$search_interval = "1 year";
}


$daytime_start = $starttime.' 00:00:00';
$daytime_end = $endtime.' 23:59:59';;
$daytime_interval = array();
$daytime_Cal = $daytime_start;
while(strtotime($daytime_Cal)<=strtotime($daytime_end)){ //時間戳記比較
	$daytime_Cal_start = $daytime_Cal; //Y-m-d H:i:s
	$daytime_Cal = strtotime($search_interval,strtotime($daytime_Cal)); //時間戳記
	$daytime_key = date("Y-m-d", $daytime_Cal); //Y-m-d
	$daytime_Cal = date("Y-m-d H:i:s", $daytime_Cal);  //Y-m-d H:i:s
	if(strtotime($daytime_Cal)>strtotime($daytime_end)){ //最後一筆
		$daytime_interval[date("Y-m-d", strtotime($daytime_end))] = array($daytime_Cal_start, $daytime_end);
	}else{
		$daytime_interval[$daytime_key] = array($daytime_Cal_start, $daytime_Cal);
	}
}
//echo "<pre>".json_encode($daytime_interval, JSON_PRETTY_PRINT)."</pre>";

$js_chart_trend = array();
foreach ($daytime_interval as $key => $value) {
	$temp = array();
	$temp["interval"] = $value;
	$temp["class"] = $key;
	$temp["count"] = 0;
	$temp["positive"] = 0;
	$temp["negative"] = 0;
	array_push($js_chart_trend,$temp);
}

//文章聲量
$sql = "SELECT date_format(daytime, '%Y-%m-%d') as thedate, count(*)  FROM (SELECT * FROM $database.$table WHERE `view` != '' AND titlehavetarget = 'yes' UNION SELECT * FROM $database.$table WHERE `view` = '' AND titlehavetarget = 'no') as result ".$sqlstr." GROUP BY date_format(daytime, '%Y-%m-%d') ORDER BY thedate DESC";
$result = mysqli_query($link,$sql);
while($Row = mysqli_fetch_assoc($result)){
	foreach ($js_chart_trend as $key => $value) {
		if(strtotime($Row['thedate'])>=strtotime($js_chart_trend[$key]["interval"][0])&&strtotime($Row['thedate'])<strtotime($js_chart_trend[$key]["interval"][1])){
			$js_chart_trend[$key]["count"] += $Row['count(*)'];
		}
	}
}
//正負面評價量
$sum_pos = 0;
$sum_neg = 0;
$sql = "SELECT thedate, count(*) as total, SUM(CASE WHEN opt = '正面' THEN 1 ELSE 0 END) as '正面', SUM(CASE WHEN opt = '負面' THEN 1 ELSE 0 END) as '負面' FROM (SELECT oriblogsn, opt, sen, word, type, title, domain, url, authorid, daytime, date_format(daytime, '%Y-%m-%d') as thedate FROM $database.$table_opt as A LEFT JOIN $database.$table as B ON A.oriblogsn = B.sn) as result ".$sqlstr." GROUP BY thedate ORDER BY daytime DESC";
$result = mysqli_query($link,$sql);
while($Row = mysqli_fetch_assoc($result)){
	foreach ($js_chart_trend as $key => $value) {
		if(strtotime($Row['thedate'])>=strtotime($js_chart_trend[$key]["interval"][0])&&strtotime($Row['thedate'])<strtotime($js_chart_trend[$key]["interval"][1])){
			$js_chart_trend[$key]["positive"] += $Row['正面'];
			$js_chart_trend[$key]["negative"] += $Row['負面'];
			$sum_pos += $Row['正面'];
			$sum_neg += $Row['負面'];
		}
	}
}

$js_chart_pie = array();
$temp = array();
$temp['class'] = "正評價";
$temp['count'] = $sum_pos;
array_push($js_chart_pie,$temp);
$temp = array();
$temp['class'] = "負評價";
$temp['count'] = $sum_neg;
array_push($js_chart_pie,$temp);

/*
$js_chart_trend = array();
$js_chart_trend[0] = array();
$js_chart_trend[0]['class'] = 'w1';
$js_chart_trend[0]['count'] = 11;
$js_chart_trend[0]['positive'] = 8;
$js_chart_trend[0]['negative'] = 3;
$js_chart_trend[1] = array();
$js_chart_trend[1]['class'] = 'w2';
$js_chart_trend[1]['count'] = 2;
$js_chart_trend[1]['positive'] = 6;
$js_chart_trend[1]['negative'] = 5;
$js_chart_trend[2] = array();
$js_chart_trend[2]['class'] = 'w3';
$js_chart_trend[2]['count'] = 1;
$js_chart_trend[2]['positive'] = 12;
$js_chart_trend[2]['negative'] = 2;
*/

?>
<!DOCTYPE html>

<html lang="en" class="material-style layout-fixed">

<head>
    <title>裕珍馨 - 口碑評價管理系統</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="Empire Bootstrap admin template made using Bootstrap 4, it has tons of ready made feature, UI components, pages which completely fulfills any dashboard needs." />
    <meta name="keywords" content="Empire, bootstrap admin template, bootstrap admin panel, bootstrap 4 admin template, admin template">
    <meta name="author" content="Srthemesvilla" />
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">

    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <!-- Icon fonts -->
    <link rel="stylesheet" href="assets/fonts/fontawesome.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.css">
    <link rel="stylesheet" href="assets/fonts/linearicons.css">
    <link rel="stylesheet" href="assets/fonts/open-iconic.css">
    <link rel="stylesheet" href="assets/fonts/pe-icon-7-stroke.css">
    <link rel="stylesheet" href="assets/fonts/feather.css">
    <!-- Core stylesheets -->
    <link rel="stylesheet" href="assets/css/bootstrap-material.css">
    <link rel="stylesheet" href="assets/css/shreerang-material.css">
    <link rel="stylesheet" href="assets/css/uikit.css">
    <!-- Libs -->
    <link rel="stylesheet" href="assets/libs/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="assets/libs/flot/flot.css">

	<link href="layui-v2.5.7/layui/css/layui.css" rel="stylesheet" type="text/css"/>
    <script src="js/jquery-3.1.1.js"></script>
	<script type="text/javascript" src="layer-v3.1.0/layer/layer.js"></script>
	<script type="text/javascript" src="layui-v2.5.7/layui/layui.js"></script>
	<script>
	layui.use(['element','form'], function(){
	  var element = layui.element, form = layui.form;
	  
	});
	
	</script>
	<style>
	.layui-form-select{
		margin-right:5px;
	}
	.layui-form-select .layui-input{
		border-top: none;
		border-left: none;
		border-right: none;
		background: transparent;
		border-bottom: 1.5px #e2e6e7 solid;
	}
	.layui-layer-btn .layui-layer-btn0{
		color: #fff !important;
	}
	.layui-layer-btn a{
		height: 31px;
	}
	.sidenav.bg-white .sidenav-item.active > .sidenav-link:not(.sidenav-toggle){
		background-color: #e5e5e5;
	}
	.container-p-y:not([class^="pt-"]):not([class*=" pt-"]){
		padding-top: 0.8rem !important;
	}
	.sidenav-vertical .sidenav-item .sidenav-link{
		padding: 0.5rem 1.7rem;
	}
	.sidenav-item .sidenav-link:hover{
		background-color: #e5e5e5;
	}
	.sidenav-menu .sidenav-link{
		padding-left: 3.7rem !important;
	}
	.h6_{
		line-height: 40px;
	}
	h6.urllink a{
		font-weight:bold;
		font-size: 15px;
		color: #000;
	}
	h6.urllink a:hover{
		font-weight:bold;
		font-size: 17px;
		color: #1668ff;
		cursor:pointer;
	}
	</style>
</head>

<body>
    <!-- [ Preloader ] Start -->
    <div class="page-loader">
        <div class="bg-primary"></div>
    </div>
    <!-- [ Preloader ] End -->

    <!-- [ Layout wrapper ] Start -->
    <div class="layout-wrapper layout-2">
        <div class="layout-inner">
            <!-- [ Layout sidenav ] Start -->
            <div id="layout-sidenav" class="layout-sidenav sidenav sidenav-vertical bg-white logo-dark">
                <!-- Brand demo (see assets/css/demo/demo.css) -->
                <div class="app-brand demo" style="height: 54px;">
                    <span class="app-brand-logo demo">
                        <img src="images/logo_white.png" alt="Brand Logo" class="img-fluid" style="width: 155px;padding-left: 5px;">
                    </span>
                    <a href="javascript:" class="layout-sidenav-toggle sidenav-link text-large ml-auto">
                        <i class="ion ion-md-menu align-middle"></i>
                    </a>
                </div>
                <div class="sidenav-divider mt-0"></div>

                <!-- Links -->
                <ul class="sidenav-inner py-1">

                    <!-- Dashboards -->
                    <li class="sidenav-item">
                        <a href="index.html" class="sidenav-link">
                            <i class="sidenav-icon feather icon-home"></i>
                            <div>儀錶板</div>
                            <div class="pl-1 ml-auto">
                                <div class="badge badge-danger">首頁</div>
                            </div>
                        </a>
                    </li>

                    <!-- Layouts -->
                    <li class="sidenav-divider mb-1"></li>
                    <li class="sidenav-header small font-weight-semibold">功能列表</li>
                    <li class="sidenav-item">
                        <a href="article.php" class="sidenav-link">
                            <i class="sidenav-icon feather icon-align-left"></i>
                            <div>網路文章</div>
                        </a>
                    </li>
                    <li class="sidenav-item">
                        <a href="optlist.php" class="sidenav-link">
                            <i class="sidenav-icon feather icon-battery-charging"></i>
                            <div>極性評價</div>
                        </a>
                    </li>
                    <!-- UI elements -->
                    <li class="sidenav-item active open">
                        <a href="javascript:" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon feather icon-trending-up"></i>
                            <div>聲量趨勢</div>
                        </a>
                        <ul class="sidenav-menu">
                            <li class="sidenav-item active">
                                <a href="trend.php" class="sidenav-link">
                                    <div>單一趨勢</div>
                                </a>
                            </li>
                            <li class="sidenav-item">
                                <a href="trend_compare.php" class="sidenav-link">
                                    <div>比較趨勢</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidenav-item">
                        <a href="javascript:" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon feather icon-crosshair"></i>
                            <div>構面佔比</div>
                        </a>
                        <ul class="sidenav-menu">
                            <li class="sidenav-item">
                                <a href="type.php" class="sidenav-link">
                                    <div>單一佔比</div>
                                </a>
                            </li>
                            <li class="sidenav-item">
                                <a href="type.php" class="sidenav-link">
                                    <div>比較佔比</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidenav-item">
                        <a href="javascript:" class="sidenav-link sidenav-toggle">
                            <i class="sidenav-icon feather icon-bar-chart"></i>
                            <div>網站分佈</div>
                        </a>
                        <ul class="sidenav-menu">
                            <li class="sidenav-item">
                                <a href="website.php" class="sidenav-link">
                                    <div>單一分佈</div>
                                </a>
                            </li>
                            <li class="sidenav-item">
                                <a href="website_compare.php" class="sidenav-link">
                                    <div>比較分佈</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidenav-item">
                        <a href="opinionleadership.php" class="sidenav-link">
                            <i class="sidenav-icon feather icon-award"></i>
                            <div>意見領袖</div>
                        </a>
                    </li>
                    <li class="sidenav-item">
                        <a href="followarticle.php" class="sidenav-link">
                            <i class="sidenav-icon feather icon-compass"></i>
                            <div>發文者追蹤</div>
                        </a>
                    </li>
                    <li class="sidenav-item">
                        <a href="hotspot.php" class="sidenav-link">
                            <i class="sidenav-icon feather icon-thermometer"></i>
                            <div>熱詞探索</div>
                        </a>
                    </li>
					<!--
                    <li class="sidenav-item">
                        <a href="monitor.php" class="sidenav-link">
                            <i class="sidenav-icon feather icon-globe"></i>
                            <div>事件監測</div>
                        </a>
                    </li>
					 -->

                    <!-- Forms & Tables -->
                    <li class="sidenav-divider mb-1"></li>
                    <li class="sidenav-header small font-weight-semibold">簡介</li>
                    <li class="sidenav-item">
                        <a href="socialmedia.php" class="sidenav-link">
                            <i class="sidenav-icon feather icon-flag"></i>
                            <div>網路社群</div>
                        </a>
                    </li>
                    <li class="sidenav-item">
                        <a href="qa.php" class="sidenav-link">
                            <i class="sidenav-icon feather icon-anchor"></i>
                            <div>Q&A</div>
                        </a>
                    </li>
                    <li class="sidenav-item">
                        <a href="contact.php" class="sidenav-link">
                            <i class="sidenav-icon feather icon-message-circle"></i>
                            <div>聯絡我們</div>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- [ Layout sidenav ] End -->
            <!-- [ Layout container ] Start -->
            <div class="layout-container">
                <!-- [ Layout navbar ( Header ) ] Start -->
                <nav class="layout-navbar navbar navbar-expand-lg align-items-lg-center bg-dark container-p-x" id="layout-navbar" style="padding: initial;padding-left: 1rem !important;">

                    <!-- Brand demo (see assets/css/demo/demo.css) -->
                    <a href="index.html" class="navbar-brand app-brand demo d-lg-none py-0 mr-4">
                        <span class="app-brand-logo demo">
                            <img src="assets/img/logo-dark.png" alt="Brand Logo" class="img-fluid">
                        </span>
                        <span class="app-brand-text demo font-weight-normal ml-2">Empire</span>
                    </a>

                    <!-- Sidenav toggle (see assets/css/demo/demo.css) -->
                    <div class="layout-sidenav-toggle navbar-nav d-lg-none align-items-lg-center mr-auto">
                        <a class="nav-item nav-link px-0 mr-lg-4" href="javascript:">
                            <i class="ion ion-md-menu text-large align-middle"></i>
                        </a>
                    </div>

                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#layout-navbar-collapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="navbar-collapse collapse" id="layout-navbar-collapse">
                        <!-- Divider -->
                        <hr class="d-lg-none w-100 my-2">

                        <div class="navbar-nav align-items-lg-center">
                            <label class="nav-item navbar-text navbar-search-box p-0 active" style="width: 100%;">
                                <span class="navbar-search-input pl-2">
									<a href="index.html" class="app-brand-text demo sidenav-text font-weight-normal ml-2" style="font-size:18px;">口碑評價管理系統 <span></span></a>
                                </span>
                            </label>
                        </div>

                        <div class="navbar-nav align-items-lg-center ml-auto">
                            <!-- Divider -->
                            <div class="nav-item d-none d-lg-block text-big font-weight-light line-height-1 opacity-25 mr-3 ml-1">|</div>
                            <div class="demo-navbar-user nav-item dropdown">
                                <a class="nav-link dropdown-toggle hank-nav-link" href="#" data-toggle="dropdown">
                                    <span class="d-inline-flex flex-lg-row-reverse align-items-center align-middle">
                                        <span class="px-1 mr-lg-2 ml-2 ml-lg-0">管理者</span>
                                        <img src="assets/img/avatars/1.png" alt class="d-block ui-w-30 rounded-circle">
                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="javascript:" class="dropdown-item">
                                        <i class="feather icon-settings text-muted"></i> &nbsp; 後端系統</a>
                                    <div class="dropdown-divider"></div>
                                    <a href="javascript:" class="dropdown-item">
                                        <i class="feather icon-power text-danger"></i> &nbsp; 登出</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
                <!-- [ Layout navbar ( Header ) ] End -->

                <!-- [ Layout content ] Start -->
                <div class="layout-content">

                    <!-- [ content ] Start -->
                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0">單一趨勢</h4>
                        <div class="text-muted small mt-0 mb-4 d-block breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item active">聲量趨勢</li>
                                <li class="breadcrumb-item active">單一趨勢</li>
                            </ol>
                        </div>
                        <div class="row">
                            <!-- 1st row Start -->
                            <div class="col-md-12">
								<form method="get" class="layui-form" style="margin-bottom: 25px;">
									<div class="form-inline" style="font-size: 16px !important;">
										<label class="form-check mr-sm-2 mb-2 mb-sm-0">
											<span class="form-check-label" style="font-weight:bold;">期間選擇: </span>
										</label>
										<label class="sr-only">Starttime</label>
										<input name="starttime" type="date" class="form-control mr-sm-2 mb-2 mb-sm-0" style="font-size: 16px !important;" placeholder="開始日期" value="<?=$starttime?>">
										<label class="form-check mr-sm-2 mb-2 mb-sm-0">
											<span class="form-check-label">~</span>
										</label>
										<label class="sr-only">Endtime</label>
										<input name="endtime" type="date" class="form-control mr-sm-2 mb-2 mb-sm-0" style="font-size: 16px !important;" placeholder="結束日期" value="<?=$endtime?>">
										<span>　</span>
										<span>　|　</span>
										<label class="form-label mr-sm-2 mb-2 mb-sm-0" style="font-size: 16px !important;font-weight:bold;">科室</label>
										<select class="custom-select mr-sm-2 mb-sm-0" name="searchinterval" id="searchinterval" style="display:none;" lay-filter="searchinterval">
											<option value="year">所有</option>
											<option value="month">第一工務大隊</option>
											<option value="week">第二工務大隊</option>
											<option value="week">第二工務大隊</option>
											<option value="week">工程企劃科</option>
											<option value="week">使用管理科</option>
											<option value="week">XXX</option>
											<option value="week">XXX</option>
										</select>
										<button type="submit" class="btn btn-primary">送出</button>
									</div>
                                </form>
							</div>
						</div>
<style>
	.page a{
		color: #000;
		border: 1px solid #ccc;
		padding: 3px 9px;
		background: #fff;
	}
	.page a:hover{
		background: #f0f4f5;
	}
	.page b{
		color: #000;
		padding: 3px 9px;
	}
	.p_opt1{
		border-left: 4px solid #649d7f;
		padding: 3px 5px;
		background: #cefde4;
		color: #215a3c !important;
		font-size: 16px;
		font-weight: bold;
		margin-bottom: 8px !important;
	}
	.p_opt2{
		border-left: 4px solid #d14f4f;
		padding: 3px 5px;
		background: #ffecec;
		color: #812a2a !important;
		font-size: 16px;
		font-weight: bold;
		margin-bottom: 8px !important;
	}
</style>
                        <div class="row">
                            <div class="col-md-9">
                                <div class="card mb-4">
									<div class="card-header" style="line-height: 0.54;padding: 1px 22px;">
										<span style="display: inline-block;padding: 15px 0px;">聲量趨勢</span>
									</div>
                                    <div class="card-body" style="padding: 0.5rem;">
										<div id="trend" style="height: 250px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card mb-4">
									<div class="card-header" style="line-height: 0.54;padding: 1px 22px;">
										<span style="display: inline-block;padding: 15px 0px;">極性佔比</span>
										<button type="button" class="btn btn-sm btn-secondary" style="float: right;margin-top: 6px;" id="showPieAllInterval">總體區間 > </button>
									</div>
                                    <div class="card-body" style="padding: 0.5rem;">
										<div id="pie" style="height: 250px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
					    <div class="row" id="draw_1">
                            <div class="col-xl-12">
                                <div class="card mb-4">
                                    <div class="card-header with-elements pb-0" style="padding-top: 5px;padding-bottom: 3px !important;">
                                        <h6 class="card-header-title mb-0" id="interval_label"><strong>區間資訊</strong></h6>
                                        <div class="card-header-elements ml-auto p-0">
                                            <ul class="nav nav-tabs">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="TabArea_tableScore" data-toggle="tab" href="#index1" >文章聲量<span id="lable_count_vol"></span></a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="TabArea_tableUploadExam" data-toggle="tab" href="#index2">正面評價量<span id="lable_count_pos"></span></a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="TabArea_tableUploadHomework" data-toggle="tab" href="#index3">負面評價量<span id="lable_count_neg"></span></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="nav-tabs-top">
                                        <div class="tab-content">
                                            <div class="tab-pane fade show active" id="index1">
                                                <div class="tabArea">
													<div id="area_vol">
														<div style="text-align: center;padding: 43px;color: #838383;">點擊上方區間以顯示區間內容</div>
													</div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="index2">
                                                <div class="tabArea">
													<div id="area_pos">
														<div style="text-align: center;padding: 43px;color: #838383;">點擊上方區間以顯示區間內容</div>
													</div>
													<div id="area_pos_typeNone" style="text-align: center;padding: 43px;color: #838383;display:none;">尚無資料</div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="index3">
                                                <div class="tabArea">
													<div id="area_neg">
														<div style="text-align: center;padding: 43px;color: #838383;">點擊上方區間以顯示區間內容</div>
													</div>
													<div id="area_neg_typeNone" style="text-align: center;padding: 43px;color: #838383;display:none;">尚無資料</div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="index6">
                                                <div class="tabArea">
													<div class="trendArea" id="trend6" style="width: 100%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
						</div>
					</div>
                    <!-- [ content ] End -->

                    <!-- [ Layout footer ] Start -->
                    <nav class="layout-footer footer bg-white">
                        <div class="container-fluid d-flex flex-wrap justify-content-between text-center container-p-x pb-3">
                            <div class="pt-3">
                                <span class="footer-text font-weight-semibold">&copy; <a href="https://www.yjs.com.tw/" class="footer-link" target="_blank">裕珍馨 All Rights Reserved.</a></span>
                            </div>
                            <div>
                                <a href="javascript:" class="footer-link pt-3">About 關於我們</a>
                                <a href="javascript:" class="footer-link pt-3 ml-4">Support 支援</a>
                                <a href="javascript:" class="footer-link pt-3 ml-4">Contact 聯絡我們</a>
                            </div>
                        </div>
                    </nav>
                    <!-- [ Layout footer ] End -->
                </div>
                <!-- [ Layout content ] Start -->
            </div>
            <!-- [ Layout container ] End -->
        </div>
        <!-- Overlay -->
        <div class="layout-overlay layout-sidenav-toggle"></div>
    </div>
	<span id='tasks-inner'></span>
	<span id='tab-table-1'></span>
	<span id='tab-table-2'></span>
    <!-- [ Layout wrapper] End -->

    <!-- Core scripts -->
    <script src="assets/js/pace.js"></script>
    <script src="assets/libs/popper/popper.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/sidenav.js"></script>
    <script src="assets/js/layout-helpers.js"></script>
    <script src="assets/js/material-ripple.js"></script>

    <!-- Libs -->
    <script src="assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/libs/eve/eve.js"></script>
    <script src="assets/libs/flot/flot.js"></script>
    <script src="assets/libs/flot/curvedLines.js"></script>
	
	<script src="cdn.amcharts/core.js"></script>
	<script src="cdn.amcharts/charts.js"></script>
	<script src="cdn.amcharts/material.js"></script>
	<script src="cdn.amcharts/de_DE.js"></script>
	<script src="cdn.amcharts/germanyLow.js"></script>
	<script src="cdn.amcharts/notosans-sc.js"></script>
	<script src="cdn.amcharts/animated.js"></script>

    <!-- Demo -->
    <script src="assets/js/demo.js"></script><script src="assets/js/analytics.js"></script>
	
	<script>

//=========== 置頂
$('body').append('' +
    '<div class="fixed-button active">' + '<a href="javascript:$(\'html,body\').animate({\'scrollTop\':0});" class="btn btn-md btn-primary">' + '<i class="fa fa-angle-double-up" aria-hidden="true"></i> &nbsp;置頂' +
    '</a> ' + '</div>' + '');
	var $window = $(window);
	var nav = $('.fixed-button');
	$window.scroll(function() {
    if ($window.scrollTop() >= 200) {
        nav.addClass('active');
    } else {
        nav.removeClass('active');
    }
});
	</script>
	
	<script>
	
	function myFunction(ev) {
	  console.log("clicked on ", ev.target.dataItem.categories.categoryX);
	}
	function showlist_pos(type, thisObj, count){
		if(type!='所有'){
			$("#area_typebtn_pos button.btn-danger").attr("class","btn btn-sm btn-outline-danger");
			$("#area_typebtn_pos button.btn-primary").attr("class","btn btn-sm btn-outline-primary");
			thisObj.attr("class","btn btn-sm btn-primary");
			$("#area_pos tr").hide();
			$("#area_pos tr."+type).show();
		}else{
			$("#area_typebtn_pos button.btn-outline-danger").attr("class","btn btn-sm btn-danger");
			$("#area_typebtn_pos button.btn-primary").attr("class","btn btn-sm btn-outline-primary");
			$("#area_pos tr").show();
		}
		if(count==0){
			$("#area_pos_typeNone").show();
		}else{
			$("#area_pos_typeNone").hide();
		}
	}
	function showlist_neg(type, thisObj, count){
		if(type!='所有'){
			$("#area_typebtn_neg button.btn-danger").attr("class","btn btn-sm btn-outline-danger");
			$("#area_typebtn_neg button.btn-primary").attr("class","btn btn-sm btn-outline-primary");
			thisObj.attr("class","btn btn-sm btn-primary");
			$("#area_neg tr").hide();
			$("#area_neg tr."+type).show();
		}else{
			$("#area_typebtn_neg button.btn-outline-danger").attr("class","btn btn-sm btn-danger");
			$("#area_typebtn_neg button.btn-primary").attr("class","btn btn-sm btn-outline-primary");
			$("#area_neg tr").show();
		}
		if(count==0){
			$("#area_neg_typeNone").show();
		}else{
			$("#area_neg_typeNone").hide();
		}
	}
	function draw_pie(js_chart_pie,datestart,dateend){
		
		js_chart_pie[0]['color'] = am4core.color("#60bd50");
		js_chart_pie[1]['color'] = am4core.color("#e12828");
	
		if(js_chart_pie.length>0){

		am4core.useTheme(am4themes_animated);
		// Themes end

		
		var chart = am4core.create("pie", am4charts.PieChart);
		chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

		chart.data = js_chart_pie;
		
		chart.radius = am4core.percent(70);
		chart.innerRadius = am4core.percent(40);
		chart.startAngle = 180;
		chart.endAngle = 360;  

		var series = chart.series.push(new am4charts.PieSeries());
		series.dataFields.value = "count";
		series.dataFields.category = "class";
		series.labels.template.maxWidth = 60;
		series.labels.template.wrap = true;
		series.slices.template.propertyFields.fill = "color";

		series.slices.template.cornerRadius = 10;
		series.slices.template.innerCornerRadius = 7;
		series.slices.template.draggable = true;
		series.slices.template.inert = true;
		series.slices.template.tooltipText = "[bold][font-size:14px]{category}: {value}筆";
		series.alignLabels = false;

		series.hiddenState.properties.startAngle = 90;
		series.hiddenState.properties.endAngle = 90;

		var label = chart.seriesContainer.createChild(am4core.Label);
		label.textAlign = "middle";
		label.horizontalCenter = "middle";
		label.verticalCenter = "middle";
		label.adapter.add("text", function(text, target){
			return "[font-size:18px]正 / 負[/]\n[bold font-size:24px]"+js_chart_pie[0]['count']+' / '+js_chart_pie[1]['count']+'\n[font-size:14px] '+datestart+' ~ '+dateend+' ]';
		})

		//Export
		chart.exporting.menu = new am4core.ExportMenu();
		chart.exporting.menu.align = "right";
		chart.exporting.menu.verticalAlign = "top";
		chart.exporting.menu.items = [{
			"label": "...",
			"menu": [
			  {
				"label": "圖片",
				"menu": [
				  { "type": "png", "label": "PNG" },
				  { "type": "jpg", "label": "JPG" },
				  { "type": "svg", "label": "SVG" },
				  { "type": "pdf", "label": "PDF" }
				]
			  }, {
				"label": "資料",
				"menu": [
				  { "type": "json", "label": "JSON" },
				  { "type": "xlsx", "label": "XLSX" },
				  { "type": "html", "label": "HTML" },
				]
			  }, {
				"label": "列印", "type": "print"
			  }
			]
		  }
		];
		
		chart.logo.height = -15000;
		
		}else{
			
		}
	}
	$(document).ready(function() {
		
		daytime_start = '<?=$daytime_start?>'
		daytime_end = '<?=$daytime_end?>'
		var js_chart_pie = new Array();
		js_chart_pie = <?php echo json_encode($js_chart_pie);?>;
		draw_pie(js_chart_pie,daytime_start.split(" ")[0],daytime_end.split(" ")[0]);
		
		$("#showPieAllInterval").hide();
		$("#showPieAllInterval").click(function() {
			draw_pie(js_chart_pie,daytime_start.split(" ")[0],daytime_end.split(" ")[0]);
			$("#showPieAllInterval").hide();
		});
		
		//------ 聲量 ----------//
		
		var js_data = new Array();
		js_data = <?php echo json_encode($js_chart_trend);?>;
		
		if(js_data.length>0){
			
		am4core.useTheme(am4themes_animated);
		// Themes end
		
		// Create chart instance
		var chart = am4core.create("trend", am4charts.XYChart);
		chart.cursorOverStyle = am4core.MouseCursorStyle.pointer;
		chart.events.on("hit", function(){
			if(series.tooltipDataItem.categories.categoryX){
				$("#showPieAllInterval").show();
				getCategoryX = series.tooltipDataItem.categories.categoryX
				for (var index in js_data) {
					if(js_data[index]['class']==getCategoryX){
						timestart = js_data[index]['interval'][0].split(' ')[0];
						timeend = js_data[index]['interval'][1].split(' ')[0];
						$('#interval_label').html('<strong>區間: '+timestart+' ~ '+timeend+'</strong>');
						$('#lable_count_vol').html('('+js_data[index]['count']+')');
						$('#lable_count_pos').html('('+js_data[index]['positive']+')');
						$('#lable_count_neg').html('('+js_data[index]['negative']+')');
						
						//----圓餅圖
						js_pie = new Array();
						js_pie[0] = new Array();
						js_pie[0]['class'] = "正評價";
						js_pie[0]['count'] = js_data[index]["positive"];
						js_pie[1] = new Array();
						js_pie[1]['class'] = "負評價";
						js_pie[1]['count'] = js_data[index]["negative"];
						draw_pie(js_pie,timestart,timeend);
						console.log(js_pie)
						
						$.ajax({ //------ 文章聲量
						  type: 'POST',
						  url: 'function/report.php',
						  data: {action:'trend_vol', database:'<?=$database?>', timestart:timestart, timeend:timeend},
						  error: function (xhr) {
							  layer.open({title:'訊息', content:'處理中發生錯誤，請重新嘗試或連繫開發人員[Code:001x_ajax]',icon: 2,btn: '知道了'});
							  console.log('[Code:001x_ajax]');
						  },
						  success: function (data) {
							result = JSON.parse(data);
							if(Array.isArray(result)){
								console.log(result)
								if(result.length>0){
									strHTML = '';
									strHTML += '';
									strHTML += '<table class="table table-hover card-table">';
									strHTML += '	<tbody>';
									for(var key in result){
									strHTML += '		<tr>';
									strHTML += '			<td>';
									strHTML += '				<div class="d-inline-block align-middle">';
									strHTML += '				<h6 class="mb-1 urllink"><a href="'+result[key]['url']+'" target="_blank">'+result[key]['title_short']+' <i class="fa fa-share" aria-hidden="true"></i></a></h6>';
									strHTML += '				<p class="text-muted mb-0">'+result[key]['text_short']+'</p>';
									strHTML += '				</div>';
									strHTML += '			</td>';
									strHTML += '			<td style="line-height: 20px;">';
									strHTML += '				<span>'+result[key]['authorid']+'</span><br>';
									strHTML += '				<span>'+result[key]['daytime']+'</span>';
									strHTML += '			</td>';
									strHTML += '			<td><h6 class="mb-0 h6_"><i class="fa fa-eye" aria-hidden="true" style="color: #6a6a6a;"></i> &nbsp;'+result[key]['view']+'</h6></td>';
									strHTML += '			<td><h6 class="mb-0 h6_"><i class="fa fa-comment" aria-hidden="true" style="color: #6a6a6a;"></i> &nbsp;'+result[key]['reply']+'</h6></td>';
									strHTML += '			<td><h6 class="mb-0 h6_"><i class="fa fa-flag" aria-hidden="true" style="color: #6a6a6a;"></i> &nbsp;'+result[key]['domain']+'</h6></td>';
									strHTML += '		</tr>';
									}
									strHTML += '	</tbody>';
									strHTML += '</table>';
								}else{
									strHTML = '<div style="text-align: center;padding: 43px;color: #838383;">該區間尚無資料</div>';
								}
								$('div#area_vol').html(strHTML);
							}else{
								layer.open({title:'訊息', content:'處理中發生錯誤，請重新嘗試或連繫開發人員[Code:001x]',icon: 2,btn: '知道了'});
							}
						  },
						  complete: function(){
						  },
						  async:true
						});
						
						$.ajax({ //------ 評價
						  type: 'POST',
						  url: 'function/report.php',
						  data: {action:'trend_opt', database:'<?=$database?>', timestart:timestart, timeend:timeend},
						  error: function (xhr) {
							  layer.open({title:'訊息', content:'處理中發生錯誤，請重新嘗試或連繫開發人員[Code:002x_ajax]',icon: 2,btn: '知道了'});
							  console.log('[Code:002x_ajax]');
						  },
						  success: function (data) {
							result = JSON.parse(data);
							if(Array.isArray(result)){
								console.log(result);
								var typeClass = {'總體':'type1', '品質':'type2', '價格':'type3', '服務':'type4', '環境':'type5'};
								var typeCount_pos = {'總體':0, '品質':0, '價格':0, '服務':0, '環境': 0};
								var typeCount_neg = {'總體':0, '品質':0, '價格':0, '服務':0, '環境': 0};
								countPos = 0;
								countNeg = 0;
								
								strHTML = '';
								strHTML += '';
								strHTML += '<table class="table table-hover card-table">';
								strHTML += '	<tbody>';
								for(var key in result){
									if(result[key]['opt']=="正面"){
										strHTML += '		<tr class="'+typeClass[result[key]['type']]+'">';
										strHTML += '			<td>';
										strHTML += '				<div class="d-inline-block align-middle">';
										strHTML += '				<p class="text-muted mb-0 p_opt1">'+result[key]['sen_short']+'</p>';
										strHTML += '				<p class="text-muted mb-0"><a href="'+result[key]['url']+'" target="_blank">'+result[key]['title_short']+' <i class="fa fa-share" aria-hidden="true"></i></a></p>';
										strHTML += '				</div>';
										strHTML += '			</td>';
										strHTML += '			<td style="line-height: 20px;">';
										strHTML += '				<span>'+result[key]['authorid']+'</span><br>';
										strHTML += '				<span>'+result[key]['daytime']+'</span>';
										strHTML += '			</td>';
										strHTML += '			<td><h6 class="mb-0 h6_"><i class="fa fa-eye" aria-hidden="true" style="color: #6a6a6a;"></i> &nbsp;'+result[key]['type']+'</h6></td>';
										strHTML += '			<td><h6 class="mb-0 h6_"><i class="fa fa-flag" aria-hidden="true" style="color: #6a6a6a;"></i> &nbsp;'+result[key]['domain']+'</h6></td>';
										strHTML += '		</tr>';
										countPos += 1;
										typeCount_pos[result[key]['type']] += 1;
									}
								}
								strHTML += '	</tbody>';
								strHTML += '</table>';				
								
								strHTML_ = '';
								strHTML_ += '<div id="area_typebtn_pos" style="text-align: center;border-bottom: 1px solid #e1e2e2;padding: 10px;">';
								strHTML_ += '	<button type="button" class="btn btn-sm btn-danger" onclick="showlist_pos(\'所有\',$(this))">顯示所有</button>';
								strHTML_ += '	<button type="button" class="btn btn-sm btn-outline-primary" onclick="showlist_pos(\'type1\',$(this),'+typeCount_pos['總體']+')">總體('+typeCount_pos['總體']+')</button>';
								strHTML_ += '	<button type="button" class="btn btn-sm btn-outline-primary" onclick="showlist_pos(\'type2\',$(this),'+typeCount_pos['品質']+')">品質('+typeCount_pos['品質']+')</button>';
								strHTML_ += '	<button type="button" class="btn btn-sm btn-outline-primary" onclick="showlist_pos(\'type3\',$(this),'+typeCount_pos['價格']+')">價格('+typeCount_pos['價格']+')</button>';
								strHTML_ += '	<button type="button" class="btn btn-sm btn-outline-primary" onclick="showlist_pos(\'type4\',$(this),'+typeCount_pos['服務']+')">服務('+typeCount_pos['服務']+')</button>';
								strHTML_ += '	<button type="button" class="btn btn-sm btn-outline-primary" onclick="showlist_pos(\'type5\',$(this),'+typeCount_pos['環境']+')">環境('+typeCount_pos['環境']+')</button>';
								strHTML_ += '</div>';
								strHTML = strHTML_ + strHTML;
								if(countPos==0){
									strHTML = '<div style="text-align: center;padding: 43px;color: #838383;">該區間尚無資料</div>';
								}
								$('div#area_pos').html(strHTML);
								
								
								strHTML = '';
								strHTML += '';
								strHTML += '<table class="table table-hover card-table">';
								strHTML += '	<tbody>';
								for(var key in result){
									if(result[key]['opt']=="負面"){
										strHTML += '		<tr class="'+typeClass[result[key]['type']]+'">';
										strHTML += '			<td>';
										strHTML += '				<div class="d-inline-block align-middle">';
										strHTML += '				<p class="text-muted mb-0 p_opt2">'+result[key]['sen_short']+'</p>';
										strHTML += '				<p class="text-muted mb-0"><a href="'+result[key]['url']+'" target="_blank">'+result[key]['title_short']+' <i class="fa fa-share" aria-hidden="true"></i></a></p>';
										strHTML += '				</div>';
										strHTML += '			</td>';
										strHTML += '			<td style="line-height: 20px;">';
										strHTML += '				<span>'+result[key]['authorid']+'</span><br>';
										strHTML += '				<span>'+result[key]['daytime']+'</span>';
										strHTML += '			</td>';
										strHTML += '			<td><h6 class="mb-0 h6_"><i class="fa fa-eye" aria-hidden="true" style="color: #6a6a6a;"></i> &nbsp;'+result[key]['type']+'</h6></td>';
										strHTML += '			<td><h6 class="mb-0 h6_"><i class="fa fa-flag" aria-hidden="true" style="color: #6a6a6a;"></i> &nbsp;'+result[key]['domain']+'</h6></td>';
										strHTML += '		</tr>';
										countNeg += 1;
										typeCount_neg[result[key]['type']] += 1;
									}
								}
								strHTML += '	</tbody>';
								strHTML += '</table>';										
								
								strHTML_ = '';
								strHTML_ += '<div id="area_typebtn_neg" style="text-align: center;border-bottom: 1px solid #e1e2e2;padding: 10px;">';
								strHTML_ += '	<button type="button" class="btn btn-sm btn-danger" onclick="showlist_neg(\'所有\',$(this))">顯示所有</button>';
								strHTML_ += '	<button type="button" class="btn btn-sm btn-outline-primary" onclick="showlist_neg(\'type1\',$(this),'+typeCount_neg['總體']+')">總體('+typeCount_neg['總體']+')</button>';
								strHTML_ += '	<button type="button" class="btn btn-sm btn-outline-primary" onclick="showlist_neg(\'type2\',$(this),'+typeCount_neg['品質']+')">品質('+typeCount_neg['品質']+')</button>';
								strHTML_ += '	<button type="button" class="btn btn-sm btn-outline-primary" onclick="showlist_neg(\'type3\',$(this),'+typeCount_neg['價格']+')">價格('+typeCount_neg['價格']+')</button>';
								strHTML_ += '	<button type="button" class="btn btn-sm btn-outline-primary" onclick="showlist_neg(\'type4\',$(this),'+typeCount_neg['服務']+')">服務('+typeCount_neg['服務']+')</button>';
								strHTML_ += '	<button type="button" class="btn btn-sm btn-outline-primary" onclick="showlist_neg(\'type5\',$(this),'+typeCount_neg['環境']+')">環境('+typeCount_neg['環境']+')</button>';
								strHTML_ += '</div>';
								strHTML = strHTML_ + strHTML;
								if(countNeg==0){
									strHTML = '<div style="text-align: center;padding: 43px;color: #838383;">該區間尚無資料</div>';
								}
								$('div#area_neg').html(strHTML);
								
							}else{
								layer.open({title:'訊息', content:'處理中發生錯誤，請重新嘗試或連繫開發人員[Code:002x]',icon: 2,btn: '知道了'});
							}
						  },
						  complete: function(){
						  },
						  async:true
						});
					}
				}
			}
		})

		// Add data
		chart.data = js_data;

		// Create axes
		var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
		categoryAxis.dataFields.category = "class";		
		
		//左邊資料
		var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
		valueAxis.renderer.minWidth = 50; //圖表距離左側
		valueAxis.numberFormatter = new am4core.NumberFormatter();
		valueAxis.numberFormatter.numberFormat = "#'筆'"
		valueAxis.cursorTooltipEnabled = false; //左側tooltip
		valueAxis.extraMin = 0; //顯示規模擴大不至於讓線貼到底或top
		valueAxis.extraMax = 0.25;
		
		// Create series
		var series = chart.series.push(new am4charts.ColumnSeries());
		series.name = "文章聲量";
		series.sequencedInterpolation = true;
		series.dataFields.valueY = "count";
		series.dataFields.categoryX = "class";
		series.columns.template.strokeWidth = 0; //長條圖邊寬
		series.columns.template.column.cornerRadiusTopLeft = 10; //長條圖邊圓
		series.columns.template.column.cornerRadiusTopRight = 10; //長條圖邊圓
		series.columns.template.column.fillOpacity = 0.8; //長條圖透明度
		series.columns.template.column.fill = am4core.color("#64a7eb");  //長條圖顏色 -> 未設置則自動
		//series.columns.template.events.on("hit", myFunction, this);
		//series.columns.template.cursorOverStyle = am4core.MouseCursorStyle.pointer;
		series.tooltip.label.textAlign = "middle";
		series.tooltipText = "[#fff font-size: 13px] 文章聲量: [/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}筆[/]"
		series.tooltip.pointerOrientation = "left";
		series.tooltip.getFillFromObject = false;
		series.tooltip.background.fill = am4core.color("#64a7eb");
		
		var labelBullet = series.bullets.push(new am4charts.LabelBullet());
		labelBullet.label.verticalCenter = "bottom"; //長條圖資料標籤位置
		labelBullet.label.dy = -10; //長條圖資料標籤位置
		labelBullet.label.text = "{values.valueY.workingValue.formatNumber('#.')}筆";
		
		//右邊資料1
		var paretoValueAxis = chart.yAxes.push(new am4charts.ValueAxis());
		paretoValueAxis.renderer.opposite = true; //右側軸顯示
		paretoValueAxis.strictMinMax = false;
		paretoValueAxis.renderer.grid.template.disabled = true; //輔助線
		paretoValueAxis.numberFormatter = new am4core.NumberFormatter();
		paretoValueAxis.numberFormatter.numberFormat = "#'筆'" //"#.##'倍'"
		paretoValueAxis.cursorTooltipEnabled = false; //右側tooltip
		paretoValueAxis.extraMin = 0; //顯示規模擴大不至於讓線貼到底或top
		paretoValueAxis.extraMax = 0.3;

		var paretoSeries = chart.series.push(new am4charts.LineSeries())
		paretoSeries.name = "正面評價量";
		paretoSeries.dataFields.valueY = "positive";
		paretoSeries.dataFields.categoryX = "class";
		paretoSeries.yAxis = paretoValueAxis;
		paretoSeries.stroke = am4core.color("#56b754");
		paretoSeries.strokeWidth = 3; //線的粗度
		paretoSeries.propertyFields.strokeDasharray = "lineDash";
		paretoSeries.tooltip.label.textAlign = "middle";
		paretoSeries.tooltipText = "[#fff font-size: 13px] 正評價: [/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}筆[/]"
		paretoSeries.tooltip.pointerOrientation = "left";
		paretoSeries.tooltip.getFillFromObject = false;
		paretoSeries.tooltip.background.fill = am4core.color("#56b754");
		paretoSeries.smoothing = "monotoneX";

		var bullet = paretoSeries.bullets.push(new am4charts.CircleBullet());
		bullet.fill = am4core.color("#56b754"); // tooltips grab fill from parent by default
		var circle = bullet.createChild(am4core.Circle);
		circle.radius = 4;
		circle.fill = am4core.color("#fff");
		circle.strokeWidth = 3;
		
		//右邊資料2
		var paretoSeriesAvg = chart.series.push(new am4charts.LineSeries())
		paretoSeriesAvg.name = "負面評價量";
		paretoSeriesAvg.dataFields.valueY = "negative";
		paretoSeriesAvg.dataFields.categoryX = "class";
		paretoSeriesAvg.yAxis = paretoValueAxis;
		paretoSeriesAvg.stroke = am4core.color("#ff5d5d");
		paretoSeriesAvg.strokeWidth = 3;
		paretoSeriesAvg.propertyFields.strokeDasharray = "lineDash";
		//paretoSeriesAvg.strokeDasharray = "3,3"; //虛線
		paretoSeriesAvg.tooltip.label.textAlign = "middle";
		paretoSeriesAvg.tooltipText = "[#fff font-size: 13px] 負評價: [/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}筆[/]"
		paretoSeriesAvg.tooltip.pointerOrientation = "left";
		paretoSeriesAvg.tooltip.getFillFromObject = false;
		paretoSeriesAvg.tooltip.background.fill = am4core.color("#ff5d5d");
		paretoSeriesAvg.smoothing = "monotoneX";
		paretoSeriesAvg.extraMin = 0; //顯示規模擴大不至於讓線貼到底或top
		paretoSeriesAvg.extraMax = 0.3;

		var bulletAvg = paretoSeriesAvg.bullets.push(new am4charts.CircleBullet());
		bulletAvg.fill = am4core.color("#ff5d5d"); // tooltips grab fill from parent by default
		var circleAvg = bulletAvg.createChild(am4core.Circle);
		circleAvg.radius = 4;
		circleAvg.fill = am4core.color("#fff");
		circleAvg.strokeWidth = 3;

		// on hover, make corner radiuses bigger
		var hoverState = series.columns.template.column.states.create("hover");
		hoverState.properties.cornerRadiusTopLeft = 0;
		hoverState.properties.cornerRadiusTopRight = 0;
		hoverState.properties.fillOpacity = 1;

		series.columns.template.adapter.add("fill", function(fill, target) {
		  return chart.colors.getIndex(target.dataItem.index);
		});
		
		// Cursor
		chart.cursor = new am4charts.XYCursor();
		chart.cursor.xAxis = categoryAxis;
		chart.cursor.fullWidthLineX = true;
		chart.cursor.lineX.strokeWidth = 0;
		chart.cursor.lineX.fill = am4core.color("#aaa");
		chart.cursor.lineX.fillOpacity = 0.1;

		chart.logo.height = -15000;
		chart.legend = new am4charts.Legend();
		
		}else{
			$('div#trend').html('<div style="border: 1px solid #ccc;width: 90%;margin: 8px auto;height: 250px;text-align: center;padding: 113px;font-weight: bold;color: #888;">尚 無 資 料</div>')
		}
		
	});
	</script>
</body>

</html>
