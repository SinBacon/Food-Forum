<?php
	session_start();
	//引入連線檔案
	include("../db_connect.inc.php");

	if (isset($_SESSION['UserID'])){
		//所要選擇的「資料庫UserID」變數
		$database = "flavorfulsphere";
		//選擇資料庫，失敗的話則顯示「資料庫選擇失敗」
		$db_select = mysqli_select_db($con , $database) or die("資料庫選擇失敗");
		$UserID = $_SESSION['UserID'];
		$sql_str = "SELECT * FROM `users` WHERE `UserID` = '$UserID'";
		$res = mysqli_query($con , $sql_str) or die("SQL語法錯誤");
		$row = mysqli_fetch_assoc($res);
		$check = mysqli_num_rows($res);
		if ($check != 1){
			echo '<meta http-equiv=REFRESH CONTENT=0;url=../index.php>';
		}
	}

	//所要選擇的「資料庫名稱」變數
	$Postsbase = "flavorfulsphere";

	if (isset($_GET['post_id'])) {
		$PostID = $_GET['post_id'];
	} else {
		$PostID = 0;
	}

	//選擇資料庫，失敗的話則顯示「資料庫選擇失敗」
	$db_select = mysqli_select_db($con , $Postsbase) or die("資料庫選擇失敗");
	
	// 執行資料庫查詢，這裡假設有一個名為example_table的表格
	$sql = "
		SELECT `LikeID`, `PostID`, `UserID`, `Timestamp`, `hashtag` FROM `like` 
		WHERE `PostID`='$PostID' AND `UserID`='$UserID';
		";

	// 使用連接對象進行查詢
	$result = $con->query($sql);

	// 檢查是否有資料
	if ($result->num_rows >= 1) {
		
		$sql = "
		DELETE FROM `like`
		WHERE `PostID`='$PostID' AND `UserID`='$UserID';
		";
		$result = $con->query($sql);
		
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode(array("UserID" => $UserID, "PostID" => $PostID, "LikeState" => "F"));
	} else if ($result->num_rows == 0){
		
		$sql = "
		INSERT INTO `like`(`LikeID`, `PostID`, `UserID`, `Timestamp`, `hashtag`) 
			VALUES (NULL,'$PostID','$UserID',current_timestamp(),0);
		";
		$result = $con->query($sql);
		
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode(array("UserID" => $UserID, "PostID" => $PostID, "LikeState" => "T"));
	} else {
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode(array("UserID" => $UserID, "PostID" => $PostID, "LikeState" => "ERROR"));
	}

	// 關閉資料庫連接
	$con->close();
?>
