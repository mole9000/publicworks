<?php
if(isset($_POST['item']) && isset($_POST['subitem']) && isset($_POST['content'])){
    
    $item = escapeshellarg($_POST['item']);
    $subitem = escapeshellarg($_POST['subitem']);
    $content = escapeshellarg($_POST['content']);
    $command = "/usr/bin/python3.7 ".__DIR__."/update_csv.py 'reply' $content $item $subitem";
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
                                                    <a class="nav-link active" id="TabArea_tableScore" data-toggle="tab" href="#index1" >預擬回覆 - 分析反饋</a>
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
																<!-- <div class="badge badge-dark">路燈故障不亮</div> -->
																<!-- <div class="badge badge-dark">路燈損毀、傾倒</div> -->
																<!-- <div class="badge badge-dark">9盞以下路燈故障</div> -->
															</div></td>
															<td></td>
														</tr>
														<tr>
															<td>反饋建議</td>
															<td>
															
																<div class="form-row">
																	<div class="form-group col-md-4" style="margin-bottom:0px;">
																		<label class="form-label">主項目</label>
																		<select name="item" id="item-list" class="custom-select" onchange="changeItem(this.selectedIndex)"></select>
																	</div>
																	<div class="form-group col-md-4" style="margin-bottom:0px;">
																		<label class="form-label">子項目</label>
																		<select name="subitem" id="subitem-list" class="custom-select"></select>
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

	//=========== 建立連動下拉清單
	var colleges=['警政及路霸排除類','環保類','交通類','道路、人行道、騎樓及排水溝類','路燈路樹公園類','建築管理及使用類','水利類','教育類','衛生醫療類','社會福利救助類','勞工類','經濟發展類','文化觀光類','農林漁畜及動保類','民政類','都市發展類','地政類','消防類','新聞及國際關係類','財稅類','人事行政類','青年事務類','體育類','行動應用程式(APP)','1999-工務類','1999-交通類','1999-警政類','1999-環保類','1999-動保類','1999-公用事業類','1999-水利類','Covid-19居家照護服務','其他類','存參類'];
	var collegeSelect=document.getElementById("item-list");
	var inner="";
	for(var i=0;i<colleges.length;i++){
		inner=inner+'<option value="'+colleges[i]+'">'+colleges[i]+'</option>';
	}
	collegeSelect.innerHTML=inner;			
	var sectors=new Array();
	sectors[0]=['違規停車(車輛佔用道路、人行道、騎樓)','物品佔用道路、人行道、騎樓(含移動式、固定式、招牌、景觀燈)','廢棄車輛佔用道路、人行道、騎樓','違規爭議及投訴(含拖吊)','治安維護','交通管制疏導','監視器問題','其他'];
	sectors[1]=['水、空氣、環境污染','垃圾清運(含垃圾車及回收車)','廢棄物處理(含回收、掩埋、焚化)','路面清理(含垃圾、油漬、排泄物及動物屍體)','空地空屋髒亂','場所、設備及連續噪音','人、動物及非連續噪音','其他'];
	sectors[2]=['路邊停車格及停車場問題','停車收費問題','標線問題','號誌、標誌問題','公車問題','自行車問題','捷運問題','違規裁罰','無人機問題','其他'];
	sectors[3]=['道路、人行道凹陷破損回填不實','道路、人行道工程問題','道路、橋樑開闢徵收補償','地下道問題(含車行及人行)','排水溝溝蓋破損、鬆動、遺失','排水溝淤積清疏','其他'];
	sectors[4]=['路燈故障不亮','路燈損毀、傾倒','路樹修剪、傾倒','車輛違規進入公園','公園設施損毀','公園、綠地、安全島髒亂','其他'];
	sectors[5]=['違建查報及拆除','公寓大廈管理','建物公共安全','廣告招牌問題','建築管理問題','其他'];
	sectors[6]=['雨、污水下水道工程','積(淹)水問題','堤防、護岸、擋土牆','山坡地水土保持','其他'];
	sectors[7]=['幼兒園與學費補助','補教問題','十二年國教','教師介聘及甄選','親子溝通、班級經營','招生入學','英語教育與推廣','其他'];
	sectors[8]=['食安','防疫','醫事醫療','心理健康','登革熱防治','其他'];
	sectors[9]=['低與中低收入戶、急難救助','社會福利(含兒少、婦女、老人、身障)','照顧服務管理(含長照照服員及據點)','復康巴士','街友問題','人民團體問題','家暴及性侵防治','其他'];
	sectors[10]=['勞基法問題(含一例一休)','勞資爭議協調','求職與徵才','職業災害及衛生安全','移工問題','其他'];
	sectors[11]=['違規稽查','工商及公司登記','市場、攤販、夜市問題','能源及公用事業','商圈及市集','其他'];
	sectors[12]=['圖書館管理','文化中心及藝文活動','古蹟、文資及文化園區管理','旅館、民宿及溫泉區管理','風景區管理','觀光行銷、旅行業及旅展','觀光活動、美食小吃及伴手禮','其他'];
	sectors[13]=['森林自然保育','農地管理','農務、漁業及畜產','漁港及近海管理','動物救援','動物防疫(含禽流感、狂犬病)','動物捕捉及收容','其他']
	sectors[14]=['廟會及宗教輔導團','生命事業及殯葬問題','戶政問題','行政區劃、鄰里整編','社區活動中心','兵役問題','其他'];
	sectors[15]=['都市計畫與規劃','使用分區','購(租)屋補貼及貸款','國宅、社會及照顧住宅','其他'];
	sectors[16]=['土地測量','土地登記與地籍問題','市地重劃','開發工程','徵收補償','其他'];
	sectors[17]=['消防安全設備','災害應變及搶救','緊急救護','其他'];
	sectors[18]=['有線電視及第三頻道','國際關係','官方Line問題','其他'];
	sectors[19]=['稅務問題','公產管理','菸酒管理','其他'];
	sectors[21]=['公務人員考核獎懲','考試分發及任免遷調','組織編制','其他'];
	sectors[22]=['青年就業','青年創業','青年居住','育兒福利0-2歲','幼兒教育2-4歲','網路公共事務參與','公共事務參與'];
	sectors[23]=['運動設施及產業','運動推廣、賽會及補助','學校體育(學校選手參賽及聘用、補助、場地管理)','其他'];
	sectors[24]=['大台南公車','旅行台南','台南工作好找','台南水情即時通','T-Bike臺南市公共自行車','OPEN台南1999','安平GO好行','臺南好停','台南市福利地圖','臺南市道路挖掘行動查報系統','台南水情巡查報APP','南市地政e網通','臺南市立圖書館-WOW愛讀冊','札哈木部落大學','台南環保通','臺南市道路養護資訊系統','其他APP事項'];
	sectors[25]=['路面坑洞','寬頻管線、孔蓋損壞','路面下陷、凹陷','路面掏空、塌陷','路樹傾倒','9盞以下路燈故障'];
	sectors[26]=['號誌故障','號誌秒差調整','公車動態LED跑馬燈資訊顯示異常'];
	sectors[27]=['妨礙安寧','違規停車','騎樓舉發','佔用道路','交通疏導','無人機違規'];
	sectors[28]=['公園髒亂','古蹟髒亂','風景區髒亂','其他環境髒亂','市區道路路面油漬','連續噪音','空氣污染','道路散落物','大型廢棄物清運','小廣告、旗幟','其他污染舉發'];
	sectors[29]=['犬貓受傷（不含家畜禽、野生動物）','遊蕩犬隻捕捉管制','其他動物受傷'];
	sectors[30]=['台電--停電、10盞以上路燈不亮、電線掉落、漏電、孔蓋鬆動','台水--漏水、停水、消防栓漏水或損壞','瓦斯01-管溝修補、孔蓋鬆動','瓦斯02-天然氣外洩搶修','中華電信-電信孔蓋鬆動、電信線路掉落、電信桿傾倒'];
	sectors[31]=['地下道積水','人孔蓋或溝蓋聲響、鬆動','人孔蓋凹陷坑洞'];
	sectors[32]=['生活-區政及里鄰長關懷服務','生活-垃圾清運','生活-採購生活及防疫物資','生活-寵物照顧','生活-後送就醫','生活-孤老弱勢及身障者關懷服務','生活-勞工權益','生活-學生關懷服務','生活-公寓大樓事務','健康-健康評估及關懷、遠距醫療、居家送藥','健康-防疫物資、居隔解隔、電子圍籬','其他'];
	sectors[33]=['線上申辦','其他'];
	sectors[34]=['廣告信件','登記後不予處理','不實個資'];
	function changeItem(index){
		var Sinner="";
		for(var i=0;i<sectors[index].length;i++){
			Sinner=Sinner+'<option value="'+sectors[index][i]+'">'+sectors[index][i]+'</option>';
		}
		var sectorSelect=document.getElementById("subitem-list");
		sectorSelect.innerHTML=Sinner;
	}
	changeItem(document.getElementById("item-list").selectedIndex);
	
	
	
	$(document).ready(function() {

		//取得父視窗陳情內容
		var content = parent.$('#contenttext').val()
		$('textarea#contenttextarea').val(content);	
		if(content.length==0){content="尚無分析內容";}
		$('td#contenttext').html(content);	
		console.log(content.length)		
		
		//取得父視窗分析結果
		showResult = "";
		var ResultCount = parent.$('.ResultReply').length
		for (let i = 0; i < ResultCount; i++) {	
			showResult += '<div class="badge badge-dark">'+parent.$('.ResultReply').eq(i).html()+'</div>  ';
		}								
		if(showResult==""){showResult = "尚無分析內容";}
		$('#Result').html(showResult);
		
		
	});
	</script>
	
</body>

</html>
