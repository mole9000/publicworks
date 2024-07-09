<?php
header("Content-Type: text/html; charset=utf-8"); //Hank 自編 《PHP專書P14-3》
date_default_timezone_set("Etc/GMT-8");
set_time_limit(0);
$filename = "ini.txt";    $fp = fopen($filename, "r");   $path_python = fread($fp, filesize($filename));   fclose($fp);
//$path_python = 'C:/Users/user/AppData/Local/Programs/Python/Python36/python.exe';

if(isset($_POST['contenttext'])){
  $input = $_POST['contenttext'];
  $input = preg_replace('/\s+/', '，', $input); 
  
  // 設置Python執行器的路徑
  $path_python = '/usr/bin/python3';

  // 確保輸入已被正確轉義
  $input_escaped = escapeshellarg($input);
  
  //==== 情緒分析
  $command = $path_python.' '.__DIR__.'/analysis/task2_emotion/emotion.py '.$input_escaped.' 2>/tmp/error_ana_emotion.txt';
  $output = exec($command, $output2, $res);
  $output = mb_convert_encoding($output, 'UTF-8', "BIG5");
  //echo $output.'<br/>外部程序運行是否成功:'.$res.'(0代表成功,1代表失敗)<br/><br/><br/>';
  $output_emotion = (float)$output; // 範圍-1~+1: 小於0負面 大於0正面
  $state_emotion = $res;
  
  //==== 重要性分析
  $command = $path_python.' '.__DIR__.'/analysis/task2_importance/importance.py '.$input_escaped.' 2>/tmp/error_ana_importance.txt';
  $output = exec($command, $output2, $res);
  //$output = mb_convert_encoding($output, 'UTF-8', "BIG5");
  //echo $output.'<br/>外部程序運行是否成功:'.$res.'(0代表成功,1代表失敗)<br/><br/><br/>';
  $output_importance = (string)$output; // 危急案件 or 一般案件
  $state_importance = $res;
  
  //==== 科室分類
  $command = $path_python.' '.__DIR__."/analysis/task1_department/bert.py --Subject ".$input_escaped." 2>/tmp/error_ana_department.txt";
  $output = exec($command, $output2, $res);
  $output = mb_convert_encoding($output, 'UTF-8', "BIG5");
  echo $output.'<br/>外部程序運行是否成功:'.$res.'(0代表成功,1代表失敗)<br/><br/><br/>';
  if ($res !== 0) {
    echo "========================depart=========================\n"
    echo "Command failed with status code: $res\n";
    foreach ($output2 as $line) {
        echo "$line\n";
    }
    echo "========================depart=========================\n"
  }	
  $output_department = $output; // 如上陣列
  $state_department = $res;
  
  //==== 預擬回覆
  $command = $path_python.' '.__DIR__.'/analysis/task3_reply/testModel.py '.$input_escaped.' 2>/tmp/error_ana_reply.txt';
  $output = exec($command, $output2, $res);
  $output = mb_convert_encoding($output, 'UTF-8', "BIG5");
  //echo $output.'<br/>外部程序運行是否成功:'.$res.'(0代表成功,1代表失敗)<br/><br/><br/>';
  $output_reply = (string)$output; // 如上陣列
  $state_reply = $res;
}

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
                    <li class="sidenav-item active">
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
                    <li class="sidenav-item">
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
                        <h4 class="font-weight-bold py-3 mb-0">綜合分析</h4>
                        <div class="text-muted small mt-0 mb-4 d-block breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#">首頁</a></li>
                                <li class="breadcrumb-item active">綜合分析</li>
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
                                                // 確認 $output_department 是否已定義並且是字串，如果是，則轉換為陣列
                                                if (isset($output_department) && is_string($output_department)) {
                                                    $output_department = trim($output_department, "[]'");
                                                    $output_department = explode(',', $output_department);
                                                }
                                                // 確認類型和內容
												if(!isset($output_department)){
													echo '<span>尚無分析結果</span>';
												}else{
													if(count($output_department)==0){
														echo '<span>尚無法判別，請再右側進行反饋</span>';
													}else{
                                                        if (is_array($output_department)){
                                                            foreach ($output_department as $key => $value) {
                                                                $value = str_replace("'", "", $value);
															    echo '<button type="button" class="ResultClassifier btn btn-info">'.$value.'</button>';
														    }
                                                        }
														
													}
												}
												?>
                                            </div>
                                        </div>
                                        <div class="col-xl-12 p-4">
                                            <span style="font-size: 1rem;"><b>重要性</b></span>
											<button type="button" class="btn btn-sm btn-outline-warning" style="float:right;" onclick="showFeedback('feedback_importance.php')"><span class="far fa-paper-plane"></span>&nbsp;&nbsp;反饋</button>
                                            <hr>
                                            <div class="demo-inline-spacing mt-3">
                                                
                                                
												<?php 
													//重要性分析
													if(!isset($output_importance)||!isset($output_emotion)){
														echo '<span>尚無分析結果</span>';
													}else{
														if (strpos($output_importance, "危急") !== false) {
															echo '<button type="button" class="ResultImportance btn btn-round btn-danger">危急</button>';
														}else{
															echo '<button type="button" class="ResultImportance btn btn-round btn-secondary">一般</button>';
														}
														//情緒分析
														if($output_emotion < 0){ // 範圍-1~+1: 小於0負面 大於0正面
															echo '<button type="button" class="ResultImportance btn btn-round btn-warning">情緒</button>';
														}
													}
												?>
                                                <!-- <button type="button" class="ResultImportance btn btn-round btn-secondary">　重複　</button> --> <!--這個沒有了-->
                                            </div>
                                        </div>
                                        <div class="col-xl-12 p-4">
                                            <span style="font-size: 1rem;"><b>預擬回覆</b></span>
											<button type="button" class="btn btn-sm btn-outline-warning" style="float:right;" onclick="showFeedback('feedback_reply.php')"><span class="far fa-paper-plane"></span>&nbsp;&nbsp;反饋</button>
                                            <hr>
                                            <div class="demo-inline-spacing mt-3">

<?php
if(!isset($output_reply)){
	echo '<span style="font-size: 0.92rem;">尚無分析結果</span>';
}else{
    #print_r($output_reply);
    $output_reply = explode(',', $output_reply);
	if(count($output_reply)==0){
		echo '<span style="font-size: 0.92rem;">尚無法判別，請再右側進行反饋</span>';
	}else{
		foreach ($output_reply as $key => $value) {
			// $classidArr = explode("_",$value);
            // print_r($classidArr);
            // echo "<br>";
			// $itemStr = (int)$classidArr[0];
            // print_r($itemStr);
            // echo "<br>";
			// $subitemStr = (int)$classidArr[1];
			// $itemStr = str_pad($itemStr,2,'0',STR_PAD_LEFT);//將數字由左邊補零至兩位數
			// $subitemStr = str_pad($subitemStr,2,'0',STR_PAD_LEFT);
			// $classid = (string)$itemStr."-".(string)$subitemStr;
            // print_r($value);
            // echo "<br>";
			// print_r($classid);
            // echo "<br>";
			// if($key == 0){
			// echo '<button type="button" id="btnReply_'.$classid.'" class="ResultReply btnReply btn btn-dark">　'.$subitemIDNameDict[$classid].'　</button>';
			// }else{
			// echo '<button type="button" id="btnReply_'.$classid.'" class="ResultReply btnReply btn btn-outline-dark">　'.$subitemIDNameDict[$classid].'　</button>';
			// }
            // 去除可能存在的引號
            $value = trim($value, "[]'");
            $value = str_replace("'", "", $value);

            // 分割字符串
            $classidArr = explode("_", $value);

            // 確保分割後有兩個部分
            if (count($classidArr) == 2) {
                $itemStr = (int)$classidArr[0];
                $subitemStr = (int)$classidArr[1];
                $itemStr = str_pad($itemStr, 2, '0', STR_PAD_LEFT); // 將數字由左邊補零至兩位數
                $subitemStr = str_pad($subitemStr, 2, '0', STR_PAD_LEFT);
                $classid = (string)$itemStr . "-" . (string)$subitemStr;
                if ($key == 0) {
                    echo '<button type="button" id="btnReply_' . $classid . '" class="ResultReply btnReply btn btn-dark">' . $subitemIDNameDict[$classid] . '</button>';
                } else {
                    echo '<button type="button" id="btnReply_' . $classid . '" class="ResultReply btnReply btn btn-outline-dark">' . $subitemIDNameDict[$classid] . '</button>';
                }
            } else {
                echo "分割後的陣列元素數量不正確";
            }
		}
	}
}
?>
<!--
                                                <button type="button" id="btnReply_01-01" class="ResultReply btnReply btn btn-dark">　路燈故障不亮　</button>
                                                <button type="button" id="btnReply_02-01" class="ResultReply btnReply btn btn-outline-dark">　路燈損毀、傾倒　</button>
                                                <button type="button" id="btnReply_02-06" class="ResultReply btnReply btn btn-outline-dark">　9盞以下路燈故障　</button>
-->
                                                <button type="button" class="btn btn-secondary" style="float:right;" onclick="showReplyManage('')">　其他公版回覆　</button>
                                            </div>
											<div class="col-sm-12" style="padding-left:0px;padding-right:0.375rem;">
<style>
textarea.textareaReply {
	min-height: 60px;
	overflow-y: auto;
	word-wrap:break-word
}
</style>
<script>
function textAreaAdjust(element) {
  element.style.height = "1px";
  element.style.height = (25+element.scrollHeight)+"px";
}
</script>
<?php
if(!isset($output_reply)){
	echo '';
}else{
	if(count($output_reply)==0){
		echo '';
	}else{
		foreach ($output_reply as $key => $value) {
            $value = trim($value, "[]'");
            $value = str_replace("'", "", $value);
			$classidArr = explode("_",$value);
			$itemStr = (int)$classidArr[0];  //61
			$subitemStr = (int)$classidArr[1]; // 5 or 6
			$itemStr = str_pad($itemStr,2,'0',STR_PAD_LEFT);//將數字由左邊補零至兩位數
			$subitemStr = str_pad($subitemStr,2,'0',STR_PAD_LEFT);
			$classid = (string)$itemStr."-".(string)$subitemStr;
			
			$filePath = "reply/".$classid.'/default.txt';
			$filetxt = "";
			if(filesize($filePath) > 0){
				$fp = fopen($filePath,'r');
				$filetxt = fread($fp, filesize($filePath));
				fclose($fp);
			}
			if($key == 0){
			echo '<div id="divReply_'.$classid.'" class="divReply">';
			}else{
			echo '<div id="divReply_'.$classid.'" class="divReply" style="display:none;">';
			}
			echo '	<textarea class="form-control textareaReply" id="textareaReply_'.$classid.'" placeholder="尚未建置內容" readonly="readonly" onclick="textAreaAdjust(this)" style="width: calc(100% - 150px);font-size: 1rem;overflow:hidden">'.$filetxt.'</textarea>';
			echo '	<button class="btnManageReply btn btn-secondary" id="btnManageReply_'.$classid.'" onclick="showReplyManage(\''.$classid.'\')" style="float: right;margin-top: -37px;">管理</button>';
			echo '	<button class="textareaCopy btn btn-primary" id="btnCopyReply_'.$classid.'" style="float: right;margin-top: -37px;margin-right:10px;">複製</button>';
			echo '</div>';
		}
	}
}
?>
<!--
<div id="divReply_01-01" class="divReply">
	<textarea class="form-control textareaReply" id="textareaReply_01-01" placeholder="尚未建置內容" readonly="readonly" onclick="textAreaAdjust(this)" style="width: calc(100% - 150px);font-size: 1rem;overflow:hidden">市民朋友您好: 有關於您反映的建議事項，茲將查處結果敬復如下： 6月13日已通知保固商前往處理 倘您對本案處理結果還有任何疑問，可與本府工務局公園管理科一股   承辦人陳隆銘、陳啟東  電話2991111-8800      聯繫，市府同仁將會詳細為您說明。再次誠摯地感謝您的支持與關心，如有需本府服務之處，尚祈不吝指教。感謝您的來函  敬祝您  身體健康、萬事如意  臺南市政府工務局局長蘇金安  敬上  </textarea>
	<button class="btnManageReply btn btn-secondary" id="btnManageReply_01-01" onclick="showReplyManage('01-01')" style="float: right;margin-top: -37px;">管理</button>
	<button class="textareaCopy btn btn-primary" id="btnCopyReply_01-01" style="float: right;margin-top: -37px;margin-right:10px;">複製</button>
</div>
<div id="divReply_02-01" class="divReply" style="display:none;">
	<textarea class="form-control textareaReply" id="textareaReply_02-01" placeholder="尚未建檔內容" readonly="readonly" onclick="textAreaAdjust(this)" style="width: calc(100% - 150px);font-size: 1rem;overflow:hidden">02-01回覆公版</textarea>
	<button class="btnManageReply btn btn-secondary" id="btnManageReply_01-01" onclick="showReplyManage('02-01')" style="float: right;margin-top: -37px;">管理</button>
	<button class="textareaCopy btn btn-primary" id="btnCopyReply_02-01" style="float: right;margin-top: -37px;margin-right:10px;">複製</button>
</div>
<div id="divReply_02-06" class="divReply" style="display:none;">
	<textarea class="form-control textareaReply" id="textareaReply_02-06" placeholder="尚未建檔內容" readonly="readonly" onclick="textAreaAdjust(this)" style="width: calc(100% - 150px);font-size: 1rem;overflow:hidden">02-06回覆公版</textarea>
	<button class="btnManageReply btn btn-secondary" id="btnManageReply_01-01" onclick="showReplyManage('02-06')" style="float: right;margin-top: -37px;">管理</button>
	<button class="textareaCopy btn btn-primary" id="btnCopyReply_02-06" style="float: right;margin-top: -37px;margin-right:10px;">複製</button>
</div>
-->
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
	<script>
	
	$(document).ready(function() {
		
		//點擊呈現 - 預擬回覆
		$(".btnReply").click(function() {
			$(".btnReply").removeClass('btn-dark');
			$(".btnReply").addClass('btn-outline-dark');
			$(this).toggleClass('btn-outline-dark btn-dark');
			
			var ReplyIndex = $(this).attr("id");
			ReplyIndex = ReplyIndex.split('_')[1];
			//console.log(ReplyIndex);
			
			$('.divReply').hide();
			$('#divReply_'+ReplyIndex).fadeTo("300",1);
		});
		$(".textareaReply").click()
		
		//複製 - 預擬回覆
		$(".textareaCopy").click(function() {
			var ReplyIndex = $(this).attr("id");
			ReplyIndex = ReplyIndex.split('_')[1];
			console.log(ReplyIndex);
			$("#textareaReply_"+ReplyIndex).select();
			document.execCommand('copy');
			layer.msg('複製成功', {
			  time: 1000, //1s后自动关闭
			  //btn: ['明白了', '知道了', '哦']
			});
		});
	});
	</script>
</body>

</html>
