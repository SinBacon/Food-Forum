<?php
	//引入連線檔案
	include("../db_connect.inc.php");

	//所要選擇的「資料庫名稱」變數
	$Commentsbase = "flavorfulsphere";

	if (isset($_GET['comment_index'])) {
		$comment_index = $_GET['comment_index'];
	} else {
		$comment_index = 0;
	}
	if (isset($_GET['post_id'])) {
		$post_id = $_GET['post_id'];
	} else {
		$post_id = 0;
	}
	$per = 3;

	//選擇資料庫，失敗的話則顯示「資料庫選擇失敗」
	$db_select = mysqli_select_db($con , $Commentsbase) or die("資料庫選擇失敗");
	
	// 获取评论总数
	$count_query = "SELECT COUNT(`CommentID`) AS total_comments FROM `comment` WHERE `postID` = $post_id";
	$count_result = mysqli_query($con, $count_query);
	$row = mysqli_fetch_assoc($count_result);
	$total_comments = $row['total_comments'];

	// 计算有效的 comment_index 和 per
	if ($total_comments > 0) {
		$max_comment_index = $total_comments - $per;
		if (($comment_index+$per) >= $total_comments) {
			$per = $total_comments - $max_comment_index;
		}
	} 

	// 执行查询
	$sql = "
		SELECT comment.*, users.*
		FROM comment
		JOIN users ON comment.UserID = users.UserID
		WHERE comment.postID = $post_id
		GROUP BY comment.CommentID
		ORDER BY `comment`.`Timestamp` DESC
		LIMIT $comment_index, $per;
	";

	$result = mysqli_query($con, $sql);

	// 檢查是否有資料
	if ($result->num_rows > 0) {
		// 將查詢結果轉換為JSON格式
		$Comments = array();
		while($row = $result->fetch_assoc()) {
			$CommentID = $row['CommentID'];
			$row['CommentID'] = $CommentID;		
			$Comments[] = $row;
		}
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode(array("Comments" => $Comments, "comment_index" => $comment_index));
	} else {
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode(null);
	}
	// 關閉資料庫連接
	$con->close();
?>
