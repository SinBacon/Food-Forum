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
	}  else {
		header("Location: ../index.php");
	}

	//所要選擇的「資料庫名稱」變數
	$Postsbase = "flavorfulsphere";

	if (isset($_GET['post_index'])) {
		$post_index = $_GET['post_index'];
	} else {
		$post_index = 0;
	}
// 在 getMorePostsData.php 中接收從 JavaScript 傳遞過來的值
	if(isset($_GET['x'])) {
    $x = $_GET['x'];    
	}else {
		$x =0;
	}
	$per = 10;
	//選擇資料庫，失敗的話則顯示「資料庫選擇失敗」
	$db_select = mysqli_select_db($con , $Postsbase) or die("資料庫選擇失敗");
	// 執行資料庫查詢，這裡假設有一個名為example_table的表格
	if($x==0){
	$sql = "
    SELECT 
        post.*, 
        COUNT(like.LikeID) AS LikeCount, 
        users.*,
        MAX(`like`.Timestamp) AS LatestLikeTimestamp,
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
        END AS FormattedTimestamp,
        CASE 
            WHEN like.UserID ='$UserID' THEN 1
            ELSE 2
        END AS LikedFirst
    FROM 
        post
    LEFT JOIN 
        `like` ON post.PostID = like.PostID
    LEFT JOIN 
        `users` ON post.UserID = users.UserID
    GROUP BY 
        post.PostID
    ORDER BY 
        LikedFirst, post.`Timestamp` DESC
    LIMIT 
        $post_index, $per;
";
	}else{
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
    `like` ON post.PostID = `like`.PostID
LEFT JOIN 
    users ON post.UserID = users.UserID
GROUP BY 
    post.PostID
ORDER BY 
    post.Timestamp DESC
LIMIT 
    $post_index, $per;
";
	}
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
		$LikeStates = array();
		foreach ($Posts as $post) {
			$sql = "
				SELECT COUNT(*) AS `LikeCount` 
				FROM `like` 
				WHERE `PostID` = '" . $post['PostID'] . "' AND `UserID` = '$UserID';
			";

			// Use the connection object for the query
			$result = $con->query($sql);

			// Fetch the result
			$row = $result->fetch_assoc();

			// Check the LikeCount value
			$LikeState = ($row['LikeCount'] == 0) ? "F" : "T";
			$LikeStates[] = $LikeState;
		}
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode(array("Posts" => $Posts, "post_index" => $post_index, "LikeStates" => $LikeStates));
	} else {
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode(null);
	}

	// 關閉資料庫連接
	$con->close();
?>
