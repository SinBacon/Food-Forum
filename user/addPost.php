<?php
	//引入連線檔案
	include("../db_connect.inc.php");
	//所要選擇的「資料庫名稱」變數
	$Commentsbase = "flavorfulsphere";
	
	$title = isset($_POST['title']) ? $_POST['title'] : '';
	$content = isset($_POST['content']) ? $_POST['content'] : '';
	$food = isset($_POST['food']) ? $_POST['food'] : '';
	$meal_type = isset($_POST['meal_type']) ? $_POST['meal_type'] : '';
	$star_type = isset($_POST['star_type']) ? $_POST['star_type'] : '';
	$food_price = isset($_POST['food_price']) ? $_POST['food_price'] : '';
	$lat = isset($_POST['lat']) ? $_POST['lat'] : '';
	$lng = isset($_POST['lng']) ? $_POST['lng'] : '';
	$location_name = isset($_POST['location_name']) ? $_POST['location_name'] : '';
	$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
	
	if (empty($location_name)) {
		$location_name = "";
	}
	
	if (true) {
		
		//選擇資料庫，失敗的話則顯示「資料庫選擇失敗」
		$db_select = mysqli_select_db($con , $Commentsbase) or die("資料庫選擇失敗");
		// 執行資料庫查詢，這裡假設有一個名為example_table的表格
		
		if (isset($lat) && isset($lng) && is_numeric($lat) && is_numeric($lng)) {
			$sql = "
			START TRANSACTION;

			INSERT INTO `post`(`PostID`, `UserID`, `Title`, `Content`, `Timestamp`) VALUES (NULL, '$user_id', '$title', '$content', CURRENT_TIMESTAMP());
			SET @postID = LAST_INSERT_ID();
			INSERT INTO `food`(`FoodID`, `Ingredients`, `Foodname`, `Description`, `Price`, `PostID`) VALUES (NULL, '$food', '$food', '$star_type', '$food_price', @postID);
			SET @foodID = LAST_INSERT_ID();
			INSERT INTO `location`(`Locationname`, `Longitude`, `Latitude`, `FoodID`) VALUES ('$location_name','$lng','$lat',@foodID);

			COMMIT;
			";
		}else {
			$sql = "
			START TRANSACTION;

			INSERT INTO `post`(`PostID`, `UserID`, `Title`, `Content`, `Timestamp`) VALUES (NULL, '$user_id', '$title', '$content', CURRENT_TIMESTAMP());
			SET @postID = LAST_INSERT_ID();
			INSERT INTO `food`(`FoodID`, `Ingredients`, `Foodname`, `Description`, `Price`, `PostID`) VALUES (NULL, '$food', '$food', '$star_type', '$food_price', @postID);

			COMMIT;
			";
		}

	// Execute the SQL statements
	if (mysqli_multi_query($con, $sql)) {
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode(["info" => "新增成功"]);
	} else {
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode(["info" => "新增失敗".mysqli_error($con), "error" => mysqli_error($con)]);
	}
	$con->close();
	} else{
		echo '<meta http-equiv=REFRESH CONTENT=0;url=../index.php>';
	}
?>
