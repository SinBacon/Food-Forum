<?php

	header("Content-Type:text/html; charset='utf-8'");
	
	//引入連線檔案
	include("../db_connect.inc.php");

	//所要選擇的「資料庫名稱」變數
	$database = "flavorfulsphere";

	//選擇資料庫，失敗的話則顯示「資料庫選擇失敗」
	$db_select = mysqli_select_db($con , $database) or die("資料庫選擇失敗");

	$userID = $_POST['userID'];
	$password = $_POST['passwd'];
	$email = "$userID@gmail.com";
	$sql_str = "INSERT INTO `users`(`UserID`, `Username`, `Email`, `Password`) VALUES ('$userID','$userID','$email','$password')";
	$res = mysqli_query($con , $sql_str) or die("SQL語法錯誤");

	if ($res === TRUE) {
		echo '<meta http-equiv=REFRESH CONTENT=0;url=../login/login.php>';
	} else {
		echo "註冊失敗:";
	}
	
	mysqli_close($con);
?>