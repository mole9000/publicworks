<?php
header("Content-Type: text/html; charset=utf-8"); //Hank 自編 《PHP專書P14-3》
date_default_timezone_set("Etc/GMT-8");
set_time_limit(0);
$filename = "ini.txt";    $fp = fopen($filename, "r");   $path_python = fread($fp, filesize($filename));   fclose($fp);
//$path_python = 'C:/Users/user/AppData/Local/Programs/Python/Python36/python.exe';

if(isset($_POST['contenttext'])){
	$input = $_POST['contenttext'];
	#$input = "派工,安南區安通路六段上，靠近安明路二段的路中有路樹倒塌，請派員前往清除，煩卓處，承辦若不清楚，可聯繫民眾。";  //第一工務,皮球,工程企劃 | 危急,情緒 | 路樹傾倒,9盞以下
	#$input = "派工,安南區安通路六段上，請派員前往清除，承辦若不清楚，可聯繫民眾。";  //皮球,第一工務,養護工程 | 一般 | 9盞以下,路樹傾倒
	#$input = "北區公園北路5號附近，涼亭角落的地方時常有一位遊民在那邊當自己的家，遊民在那邊喝酒還會把玻璃酒瓶打碎在步道上，恐容易造成他人危險，遊民還會把自己的棉被堵在小運河造成堵塞，通報員警前往也沒有用，一直浪費資源，請局處強制安置遊民，也請工務局把涼亭的石桌石椅拆除不要讓遊民在那邊喝酒，煩請相關單位卓處 承辦可聯繫民眾，並請結案時將處理結果電話回覆民眾"; //公園,皮球,第一工務 | 危急,情緒 | 其他
	#$input = "北區公園北路5號附近，煩請相關單位卓處 承辦可聯繫民眾，並請結案時將處理結果電話回覆民眾";  //皮球,公園管理,第一工務 | 一般 | 其他,...一些
	$input = preg_replace('/\s+/', '，', $input); 
		
	
	//==== 科室分類
	$command = $path_python.' '.__DIR__."/analysis/task1_department/bert.py --Subject '".$input. "' 2>error_ana_department.txt";
	//echo $command."<br/><br/>";
	$output = exec($command, $output2,$res);
	$output = mb_convert_encoding($output, 'UTF-8', "BIG5");
	//echo $output.'<br/>外部程序運行是否成功:'.$res.'(0代表成功,1代表失敗)<br/><br/><br/>';
	eval("\$output = $output;"); //ex: ['皮球案件', '第一工務大隊', '公園管理科']
	$output_department = $output; // 如上陣列
	$state_department = $res;
	
}

?>



<!DOCTYPE html>

<html lang="en" class="material-style layout-fixed">

<head>
    <title>臺南市政府工務局 - 陳情案件分析與管理系統</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="Empire Bootstrap admin template made using Bootstrap 4, it has tons of ready made feature, UI components, pages which completely fulfills any dashboard needs." />
    <meta name="keywords" content="Empire, bootstrap admin template, bootstrap admin panel, bootstrap 4 admin template, admin template">
    <meta name="author" content="Srthemesvilla" />
    <link rel="icon" type="image/x-icon" href="images/favicon2.ico">

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

	<style>
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
	
	.card-header{
		padding: 0.7rem 1.5rem;
		font-weight:bold;
	}
	.form-label{
		font-size: 1rem;
		font-weight:bold;
	}
	button{
		font-size: 0.9rem !important;
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
                        <img src="images/logo_test2.png" alt="Brand Logo" class="img-fluid" style="width: 155px;padding-left: 5px;">
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
                        <a href="#" class="sidenav-link">
                            <i class="sidenav-icon feather icon-home"></i>
                            <div>系統介紹</div>
                            <div class="pl-1 ml-auto">
                                <div class="badge badge-danger">首頁</div>
                            </div>
                        </a>
                    </li>

                    <!-- Layouts -->
                    <li class="sidenav-divider mb-1"></li>
                    <li class="sidenav-header small font-weight-semibold">功能列表</li>
                    <li class="sidenav-item">
                        <a href="analysis_all.php" class="sidenav-link">
                            <i class="sidenav-icon feather icon-layers"></i>
                            <div>綜合分析</div>
                        </a>
                    </li>
                    <li class="sidenav-item">
                        <a href="analysis_importance.php" class="sidenav-link">
                            <i class="sidenav-icon feather icon-delete"></i>
                            <div>重要性分析</div>
                        </a>
                    </li>
                    <li class="sidenav-item active">
                        <a href="analysis_department.php" class="sidenav-link">
                            <i class="sidenav-icon feather icon-filter"></i>
                            <div>科室分類</div>
                        </a>
                    </li>
                    <li class="sidenav-item">
                        <a href="analysis_reply.php" class="sidenav-link">
                            <i class="sidenav-icon feather icon-message-square"></i>
                            <div>預擬回覆</div>
                        </a>
                    </li>

                    <!-- Forms & Tables -->
                    <li class="sidenav-divider mb-1"></li>
                    <li class="sidenav-header small font-weight-semibold">簡介</li>
                    <li class="sidenav-item">
                        <a href="#" class="sidenav-link">
                            <i class="sidenav-icon feather icon-anchor"></i>
                            <div>Q&A</div>
                        </a>
                    </li>
                    <li class="sidenav-item">
                        <a href="#" class="sidenav-link">
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
									<a href="index.php" class="app-brand-text demo sidenav-text font-weight-normal ml-2" style="font-size:18px;">陳情案件分析與管理系統 <span></span></a>
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
                                        <i class="feather icon-settings text-muted"></i> &nbsp; 前端系統</a>
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
                        <h4 class="font-weight-bold py-3 mb-0">科室分類</h4>
                        <div class="text-muted small mt-0 mb-4 d-block breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#">首頁</a></li>
                                <li class="breadcrumb-item active">科室分類</li>
                            </ol>
                        </div>
                        <div class="card mb-4">
                            <h5 class="card-header">陳情案件分析</h5>
                            <div class="card-body">
                                <form method="post">
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label class="form-label">陳情內容</label>
											<div class="col-sm-12" style="padding-left:0px;margin-top:15px;">
												<textarea id="contenttext" name="contenttext" class="form-control" placeholder="輸入" style="width: 100%;font-size: 1rem;"><?php if(isset($input)){echo $input;}else{ echo '';}?></textarea>
											</div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary" style="float: right;margin-right: 12px;" onclick="load()">送出</button>
                                </form>
                            </div>
                        </div>
					    <div class="row">
                            <div class="col-lg-12">
                                <div class="card mb-4">
									<h5 class="card-header">分析結果</h5>
                                    <div class="row no-gutters row-bordered">
                                        <div class="col-xl-12 p-4">
                                            <span style="font-size: 1rem;"><b>科室分類</b> <!--<span id="change_tag" style="font-size: 0.85rem;color: #d12b2b;font-weight: bold;">　　#改分註記</span>--></span>
											<button type="button" class="btn btn-sm btn-outline-warning" style="float:right;" onclick="showFeedback('feedback_classifier.php')"><span class="far fa-paper-plane"></span>&nbsp;&nbsp;反饋</button>
                                            <hr>
                                            <div class="demo-inline-spacing mt-3">
												<?php
												if(!isset($output_department)){
													echo '<span>尚無分析結果</span>';
												}else{
													if(count($output_department)==0){
														echo '<span>尚無法判別，請再右側進行反饋</span>';
													}else{
														foreach ($output_department as $key => $value) {
															echo '<button type="button" class="ResultClassifier btn btn-info">　'.$value.'　</button>';
														}
													}
												}
												?>
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
                                <span class="footer-text font-weight-semibold">&copy; <a href="#" class="footer-link" target="_blank">2022國立成功大學#物聯網與城市計算#工務局組 All Rights Reserved.</a></span>
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
    <!-- [ Layout wrapper] End -->

    <script src="assets/js/pace.js"></script>
    <script src="assets/js/jquery-3.3.1.min.js"></script>
    <script src="assets/libs/popper/popper.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/sidenav.js"></script>
    <script src="assets/js/layout-helpers.js"></script>
    <script src="assets/js/material-ripple.js"></script>
    <script src="assets/js/demo.js"></script><script src="assets/js/analytics.js"></script>
	
	<link href="layui-v2.5.7/layui/css/layui.css" rel="stylesheet" type="text/css"/>
    <script src="js/jquery-3.1.1.js"></script>
	<script type="text/javascript" src="layer-v3.1.0/layer/layer.js"></script>
	<script type="text/javascript" src="layui-v2.5.7/layui/layui.js"></script>
	
	<script>
	function load(){
		layer.msg('分析中，請稍後...', {
			icon: 16
			,anim: -1
			,time: 0
			,shade: 0.6
			,fixed: false
		});
	}
	function showFeedback(url){
		var indexForm = layer.open({
			type: 2,
			title:'',
			scrollbar: true,
			skin: 'layui-layer-rim', //加上边框
			area: ['980px', '80%'], //宽高
			content: [url]
		});
	}
	function showReplyManage(subitem){
		var indexForm = layer.open({
			type: 2,
			title:'預擬回覆',
			scrollbar: true,
			skin: 'layui-layer-rim', //加上边框
			area: ['1080px', '80%'], //宽高
			content: ['reply_manage.php?subitem='+subitem]
		});
	}
	</script>
</body>

</html>
