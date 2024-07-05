<?php
header("Content-Type: text/html; charset=utf-8"); //Hank 自編 《PHP專書P14-3》
date_default_timezone_set("Etc/GMT-8");
set_time_limit(0);

$filename = "ini.txt";    $fp = fopen($filename, "r");   $path_python = fread($fp, filesize($filename));   fclose($fp);  
//$path_python = 'C:/Users/user/AppData/Local/Programs/Python/Python36/python.exe';
$input = "北區公園北路5號附近，涼亭角落的地方時常有一位遊民在那邊當自己的家，遊民在那邊喝酒還會把玻璃酒瓶打碎在步道上，恐容易造成他人危險，遊民還會把自己的棉被堵在小運河造成堵塞，通報員警前往也沒有用，一直浪費資源，請局處強制安置遊民，也請工務局把涼亭的石桌石椅拆除不要讓遊民在那邊喝酒，煩請相關單位卓處 承辦可聯繫民眾，並請結案時將處理結果電話回覆民眾";
#$input = "北區公園北路5號附近，煩請相關單位卓處 承辦可聯繫民眾，並請結案時將處理結果電話回覆民眾";

//==== 情緒分析
$command = $path_python.' '.__DIR__.'/analysis/task2_emotion/emotion.py '.$input. ' 2>error_ana_emotion.txt';
echo $command;
echo "<br/><br/>";
$output = exec($command, $output2,$res);
$output = mb_convert_encoding($output, 'UTF-8', "BIG5");
echo $output;
echo "<br/>";
echo '外部程序運行是否成功:'.$res.'(0代表成功,1代表失敗)';
echo "<br/><br/><br/>";

//==== 重要性分析
$command = $path_python.' '.__DIR__.'/analysis/task2_importance/importance.py '.$input. ' 2>error_ana_importance.txt';
echo $command;
echo "<br/><br/>";
$output = exec($command, $output2,$res);
$output = mb_convert_encoding($output, 'UTF-8', "BIG5");
echo $output;
echo "<br/>";
echo '外部程序運行是否成功:'.$res.'(0代表成功,1代表失敗)';
echo "<br/><br/><br/>";

//==== 科室分類
$command = $path_python.' '.__DIR__."/analysis/task1_department/bert.py --Subject '".$input. "' 2>error_ana_department.txt";
echo $command;
echo "<br/><br/>";
$output = exec($command, $output2,$res);
$output = mb_convert_encoding($output, 'UTF-8', "BIG5");
echo $output;
echo "<br/>";
echo '外部程序運行是否成功:'.$res.'(0代表成功,1代表失敗)';
echo "<br/><br/><br/>";

//==== 預擬回覆
$command = $path_python.' '.__DIR__.'/analysis/task3_reply/testModel.py '.$input. ' 2>error_ana_reply.txt';
echo $command;
echo "<br/><br/>";
$output = exec($command, $output2,$res);
$output = mb_convert_encoding($output, 'UTF-8', "BIG5");
echo $output;
echo "<br/>";
echo '外部程序運行是否成功:'.$res.'(0代表成功,1代表失敗)';
echo "<br/><br/><br/>";

//==== 測試
$command = $path_python.' '.__DIR__.'/analysis/task2_emotion/test.py '.$input. ' 2>error_ana_test.txt';
echo $command;
echo "<br/><br/>";
$output = exec($command, $output2,$res);
$output = mb_convert_encoding($output, 'UTF-8', "BIG5");
echo $output;
echo "<br/>";
 
echo '外部程序運行是否成功:'.$res.'(0代表成功,1代表失敗)';
echo "<br/><br/><br/>";
?>