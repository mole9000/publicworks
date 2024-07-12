<?php
if(isset($_POST['department']) && isset($_POST['content'])){
    $department = escapeshellarg($_POST['department']);
    $content = escapeshellarg($_POST['content']);
    $command = "/usr/bin/python3.7 ".__DIR__."/update_csv.py 'classifier' $department $content";
    $output = shell_exec($command);
?>
	<link href="layui-v2.5.7/layui/css/layui.css" rel="stylesheet" type="text/css"/>
    <script src="js/jquery-3.1.1.js"></script>
	<script type="text/javascript" src="layer-v3.1.0/layer/layer.js"></script>
	<script type="text/javascript" src="layui-v2.5.7/layui/layui.js"></script>
	<script>
	layer.open({title: '分析反饋',content: '反饋完成，已反饋到系統後端！<br/>後續將由專業人員調適分析模型。',btn: ['確定']});
	</script>
<?php
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

    <script src="js/jquery-3.1.1.js"></script>
</head>
<style>
.badge{
	padding: 0.5em 0.7em;
	font-size: 0.9em;
}
.layui-layer-btn{
	color: #fff;
}
</style>
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
            <!-- [ Layout sidenav ] End -->
            <!-- [ Layout container ] Start -->
                <!-- [ Layout navbar ( Header ) ] End -->

                <!-- [ Layout content ] Start -->

                    
					    <div class="row" style="margin: 20px auto;width: 950px;">
						<form method="post">
                            <div class="col-xl-12">
                                <div class="card mb-4">
                                    <div class="card-header with-elements pb-0" style="padding-top: 5px;padding-bottom: 3px !important;">
                                        <h6 class="card-header-title mb-0" id="interval_label"><strong></strong></h6>
                                        <div class="card-header-elements ml-auto p-0">
                                            <ul class="nav nav-tabs">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="TabArea_tableScore" data-toggle="tab" href="#index1" >科室分類 - 分析反饋</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="nav-tabs-top">
                                        <div class="tab-content">
                                            <div class="tab-pane fade show active" id="index1">
                                                <div class="tabArea">
													<table class="table table-hover card-table">
													<tbody>
														<tr>
															<td style="width:100px">陳情內容</td>
															<td id="contenttext" style="white-space: normal;"></td>
															<textarea id="contenttextarea" name="content" style="display:none;"></textarea>
															<td style="width:100px"></td>
														</tr>
														<tr>
															<td>分析結果</td>
															<td><div class="pl-1 ml-auto" id="Result">
															
															</div></td>
															<td></td>
														</tr>
														<tr>
															<td>反饋建議</td>
															<td>
															
																<div class="form-row">
																	<div class="form-group col-md-4" style="margin-bottom:0px;">
																		<select name="department" id="department" class="custom-select">
																			<option value="工程企劃科">工程企劃科</option>
																			<option value="採購品管科">採購品管科</option>
																			<option value="建築管理科">建築管理科</option>
																			<option value="使用管理科">使用管理科</option>
																			<option value="秘書室">秘書室</option>
																			<option value="人事室">人事室</option>
																			<option value="會計室">會計室</option>
																			<option value="政風室">政風室</option>
																			<option value="公園管理科">公園管理科</option>
																			<option value="養護工程科">養護工程科</option>
																			<option value="新建工程科">新建工程科</option>
																			<option value="第一工務大隊">第一工務大隊</option>
																			<option value="第二工務大隊">第二工務大隊</option>
																			<option value="第三工務大隊">第三工務大隊</option>
																			<option value="主任秘書室">主任秘書室</option>
																			<option value="建築工程科">建築工程科</option>
																		</select>
																	</div>
																</div>
															</td>
															<td></td>
														</tr>
														<tr>
															<td></td>
															<td></td>
															<td><button type="submit" class="btn btn-primary" style="float: right;margin-right: 12px;">　送出　</button></td>
														</tr>
													</tbody>
													</table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
						</form>
						</div>
                    <!-- [ content ] End -->

                    <!-- [ Layout footer ] End -->
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
	
	<link href="layui-v2.5.7/layui/css/layui.css" rel="stylesheet" type="text/css"/>
    <script src="js/jquery-3.1.1.js"></script>
	<script type="text/javascript" src="layer-v3.1.0/layer/layer.js"></script>
	<script type="text/javascript" src="layui-v2.5.7/layui/layui.js"></script>
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

	
	$(document).ready(function() {

		//取得父視窗陳情內容
		var content = parent.$('#contenttext').val()
		$('textarea#contenttextarea').val(content);	
		if(content.length==0){content="尚無分析內容";}
		$('td#contenttext').html(content);	
		console.log(content.length)		
		
		//取得父視窗分析結果
		showResult = "";
		var ResultCount = parent.$('.ResultClassifier').length
		for (let i = 0; i < ResultCount; i++) {	
			showResult += '<div class="badge badge-info">'+parent.$('.ResultClassifier').eq(i).html()+'</div>  ';
		}								
		if(showResult==""){showResult = "尚無分析內容";}
		$('#Result').html(showResult);
		
		
	});
	</script>
	
</body>

</html>
