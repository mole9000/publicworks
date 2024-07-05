<?php
header("Content-Type: text/html; charset=utf-8"); //Hank 自編 《PHP專書P14-3》
date_default_timezone_set("Etc/GMT-8");

$filename = "ini.txt";    
$fp = fopen($filename, "r");   
$PythonLoc = fread($fp, filesize($filename));
echo "<pre>$PythonLoc</pre>"; 
fclose($fp);   
?>