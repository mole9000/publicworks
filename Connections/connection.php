<?php
header("Content-Type: text/html; charset=utf-8"); //Hank 自編 《PHP專書P14-3》
set_time_limit(0); //載入時間無上限 http://www.dotblogs.com.tw/jhsiao/archive/2014/11/30/147481.asp

$serve = 'localhost:3306';
$username = 'root';
$password = 'tncvs713041';
$dbname = 'test'; 
$link = mysqli_connect($serve,$username,$password,$dbname);
mysqli_set_charset($link,'UTF-8'); // 設定資料庫字符集
mysqli_query($link,"set names 'utf8'");




?>