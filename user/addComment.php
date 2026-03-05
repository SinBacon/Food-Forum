<?php
	//引入連線檔案
	include("../db_connect.inc.php");

	//所要選擇的「資料庫名稱」變數
	$Commentsbase = "flavorfulsphere";

	$comment = $_POST['input_text'];
	$post_id = $_POST['post_id'];
	$user_id = $_POST['user_id'];
	
	if (!empty(trim($comment))) {
		if (!isset($inputTextValue, $post_id, $user_id)) {
			echo '<meta http-equiv=REFRESH CONTENT=0;url=../index.php>';
		}
		
		//選擇資料庫，失敗的話則顯示「資料庫選擇失敗」
		$db_select = mysqli_select_db($con , $Commentsbase) or die("資料庫選擇失敗");
		// 執行資料庫查詢，這裡假設有一個名為example_table的表格
		$sql = "
		INSERT INTO `comment`(`CommentID`, `PostID`, `UserID`, `Content`, `Timestamp`) VALUES (NULL,'$post_id','$user_id','$comment',current_timestamp())
		;
		";
		$result = $con->query($sql);
		
		if ($result === TRUE) {
			$sql_str = "
				SELECT COUNT(`CommentID`) AS `TotalComments`
				FROM `comment`
				WHERE `PostID` = '$post_id'
				;";
				$res = mysqli_query($con , $sql_str) or die("SQL語法錯誤");
				$row = mysqli_fetch_assoc($res);
	
			$TotalComments = $row['TotalComments'];
			header('Content-Type: application/json; charset=utf-8');
			echo json_encode(array("TotalComments" => $TotalComments));
		} else {
			$conn->error;
			header('Content-Type: application/json; charset=utf-8');
			echo json_encode(array("TotalComments" => $conn));
		}
		
		$con->close();
	}
?>
