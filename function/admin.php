<?php
header("Content-Type: text/html; charset=utf-8"); //Hank 自編 《PHP專書P14-3》
require_once('../Connections/connection.php');
date_default_timezone_set("Etc/GMT-8");
if (!isset($_SESSION)) {session_start();}

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