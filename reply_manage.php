<?php
date_default_timezone_set("Etc/GMT-8");

$itemIDNameDict = array();
$itemIDNameDict["01"] = "警政及路霸排除類";
$itemIDNameDict["02"] = "環保類";
$itemIDNameDict["03"] = "交通類";
$itemIDNameDict["04"] = "道路、人行道、騎樓及排水溝類";
$itemIDNameDict["05"] = "路燈路樹公園類";
$itemIDNameDict["06"] = "建築管理及使用類";
$itemIDNameDict["07"] = "水利類";
$itemIDNameDict["08"] = "教育類";
$itemIDNameDict["09"] = "衛生醫療類";
$itemIDNameDict["10"] = "社會福利救助類";
$itemIDNameDict["11"] = "勞工類";
$itemIDNameDict["12"] = "經濟發展類";
$itemIDNameDict["13"] = "文化觀光類";
$itemIDNameDict["14"] = "農林漁畜及動保類";
$itemIDNameDict["15"] = "民政類";
$itemIDNameDict["16"] = "都市發展類";
$itemIDNameDict["17"] = "地政類";
$itemIDNameDict["18"] = "消防類";
$itemIDNameDict["19"] = "新聞及國際關係類";
$itemIDNameDict["20"] = "財稅類";
$itemIDNameDict["21"] = "人事行政類";
$itemIDNameDict["22"] = "青年事務類";
$itemIDNameDict["23"] = "體育類";
$itemIDNameDict["60"] = "行動應用程式(APP)";
$itemIDNameDict["61"] = "1999-工務類";
$itemIDNameDict["62"] = "1999-交通類";
$itemIDNameDict["63"] = "1999-警政類";
$itemIDNameDict["64"] = "1999-環保類";
$itemIDNameDict["65"] = "1999-動保類";
$itemIDNameDict["66"] = "1999-公用事業類";
$itemIDNameDict["67"] = "1999-水利類";
$itemIDNameDict["69"] = "Covid-19居家照護服務";
$itemIDNameDict["90"] = "其他類";
$itemIDNameDict["91"] = "存參類";
$subitemIDNameDict = array();
$subitemIDNameDict["01-01"] = "違規停車(車輛佔用道路、人行道、騎樓)";
$subitemIDNameDict["01-02"] = "物品佔用道路、人行道、騎樓(含移動式、固定式、招牌、景觀燈)";
$subitemIDNameDict["01-03"] = "廢棄車輛佔用道路、人行道、騎樓";
$subitemIDNameDict["01-04"] = "違規爭議及投訴(含拖吊)";
$subitemIDNameDict["01-05"] = "治安維護";
$subitemIDNameDict["01-06"] = "交通管制疏導";
$subitemIDNameDict["01-07"] = "監視器問題";
$subitemIDNameDict["01-99"] = "其他";
$subitemIDNameDict["02-01"] = "水、空氣、環境污染";
$subitemIDNameDict["02-02"] = "垃圾清運(含垃圾車及回收車)";
$subitemIDNameDict["02-03"] = "廢棄物處理(含回收、掩埋、焚化)";
$subitemIDNameDict["02-04"] = "路面清理(含垃圾、油漬、排泄物及動物屍體)";
$subitemIDNameDict["02-05"] = "空地空屋髒亂";
$subitemIDNameDict["02-06"] = "場所、設備及連續噪音";
$subitemIDNameDict["02-07"] = "人、動物及非連續噪音";
$subitemIDNameDict["02-99"] = "其他";
$subitemIDNameDict["03-01"] = "路邊停車格及停車場問題";
$subitemIDNameDict["03-02"] = "停車收費問題";
$subitemIDNameDict["03-03"] = "標線問題";
$subitemIDNameDict["03-04"] = "號誌、標誌問題";
$subitemIDNameDict["03-05"] = "公車問題";
$subitemIDNameDict["03-06"] = "自行車問題";
$subitemIDNameDict["03-07"] = "捷運問題";
$subitemIDNameDict["03-08"] = "違規裁罰";
$subitemIDNameDict["03-10"] = "無人機問題";
$subitemIDNameDict["03-99"] = "其他";
$subitemIDNameDict["04-01"] = "道路、人行道凹陷破損回填不實";
$subitemIDNameDict["04-02"] = "道路、人行道工程問題";
$subitemIDNameDict["04-03"] = "道路、橋樑開闢徵收補償";
$subitemIDNameDict["04-04"] = "地下道問題(含車行及人行)";
$subitemIDNameDict["04-05"] = "排水溝溝蓋破損、鬆動、遺失";
$subitemIDNameDict["04-06"] = "排水溝淤積清疏";
$subitemIDNameDict["04-99"] = "其他";
$subitemIDNameDict["05-01"] = "路燈故障不亮";
$subitemIDNameDict["05-02"] = "路燈損毀、傾倒";
$subitemIDNameDict["05-03"] = "路樹修剪、傾倒";
$subitemIDNameDict["05-04"] = "車輛違規進入公園";
$subitemIDNameDict["05-05"] = "公園設施損毀";
$subitemIDNameDict["05-06"] = "公園、綠地、安全島髒亂";
$subitemIDNameDict["05-99"] = "其他";
$subitemIDNameDict["06-01"] = "違建查報及拆除";
$subitemIDNameDict["06-02"] = "公寓大廈管理";
$subitemIDNameDict["06-03"] = "建物公共安全";
$subitemIDNameDict["06-04"] = "廣告招牌問題";
$subitemIDNameDict["06-05"] = "建築管理問題";
$subitemIDNameDict["06-99"] = "其他";
$subitemIDNameDict["07-01"] = "雨、污水下水道工程";
$subitemIDNameDict["07-02"] = "積(淹)水問題";
$subitemIDNameDict["07-03"] = "堤防、護岸、擋土牆";
$subitemIDNameDict["07-04"] = "山坡地水土保持";
$subitemIDNameDict["07-99"] = "其他";
$subitemIDNameDict["08-02"] = "幼兒園與學費補助";
$subitemIDNameDict["08-03"] = "補教問題";
$subitemIDNameDict["08-04"] = "十二年國教";
$subitemIDNameDict["08-05"] = "教師介聘及甄選";
$subitemIDNameDict["08-06"] = "親子溝通、班級經營";
$subitemIDNameDict["08-07"] = "招生入學";
$subitemIDNameDict["08-08"] = "英語教育與推廣";
$subitemIDNameDict["08-99"] = "其他";
$subitemIDNameDict["09-01"] = "食安";
$subitemIDNameDict["09-02"] = "防疫";
$subitemIDNameDict["09-03"] = "醫事醫療";
$subitemIDNameDict["09-04"] = "心理健康";
$subitemIDNameDict["09-05"] = "登革熱防治";
$subitemIDNameDict["09-99"] = "其他";
$subitemIDNameDict["10-01"] = "低與中低收入戶、急難救助";
$subitemIDNameDict["10-02"] = "社會福利(含兒少、婦女、老人、身障)";
$subitemIDNameDict["10-03"] = "照顧服務管理(含長照照服員及據點)";
$subitemIDNameDict["10-04"] = "復康巴士";
$subitemIDNameDict["10-05"] = "街友問題";
$subitemIDNameDict["10-06"] = "人民團體問題";
$subitemIDNameDict["10-07"] = "家暴及性侵防治";
$subitemIDNameDict["10-99"] = "其他";
$subitemIDNameDict["11-01"] = "勞基法問題(含一例一休)";
$subitemIDNameDict["11-02"] = "勞資爭議協調";
$subitemIDNameDict["11-03"] = "求職與徵才";
$subitemIDNameDict["11-04"] = "職業災害及衛生安全";
$subitemIDNameDict["11-05"] = "移工問題";
$subitemIDNameDict["11-99"] = "其他";
$subitemIDNameDict["12-01"] = "違規稽查";
$subitemIDNameDict["12-02"] = "工商及公司登記";
$subitemIDNameDict["12-03"] = "市場、攤販、夜市問題";
$subitemIDNameDict["12-04"] = "能源及公用事業";
$subitemIDNameDict["12-05"] = "商圈及市集";
$subitemIDNameDict["12-99"] = "其他";
$subitemIDNameDict["13-01"] = "圖書館管理";
$subitemIDNameDict["13-02"] = "文化中心及藝文活動";
$subitemIDNameDict["13-03"] = "古蹟、文資及文化園區管理";
$subitemIDNameDict["13-04"] = "旅館、民宿及溫泉區管理";
$subitemIDNameDict["13-05"] = "風景區管理";
$subitemIDNameDict["13-06"] = "觀光行銷、旅行業及旅展";
$subitemIDNameDict["13-07"] = "觀光活動、美食小吃及伴手禮";
$subitemIDNameDict["13-99"] = "其他";
$subitemIDNameDict["14-01"] = "森林自然保育";
$subitemIDNameDict["14-02"] = "農地管理";
$subitemIDNameDict["14-03"] = "農務、漁業及畜產";
$subitemIDNameDict["14-04"] = "漁港及近海管理";
$subitemIDNameDict["14-05"] = "動物救援";
$subitemIDNameDict["14-06"] = "動物防疫(含禽流感、狂犬病)";
$subitemIDNameDict["14-07"] = "動物捕捉及收容";
$subitemIDNameDict["14-99"] = "其他";
$subitemIDNameDict["15-01"] = "廟會及宗教輔導團";
$subitemIDNameDict["15-02"] = "生命事業及殯葬問題";
$subitemIDNameDict["15-03"] = "戶政問題";
$subitemIDNameDict["15-04"] = "行政區劃、鄰里整編";
$subitemIDNameDict["15-05"] = "社區活動中心";
$subitemIDNameDict["15-06"] = "兵役問題";
$subitemIDNameDict["15-99"] = "其他";
$subitemIDNameDict["16-01"] = "都市計畫與規劃";
$subitemIDNameDict["16-02"] = "使用分區";
$subitemIDNameDict["16-03"] = "購(租)屋補貼及貸款";
$subitemIDNameDict["16-04"] = "國宅、社會及照顧住宅";
$subitemIDNameDict["16-99"] = "其他";
$subitemIDNameDict["17-01"] = "土地測量";
$subitemIDNameDict["17-02"] = "土地登記與地籍問題";
$subitemIDNameDict["17-03"] = "市地重劃";
$subitemIDNameDict["17-04"] = "開發工程";
$subitemIDNameDict["17-05"] = "徵收補償";
$subitemIDNameDict["17-99"] = "其他";
$subitemIDNameDict["18-01"] = "消防安全設備";
$subitemIDNameDict["18-02"] = "災害應變及搶救";
$subitemIDNameDict["18-03"] = "緊急救護";
$subitemIDNameDict["18-99"] = "其他";
$subitemIDNameDict["19-01"] = "有線電視及第三頻道";
$subitemIDNameDict["19-02"] = "國際關係";
$subitemIDNameDict["19-03"] = "官方Line問題";
$subitemIDNameDict["19-99"] = "其他";
$subitemIDNameDict["20-01"] = "稅務問題";
$subitemIDNameDict["20-02"] = "公產管理";
$subitemIDNameDict["20-03"] = "菸酒管理";
$subitemIDNameDict["20-99"] = "其他";
$subitemIDNameDict["21-01"] = "公務人員考核獎懲";
$subitemIDNameDict["21-02"] = "考試分發及任免遷調";
$subitemIDNameDict["21-03"] = "組織編制";
$subitemIDNameDict["21-99"] = "其他";
$subitemIDNameDict["22-01"] = "青年就業";
$subitemIDNameDict["22-02"] = "青年創業";
$subitemIDNameDict["22-03"] = "青年居住";
$subitemIDNameDict["22-04"] = "育兒福利0-2歲";
$subitemIDNameDict["22-05"] = "幼兒教育2-4歲";
$subitemIDNameDict["22-06"] = "網路公共事務參與";
$subitemIDNameDict["22-99"] = "公共事務參與";
$subitemIDNameDict["23-01"] = "運動設施及產業";
$subitemIDNameDict["23-02"] = "運動推廣、賽會及補助";
$subitemIDNameDict["23-03"] = "學校體育(學校選手參賽及聘用、補助、場地管理)";
$subitemIDNameDict["23-99"] = "其他";
$subitemIDNameDict["60-01"] = "大台南公車";
$subitemIDNameDict["60-02"] = "旅行台南";
$subitemIDNameDict["60-03"] = "台南工作好找";
$subitemIDNameDict["60-04"] = "台南水情即時通";
$subitemIDNameDict["60-05"] = "T-Bike臺南市公共自行車";
$subitemIDNameDict["60-09"] = "OPEN台南1999";
$subitemIDNameDict["60-10"] = "安平GO好行";
$subitemIDNameDict["60-11"] = "臺南好停";
$subitemIDNameDict["60-14"] = "台南市福利地圖";
$subitemIDNameDict["60-18"] = "臺南市道路挖掘行動查報系統";
$subitemIDNameDict["60-19"] = "台南水情巡查報APP";
$subitemIDNameDict["60-20"] = "南市地政e網通";
$subitemIDNameDict["60-22"] = "臺南市立圖書館-WOW愛讀冊";
$subitemIDNameDict["60-23"] = "札哈木部落大學";
$subitemIDNameDict["60-24"] = "台南環保通";
$subitemIDNameDict["60-25"] = "臺南市道路養護資訊系統";
$subitemIDNameDict["60-99"] = "其他APP事項";
$subitemIDNameDict["61-01"] = "路面坑洞";
$subitemIDNameDict["61-02"] = "寬頻管線、孔蓋損壞";
$subitemIDNameDict["61-03"] = "路面下陷、凹陷";
$subitemIDNameDict["61-04"] = "路面掏空、塌陷";
$subitemIDNameDict["61-05"] = "路樹傾倒";
$subitemIDNameDict["61-06"] = "9盞以下路燈故障";
$subitemIDNameDict["62-01"] = "號誌故障";
$subitemIDNameDict["62-02"] = "號誌秒差調整";
$subitemIDNameDict["62-03"] = "公車動態LED跑馬燈資訊顯示異常";
$subitemIDNameDict["63-01"] = "妨礙安寧";
$subitemIDNameDict["63-02"] = "違規停車";
$subitemIDNameDict["63-03"] = "騎樓舉發";
$subitemIDNameDict["63-04"] = "佔用道路";
$subitemIDNameDict["63-05"] = "交通疏導";
$subitemIDNameDict["63-06"] = "無人機違規";
$subitemIDNameDict["64-01"] = "公園髒亂";
$subitemIDNameDict["64-02"] = "古蹟髒亂";
$subitemIDNameDict["64-03"] = "風景區髒亂";
$subitemIDNameDict["64-04"] = "其他環境髒亂";
$subitemIDNameDict["64-05"] = "市區道路路面油漬";
$subitemIDNameDict["64-06"] = "連續噪音";
$subitemIDNameDict["64-07"] = "空氣污染";
$subitemIDNameDict["64-08"] = "道路散落物";
$subitemIDNameDict["64-09"] = "大型廢棄物清運";
$subitemIDNameDict["64-10"] = "小廣告、旗幟";
$subitemIDNameDict["64-11"] = "其他污染舉發";
$subitemIDNameDict["65-01"] = "犬貓受傷（不含家畜禽、野生動物）";
$subitemIDNameDict["65-02"] = "遊蕩犬隻捕捉管制";
$subitemIDNameDict["65-03"] = "其他動物受傷";
$subitemIDNameDict["66-01"] = "台電--停電、10盞以上路燈不亮、電線掉落、漏電、孔蓋鬆動";
$subitemIDNameDict["66-02"] = "台水--漏水、停水、消防栓漏水或損壞";
$subitemIDNameDict["66-03"] = "瓦斯01-管溝修補、孔蓋鬆動";
$subitemIDNameDict["66-04"] = "瓦斯02-天然氣外洩搶修";
$subitemIDNameDict["66-05"] = "中華電信-電信孔蓋鬆動、電信線路掉落、電信桿傾倒";
$subitemIDNameDict["67-01"] = "地下道積水";
$subitemIDNameDict["67-02"] = "人孔蓋或溝蓋聲響、鬆動";
$subitemIDNameDict["67-03"] = "人孔蓋凹陷坑洞";
$subitemIDNameDict["69-01"] = "生活-區政及里鄰長關懷服務";
$subitemIDNameDict["69-02"] = "生活-垃圾清運";
$subitemIDNameDict["69-03"] = "生活-採購生活及防疫物資";
$subitemIDNameDict["69-04"] = "生活-寵物照顧";
$subitemIDNameDict["69-05"] = "生活-後送就醫";
$subitemIDNameDict["69-06"] = "生活-孤老弱勢及身障者關懷服務";
$subitemIDNameDict["69-07"] = "生活-勞工權益";
$subitemIDNameDict["69-08"] = "生活-學生關懷服務";
$subitemIDNameDict["69-09"] = "生活-公寓大樓事務";
$subitemIDNameDict["69-10"] = "健康-健康評估及關懷、遠距醫療、居家送藥";
$subitemIDNameDict["69-11"] = "健康-防疫物資、居隔解隔、電子圍籬";
$subitemIDNameDict["69-99"] = "其他";
$subitemIDNameDict["90-05"] = "線上申辦";
$subitemIDNameDict["90-99"] = "其他";
$subitemIDNameDict["69-99"] = "其他";
$subitemIDNameDict["91-01"] = "廣告信件";
$subitemIDNameDict["91-02"] = "登記後不予處理";
$subitemIDNameDict["91-03"] = "不實個資";




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
	padding: 0.4em 0.7em;
	font-size: 0.85em;
	margin-left:15px;
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
	<div style="margin: 15px auto 0px;width:1000px;text-align: center;">
		<div class="form-group row" style="margin-bottom: 0rem;">
			<label class="col-form-label col-form-label-lg col-sm-2 text-sm-right" style="text-align: left !important;max-width: 104px;"><b>選擇主項目</b></label>
			<div class="col-sm-10" style="font-size: 1rem;margin-top: 7px;">
				<select name="itemSelect" id="itemSelect" class="custom-select">
					<option value="所有">所有</option>
					<?php
					foreach ($itemIDNameDict as $key => $value) {
						echo '<option value="'.$key.'">'.$value.'</option>';
					}
					?>
				</select>
			</div>
		</div>
		
		
	</div>
    <div class="layout-wrapper layout-2">
	
        <div class="layout-inner">
            <!-- [ Layout sidenav ] Start -->
            <!-- [ Layout sidenav ] End -->
            <!-- [ Layout container ] Start -->
                <!-- [ Layout navbar ( Header ) ] End -->

                <!-- [ Layout content ] Start -->

                    
					    <!--<div class="row" style="margin: 20px auto;width: 950px;">-->
								<div class="card d-flex w-100 mb-4" style="width: 1000px !important;margin: 15px auto !important;">
									<table class="table table-hover card-table" id="data">
										<thead>
											<tr>
												<th>主項目</th>
												<th>子項目</th>
												<th>修改時間</th>
												<th>修改者</th>
											</tr>
										</thead>
										<tbody>
											<!--<td colspan='4' style='text-align: center;font-weight: bold;font-size: 16px;'>查無資料</td>-->
											<?php
											foreach (scandir("reply") as $item) {
												if (is_dir("reply/".$item)) {
													if($item!="."&&$item!=".."){
														$theitem = explode("-",$item)[0];
														//echo "目錄：$item\n";
														echo '<tr class="item_'.$theitem.' subitem_'.$item.' trList" id="trList_'.$item.'" onclick="itemClick(\''.$item.'\')" style="cursor:pointer;">';
														echo '	<td>'.$itemIDNameDict[$theitem].'</td>';
														echo '	<td>'.$subitemIDNameDict[$item].'</td>';
														$filePath = "reply/".$item.'/default.txt';
														$filetxt = "";
														if(filesize($filePath) > 0){
															$fp = fopen($filePath,'r');
															$filetxt = fread($fp, filesize($filePath));
															fclose($fp);
														}
														if(mb_strlen($filetxt,'utf-8')==0){
															echo '	<td id="modifytime_'.$item.'"><div class="badge badge-warning">尚未建置</div></td>';
															echo '	<td id="modifyuser_'.$item.'"></td>';
														}else{
															echo '	<td id="modifytime_'.$item.'">'.date("Y-m-d H:i:s",filemtime($filePath)).'</td>';
															echo '	<td id="modifyuser_'.$item.'">管理者</td>';
														}
														echo '</tr>';
														echo '<tr class="item_'.$theitem.' subitem_'.$item.' trTextarea" id="trTextarea_'.$item.'" style="display:none;">';
														echo '	<td colspan="4">';
														echo '		<textarea id="textarea_'.$item.'" class="form-control" onkeyup="textAreaAdjust(this)" placeholder="尚未建置，請輸入" style="width: 100%;font-size: 1rem;">'.$filetxt.'</textarea>';
														echo '		<div style="margin-top:15px; float:right;">';
														echo '			<button class="btn btn-info btnClear" id="btnClear_'.$item.'" style="margin-right: 8px;">清空</button>';
														echo '			<button class="btn btn-primary" id="btnSave_'.$item.'" style="margin-right: 8px;" onclick="itemSave(\''.$item.'\')">儲存</button>';
														echo '			<button class="btn btn-dark btnCopy" id="btnCopy_'.$item.'" style="margin-right: 8px;" >複製</button>';
														echo '			<button class="btn btn-secondary" id="btnClose_'.$item.'" onclick="itemClose(\''.$item.'\')">關閉</button>';
														echo '		</div>';
														echo '	</td>';
														echo '</tr>';
													}
												}
											}
											
											?>
										</tbody>
									</table>
								</div>
						<!--</div> -->
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
	
	//取得url參數
	var getUrlParameter = function getUrlParameter(sParam) {
		var sPageURL = window.location.search.substring(1),
			sURLVariables = sPageURL.split('&'),
			sParameterName,
			i;

		for (i = 0; i < sURLVariables.length; i++) {
			sParameterName = sURLVariables[i].split('=');

			if (sParameterName[0] === sParam) {
				return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
			}
		}
		return false;
	};

	//輸入自適應Textarea
	function textAreaAdjust(element) {
		element.style.height = "1px";
		element.style.height = (25+element.scrollHeight)+"px";
	}
	
	function itemClose(item){
		$("#trTextarea_"+item).hide();
	}
	function itemClick(item){
		
		//上背景顏色
		$('.trList').css("background","#fff");
		$('.trTextarea').css("background","#fff");
		$('.subitem_'+item).css("background","#cbe3ff40");
		
		//顯示Textarea
		$('.trTextarea').hide();
		$('.subitem_'+item).show();
		
		//自適應Textarea
		var element = document.getElementById('textarea_'+item);
		element.style.height = "1px";
		element.style.height = (25+element.scrollHeight)+"px";
		
		$(".trList").hover(function(){
			$(this).css("background-color","#f3f3f3");
		},function(){
			$(this).css("background-color","#fff");
		});
		
	}
	function itemSave(item){
		var today = new Date();
		var date = today.getFullYear() + '-' + (today.getMonth()+1) + '-' + today.getDate();
		var time = today.getHours().toString().padStart(2, '0') + ":" + today.getMinutes().toString().padStart(2, '0') + ":" + today.getSeconds().toString().padStart(2, '0');
		var date_time = date + ' ' + time;
		content = $("#textarea_"+item).val();
		console.log(item)
		console.log(content)
		$.post('function.php',{item:item,content:content},function(data){
			console.log(data)
			if(data=="OK"){
				$('#modifytime_'+item).html(date_time)
				$('#modifyuser_'+item).html("管理者")
				layer.open({title: '訊息',content: '儲存成功。',btn: ['確定']});
				
			}else{
				layer.open({title: '訊息',content: '儲存失敗! 請洽系統開發人員!!',btn: ['確定']});
			}
		});
	}
	$(document).ready(function() {
		
		//如果有指定subitem，則顯示
		getSubitem = getUrlParameter('subitem')
		if(getSubitem){
			if(getSubitem!=''){
				itemClick(getSubitem);
				var aTag = $("#trList_"+ getSubitem);
				$('html,body').animate({scrollTop: aTag.offset().top-100},'slow');
			}
		}
		//選擇主項目
		$('#itemSelect').on('change', function() {
			if(this.value=="所有"){
				$('.trList').show();
				$('.trTextarea').hide();
			}else{
				$('.trList').hide();
				$('.item_'+this.value).show();
				$('.trTextarea').hide();
			}
		});		
		//複製 - 回覆
		$(".btnCopy").click(function() {
			var ReplyIndex = $(this).attr("id");
			ReplyIndex = ReplyIndex.split('_')[1];
			console.log(ReplyIndex);
			$("#textarea_"+ReplyIndex).select();
			document.execCommand('copy');
			layer.msg('複製成功', {
			  time: 1000, //1s后自动关闭
			  //btn: ['明白了', '知道了', '哦']
			});
		});
		//清空 - 回覆
		$(".btnClear").click(function() {
			var ReplyIndex = $(this).attr("id");
			ReplyIndex = ReplyIndex.split('_')[1];
			console.log(ReplyIndex);
			$("#textarea_"+ReplyIndex).html("");
		});
	});
	</script>
	
</body>

</html>
