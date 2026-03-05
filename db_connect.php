<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
<?php
header("Content-Type:text/html; charset='utf-8'");
$host = "localhost";
$access = "bacon";
$password = "1234";
$con = @mysqli_connect($host , $access , $password);
mysqli_query($con, "SET CHARACTER SET 'utf8'");
mysqli_query($con , "SET NAMES 'utf8'"); 
if ($con == false){
	echo "資料庫連線失敗";
}
?>
</body>
</html>
<? mysqli_close($con); ?>