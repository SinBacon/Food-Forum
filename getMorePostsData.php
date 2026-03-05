<?php
	session_start();
	//引入連線檔案
	include("db_connect.inc.php");

	//所要選擇的「資料庫名稱」變數
	$Postsbase = "flavorfulsphere";

	if (isset($_GET['post_index'])) {
		$post_index = $_GET['post_index'];
	} else {
		$post_index = 0;
	}
	$per = 10;

	//選擇資料庫，失敗的話則顯示「資料庫選擇失敗」
	$db_select = mysqli_select_db($con , $Postsbase) or die("資料庫選擇失敗");
	// 執行資料庫查詢，這裡假設有一個名為example_table的表格
	$sql = "
    SELECT 
        post.*, 
        COUNT(like.LikeID) AS LikeCount, 
        users.*, 
        CASE 
            WHEN TIMESTAMPDIFF(DAY, post.Timestamp, NOW()) = 0 THEN
                CASE 
                    WHEN TIMESTAMPDIFF(HOUR, post.Timestamp, NOW()) = 0 THEN
                        CONCAT(TIMESTAMPDIFF(MINUTE, post.Timestamp, NOW()), ' 分鐘前')
                    ELSE
                        CONCAT(TIMESTAMPDIFF(HOUR, post.Timestamp, NOW()), ' 小時前')
                END
            ELSE
                post.Timestamp
        END AS FormattedTimestamp
    FROM 
        post
    LEFT JOIN 
        `like` ON post.PostID = like.PostID
    LEFT JOIN 
        `users` ON post.UserID = users.UserID
    GROUP BY 
        post.PostID
    ORDER BY 
        post.`Timestamp` DESC
    LIMIT 
        $post_index, $per;
	";

	// 使用連接對象進行查詢
	$result = $con->query($sql);

	// 檢查是否有資料
	if ($result->num_rows > 0) {
		// 將查詢結果轉換為JSON格式
		$Posts = array();
		while($row = $result->fetch_assoc()) {
			
			$PostID = $row['PostID'];
			$Username = $row['Username'];
			$LikeCount = $row['LikeCount'];
			
			$Posts[] = $row;
		}
		$LikeStates = array_fill(0, count($Posts), "F");
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode(array("Posts" => $Posts, "post_index" => $post_index, "LikeStates" => $LikeStates));
	} else {
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode(null);
	}

	// 關閉資料庫連接
	$con->close();
?>
