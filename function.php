<?php
if(isset($_POST['item'])){
	$fileSize = file_put_contents("reply/".$_POST['item']."/default.txt", $_POST['content']);
	echo "OK";
}
?>