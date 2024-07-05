<?php
header("Content-Type: text/html; charset=utf-8"); //Hank 自編 《PHP專書P14-3》
require_once('../Connections/connection.php');
date_default_timezone_set("Etc/GMT-8");
if (!isset($_SESSION)) {session_start();}

$title_len_limit = 25;
$text_len_limit = 35;

if(isset($_POST["action"])&&($_POST["action"]=="trend_vol")) {
	$sql = "SELECT * FROM (SELECT * FROM ".$_POST["database"].".scraperblogdata WHERE `view` != '' AND titlehavetarget = 'yes' UNION SELECT * FROM ".$_POST["database"].".scraperblogdata WHERE `view` = '' AND titlehavetarget = 'no') as result WHERE daytime >= '".$_POST["timestart"]."' AND daytime < '".$_POST["timeend"]."' ORDER BY daytime DESC";
	$result = mysqli_query($link,$sql);
	if($result){
		$return = array();
		while($RowRecord = mysqli_fetch_assoc($result)){ 
			$sql = "SELECT COUNT(*) FROM ".$_POST["database"].".scraperblogdata WHERE orisn = '".$RowRecord["sn"]."'";
			$query = mysqli_query($link,$sql);
			while($Row = mysqli_fetch_assoc($query)){ 
				$reply = $Row["COUNT(*)"];
			}
			$temp = array();
			$temp["title"] = $RowRecord["title"];
			$temp["text"] = $RowRecord["text"];
			$temp["url"] = $RowRecord["url"];
			$temp["domain"] = $RowRecord["domain"];
			$temp["authorid"] = $RowRecord["authorid"];
			$temp["view"] = $RowRecord["view"];
			if($RowRecord["view"]==""){$temp["view"] = "-";}
			$temp["daytime"] = $RowRecord["daytime"];
			$temp["reply"] = $reply;
			
			$title = $RowRecord['title'];
			if(mb_strlen( $title, "utf-8")>$title_len_limit){ $title = mb_substr( $title,0,$title_len_limit,"utf-8")."..."; }
			$text = $RowRecord['text'];
			$text = preg_replace('/\s+/', " ", $text);
			$text = preg_replace("/\t/"," ",$text);
			$text = preg_replace("/\r\n/"," ",$text); 
			$text = preg_replace("/\r/"," ",$text); 
			$text = preg_replace("/\n/"," ",$text);
			if(mb_strlen( $text, "utf-8")>$text_len_limit){ $text = mb_substr( $text,0,$text_len_limit,"utf-8")."..."; }
			$temp["title_short"] = $title;
			$temp["text_short"] = $text;
			
			array_push($return, $temp);
		}
		echo json_encode($return);
	}else{
		echo json_encode("Error: " . mysqli_error($link));
	}
	
}

if(isset($_POST["action"])&&($_POST["action"]=="trend_opt")) {
	$sql = "SELECT oriblogsn, opt, sen, word, type, title, domain, url, authorid, daytime, date_format(daytime, '%Y-%m-%d') as thedate FROM ".$_POST["database"].".analysisresult as A LEFT JOIN ".$_POST["database"].".scraperblogdata as B ON A.oriblogsn = B.sn WHERE daytime >= '".$_POST["timestart"]."' AND daytime < '".$_POST["timeend"]."' ORDER BY daytime DESC ";
	$result = mysqli_query($link,$sql);
	if($result){
		$return = array();
		while($RowRecord = mysqli_fetch_assoc($result)){ 
			$temp = array();
			$temp["title"] = $RowRecord["title"];
			$temp["sen"] = $RowRecord["sen"];
			$temp["url"] = $RowRecord["url"];
			$temp["domain"] = $RowRecord["domain"];
			$temp["authorid"] = $RowRecord["authorid"];
			$temp["daytime"] = $RowRecord["daytime"];
			$temp["type"] = $RowRecord["type"];
			$temp["opt"] = $RowRecord["opt"];
						
			$title = $RowRecord['title'];
			if(mb_strlen( $title, "utf-8")>$title_len_limit){ $title = mb_substr( $title,0,$title_len_limit,"utf-8")."..."; }
			$text = $RowRecord['sen'];
			$text = preg_replace('/\s+/', " ", $text);
			$text = preg_replace("/\t/"," ",$text);
			$text = preg_replace("/\r\n/"," ",$text); 
			$text = preg_replace("/\r/"," ",$text); 
			$text = preg_replace("/\n/"," ",$text);
			if(mb_strlen( $text, "utf-8")>$text_len_limit){ $text = mb_substr( $text,0,$text_len_limit,"utf-8")."..."; }
			$temp["title_short"] = $title;
			$temp["sen_short"] = $text;
			
			array_push($return, $temp);
		}
		echo json_encode($return);
	}else{
		echo json_encode("Error: " . mysqli_error($link));
	}
	
}

if(isset($_POST["action"])&&($_POST["action"]=="follow")) {
	
	$sql = "SELECT count(*) FROM ".$_POST["database"].".web_follow WHERE authorid = '".$_POST["authorid"]."'";
	$result = mysqli_query($link,$sql);
	if($result){
		while($RowRecord = mysqli_fetch_assoc($result)){ 
			if($_POST["act"]=="gofollow"){ //要做追蹤
				if($RowRecord["count(*)"]==0){ //本來就沒追蹤 >> 進行追蹤
					$sql = "INSERT INTO web_follow VALUES (NULL, '".$_POST["authorid"]."', '".date('Y-m-d H:i:s')."')";
					$query = mysqli_query($link,$sql);
					if($result){
						$return = "follow success";
					}else{
						$return = "Error: " . mysqli_error($link);
					}
				}else{ //已經有追蹤了 >> 不做動作
					$return = "follow success";
				}
			}else{ //取消追蹤		cancelfollow	
				if($RowRecord["count(*)"]==0){ //本來就沒追蹤 >> 不做動作
					$return = "cancel follow success";
				}else{ //已經有追蹤了 >> 取消追蹤
					$sql = "DELETE FROM web_follow WHERE authorid = '".$_POST["authorid"]."'";
					$query = mysqli_query($link,$sql);
					if($result){
						$return = "cancel follow success";
					}else{
						$return = "Error: " . mysqli_error($link);
					}
				}
			}
		echo json_encode($return);
		}
	}else{
		echo json_encode("Error: " . mysqli_error($link));
	}
}
if(isset($_POST["action"])&&($_POST["action"]=="wordpass")) {
	
	$sql = "SELECT count(*) FROM ".$_POST["database"].".web_hotspot_wordpass WHERE word = '".$_POST["word"]."'";
	$result = mysqli_query($link,$sql);
	if($result){
		while($RowRecord = mysqli_fetch_assoc($result)){ 
			if($_POST["act"]=="add"){ //要新增
				if($RowRecord["count(*)"]==0){ //本來就沒該詞 >> 新增
					$sql = "INSERT INTO web_hotspot_wordpass VALUES (NULL, '".$_POST["word"]."', '".date('Y-m-d H:i:s')."')";
					$query = mysqli_query($link,$sql);
					if($result){
						$return = "add success";
					}else{
						$return = "Error: " . mysqli_error($link);
					}
				}else{ //已經有該詞 >> 不做動作
					$return = "add success";
				}
			}else{ //取消該詞 del	
				if($RowRecord["count(*)"]==0){ //本來就沒該詞 >> 不做動作
					$return = "del word success";
				}else{ //已經有該詞 >> 刪除該詞
					$sql = "DELETE FROM web_hotspot_wordpass WHERE word = '".$_POST["word"]."'";
					$query = mysqli_query($link,$sql);
					if($result){
						$return = "del word success";
					}else{
						$return = "Error: " . mysqli_error($link);
					}
				}
			}
		echo json_encode($return);
		}
	}else{
		echo json_encode("Error: " . mysqli_error($link));
	}
}






//------------ 以下要刪除，僅拿來參考copy利用
if(isset($_POST["action"])&&($_POST["action"]=="suggest_insert_sentiword")) {
	
	$sql = "INSERT INTO ".$_POST["database"].".sentiword(sn,word,opt,ditypename) VALUES (NULL, '".$_POST["word"]."' ,".$_POST["opt"].",  '".$_POST["type"]."')";
	$result = mysqli_query($link,$sql);
	if($result){
		$sql = "DELETE FROM ".$_POST["database"].".sentiword_suggest WHERE sn = '".$_POST["sn"]."'";
		mysqli_query($link,$sql);
		echo("OK");
	}else{
		echo("Error: " . mysqli_error($link));
	}
	
}
if(isset($_POST["action"])&&($_POST["action"]=="suggest_del_sentiword")) {
	
	$sql = "DELETE FROM ".$_POST["database"].".sentiword_suggest WHERE sn = '".$_POST["sn"]."'";
	mysqli_query($link,$sql);
	echo("OK");
			
}
if(isset($_POST["action"])&&($_POST["action"]=="sentiword_update_word")) {
	
	$sql = "UPDATE ".$_POST["database"].".sentiword SET opt = '".$_POST["opt"]."', ditypename = '".$_POST["type"]."' WHERE word = '".$_POST["word"]."'";
	mysqli_query($link,$sql);
	echo("OK");
			
}
if(isset($_POST["action"])&&($_POST["action"]=="suggest_del_to_reject")) {
	
	$sql = "SELECT * FROM ".$_POST["database"].".sentiword_suggest WHERE sn = '".$_POST["sn"]."'";
	$result = mysqli_query($link,$sql);
	while($RowRecord = mysqli_fetch_assoc($result)){ 
		$sql = "INSERT INTO ".$_POST["database"].".sentiword_reject(sn,word,content) VALUES (NULL, '".$RowRecord["word"]."', '".$RowRecord["content"]."')";
		mysqli_query($link,$sql);
	}
	
	$sql = "DELETE FROM ".$_POST["database"].".sentiword_suggest WHERE sn = '".$_POST["sn"]."'";
	mysqli_query($link,$sql);
	echo("OK");
			
}
if(isset($_POST["action"])&&($_POST["action"]=="sentiword_update")) {
	
	$sql = "SELECT * FROM ".$_POST["database"].".sentiword WHERE sn != '".$_POST["sn"]."' AND word = '".$_POST["word"]."'";
	$result = mysqli_query($link,$sql);
	$numrows = mysqli_num_rows($result);
	if($numrows>0){ //重複
		echo "重複";
	}else{ //沒有重複: 可更新
		$sql = "UPDATE ".$_POST["database"].".sentiword SET opt = '".$_POST["opt"]."', ditypename = '".$_POST["type"]."', word = '".$_POST["word"]."' WHERE sn = '".$_POST["sn"]."'";
		mysqli_query($link,$sql);
		echo("OK");
	}
	
}
if(isset($_POST["action"])&&($_POST["action"]=="sentiword_del")) {
	
	$sql = "DELETE FROM ".$_POST["database"].".sentiword WHERE sn = '".$_POST["sn"]."'";
	mysqli_query($link,$sql);
	echo("OK");
	
}
if(isset($_POST["action"])&&($_POST["action"]=="sentiword_insert")) {
	
	$sql = "SELECT * FROM ".$_POST["database"].".sentiword WHERE word = '".$_POST["word"]."'";
	$result = mysqli_query($link,$sql);
	$numrows = mysqli_num_rows($result);
	if($numrows>0){ //這個詞已經有了
		echo "已有";
	}else{ //該詞沒有: 可新增
		$sql = "INSERT INTO ".$_POST["database"].".sentiword(sn,word,opt,ditypename) VALUES (NULL, '".$_POST["word"]."' ,".$_POST["opt"].",  '".$_POST["type"]."')";
		mysqli_query($link,$sql);
		echo("OK");
	}
	
}
if(isset($_POST["action"])&&($_POST["action"]=="pos_term_insert")) {
	
	$sql = "SELECT * FROM ".$_POST["database"].".pos_term WHERE word = '".$_POST["word"]."'";
	$result = mysqli_query($link,$sql);
	$numrows = mysqli_num_rows($result);
	if($numrows>0){ //這個詞已經有了
		echo "已有";
	}else{ //該詞沒有: 可新增
		$sql = "INSERT INTO ".$_POST["database"].".pos_term(sn,word,type,timecreated) VALUES (NULL, '".$_POST["word"]."' ,'".$_POST["type"]."',  '".date('Y-m-d H:i:s')."')";
		mysqli_query($link,$sql);
		echo("OK");
	}
	
}
if(isset($_POST["action"])&&($_POST["action"]=="pos_term_del")) {
	
	$sql = "DELETE FROM ".$_POST["database"].".pos_term WHERE sn = '".$_POST["sn"]."'";
	mysqli_query($link,$sql);
	echo("OK");
	
}
if(isset($_POST["action"])&&($_POST["action"]=="pos_term_update")) {
	
	$sql = "SELECT * FROM ".$_POST["database"].".pos_term WHERE sn != '".$_POST["sn"]."' AND word = '".$_POST["word"]."'";
	$result = mysqli_query($link,$sql);
	$numrows = mysqli_num_rows($result);
	if($numrows>0){ //重複
		echo "重複";
	}else{ //沒有重複: 可更新
		$sql = "UPDATE ".$_POST["database"].".pos_term SET type = '".$_POST["type"]."', word = '".$_POST["word"]."', timecreated = '".date('Y-m-d H:i:s')."' WHERE sn = '".$_POST["sn"]."'";
		mysqli_query($link,$sql);
		echo("OK");
	}
	
}
if(isset($_POST["action"])&&($_POST["action"]=="pos_termadv_insert")) {
	
	$sql = "SELECT * FROM ".$_POST["database"].".pos_termadv WHERE word = '".$_POST["word"]."'";
	$result = mysqli_query($link,$sql);
	$numrows = mysqli_num_rows($result);
	if($numrows>0){ //這個詞已經有了
		echo "已有";
	}else{ //該詞沒有: 可新增
		$sql = "INSERT INTO ".$_POST["database"].".pos_termadv(sn,word,timecreated) VALUES (NULL, '".$_POST["word"]."', '".date('Y-m-d H:i:s')."')";
		mysqli_query($link,$sql);
		$sql = "SELECT * FROM ".$_POST["database"].".pos_termadv WHERE word = '".$_POST["word"]."'";
		$result = mysqli_query($link,$sql);
		while($RowRecord = mysqli_fetch_assoc($result)){ 
			$sql = "INSERT INTO ".$_POST["database"].".pos_termadv_suffix(sn,oriterm,newterm,orisn) VALUES (NULL, '".$_POST["word"]."(ADV)', '".$_POST["word"]."(ADV1)', '".$RowRecord['sn']."')";
			mysqli_query($link,$sql);
			echo("OK");
		}
	}
	
}
if(isset($_POST["action"])&&($_POST["action"]=="pos_termadv_del")) {
	
	$sql = "DELETE FROM ".$_POST["database"].".pos_termadv WHERE sn = '".$_POST["sn"]."'";
	mysqli_query($link,$sql);
	$sql = "DELETE FROM ".$_POST["database"].".pos_termadv_suffix WHERE orisn = '".$_POST["sn"]."'";
	mysqli_query($link,$sql);
	echo("OK");
	
}
if(isset($_POST["action"])&&($_POST["action"]=="pos_termadv_update")) {
	
	$sql = "SELECT * FROM ".$_POST["database"].".pos_termadv WHERE sn != '".$_POST["sn"]."' AND word = '".$_POST["word"]."'";
	$result = mysqli_query($link,$sql);
	$numrows = mysqli_num_rows($result);
	if($numrows>0){ //重複
		echo "重複";
	}else{ //沒有重複: 可更新
		$sql = "UPDATE ".$_POST["database"].".pos_termadv SET word = '".$_POST["word"]."', timecreated = '".date('Y-m-d H:i:s')."'  WHERE sn = '".$_POST["sn"]."'";
		mysqli_query($link,$sql);
		$sql = "UPDATE ".$_POST["database"].".pos_termadv_suffix SET oriterm = '".$_POST["word"]."(ADV)', newterm = '".$_POST["word"]."(ADV1)' WHERE orisn = '".$_POST["sn"]."'";
		mysqli_query($link,$sql);
		echo("OK");
	}
	
}
if(isset($_POST["action"])&&($_POST["action"]=="subjectword_insert")) {
	
	$sql = "SELECT * FROM ".$_POST["database"].".subjectword WHERE word = '".$_POST["word"]."'";
	$result = mysqli_query($link,$sql);
	$numrows = mysqli_num_rows($result);
	if($numrows>0){ //這個詞已經有了
		echo "已有";
	}else{ //該詞沒有: 可新增
		$sql = "INSERT INTO ".$_POST["database"].".subjectword(sn,word,timecreated) VALUES (NULL, '".$_POST["word"]."',  '".date('Y-m-d H:i:s')."')";
		mysqli_query($link,$sql);
		echo("OK");
	}
	
}
if(isset($_POST["action"])&&($_POST["action"]=="subjectword_del")) {
	
	$sql = "DELETE FROM ".$_POST["database"].".subjectword WHERE sn = '".$_POST["sn"]."'";
	mysqli_query($link,$sql);
	echo("OK");
	
}
if(isset($_POST["action"])&&($_POST["action"]=="subjectword_update")) {
	
	$sql = "SELECT * FROM ".$_POST["database"].".subjectword WHERE sn != '".$_POST["sn"]."' AND word = '".$_POST["word"]."'";
	$result = mysqli_query($link,$sql);
	$numrows = mysqli_num_rows($result);
	if($numrows>0){ //重複
		echo "重複";
	}else{ //沒有重複: 可更新
		$sql = "UPDATE ".$_POST["database"].".subjectword SET word = '".$_POST["word"]."', timecreated = '".date('Y-m-d H:i:s')."'  WHERE sn = '".$_POST["sn"]."'";
		mysqli_query($link,$sql);
		echo("OK");
	}
	
}
?>