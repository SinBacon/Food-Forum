<!DOCTPYE html>

<?php 
	session_start();
	include('../db_connect.inc.php');
	
	function truncateString($string, $max_length) {
    // Check if the string length exceeds the maximum length
    if (mb_strlen($string) > $max_length) {
        // Truncate the string
        $truncated_string = mb_substr($string, 0, $max_length) . '...';
    } else {
        // The string is within the limit
        $truncated_string = $string;
    }
    return $truncated_string;
}
	
	
	if (isset($_SESSION['UserID'])){
		//所要選擇的「資料庫UserID」變數
		$database = "flavorfulsphere";
		//選擇資料庫，失敗的話則顯示「資料庫選擇失敗」
		$db_select = mysqli_select_db($con , $database) or die("資料庫選擇失敗");
		$user_id = $_SESSION['UserID'];
		$sql_str = "SELECT * FROM `users` WHERE `UserID` = '$user_id'";
		$res = mysqli_query($con , $sql_str) or die("SQL語法錯誤");
		$row = mysqli_fetch_assoc($res);
		$check = mysqli_num_rows($res);
		if ($check != 1){
			echo '<meta http-equiv=REFRESH CONTENT=0;url=../index.php>';
		}else{
			$UserName = $row['Username'];
		}
	} else {
		header("Location: ../index.php");
	}
	
	if (isset($_GET['post_id'])) {
		$post_id = $_GET['post_id'];
	} else {
		$post_id = 1;
	}

	$db_select = mysqli_select_db($con , $database) or die("資料庫選擇失敗");
	$sql_str = "
		SELECT post.*, COUNT(like.LikeID) AS LikeCount, users.*, food.*, location.*
		FROM post
		LEFT JOIN `like` ON post.PostID = like.PostID
		LEFT JOIN `users` ON post.UserID = users.UserID
		LEFT JOIN `food` ON post.PostID = food.PostID
		LEFT JOIN `location` ON food.FoodID = location.FoodID
		WHERE post.PostID = '$post_id'
		;";
	$res = mysqli_query($con , $sql_str) or die("SQL語法錯誤");
	$row = mysqli_fetch_assoc($res);
	$check = mysqli_num_rows($res);
	
	if ($check == 1){
		$Title = htmlspecialchars($row['Title'], ENT_QUOTES);
		$Content = htmlspecialchars($row['Content'], ENT_QUOTES);
		$Poster = htmlspecialchars($row['Username'], ENT_QUOTES);
		$Timestamp = htmlspecialchars($row['Timestamp'], ENT_QUOTES);
		$LikeCount = htmlspecialchars($row['LikeCount'], ENT_QUOTES);
		$Food = htmlspecialchars($row['Foodname'], ENT_QUOTES);
		$Price = htmlspecialchars($row['Price'], ENT_QUOTES);
		$Description = htmlspecialchars($row['Description'], ENT_QUOTES);
		$Lat = htmlspecialchars($row['Latitude'], ENT_QUOTES);
		$Lng = htmlspecialchars($row['Longitude'], ENT_QUOTES);
		$Locationname = truncateString(htmlspecialchars($row['Locationname'], ENT_QUOTES), 50);
	} else {
		header("Location: ../user/index.php");
	}
	
	$sql_str = "
    SELECT COUNT(`CommentID`) AS `TotalComments`
    FROM `comment`
    WHERE `PostID` = '$post_id'
    ;";
	$res = mysqli_query($con , $sql_str) or die("SQL語法錯誤");
	$row = mysqli_fetch_assoc($res);
	
	$TotalComments = htmlspecialchars($row['TotalComments'], ENT_QUOTES);
	
	$sql = "
		SELECT COUNT(*) AS `LikeCount` 
		FROM `like` 
		WHERE `PostID` = '" . $post_id . "' AND `UserID` = '$user_id';
	";

	$result = $con->query($sql);
	$row = $result->fetch_assoc();
	$LikeState = ($row['LikeCount'] == 0) ? "F" : "T";

?>

<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
		<title>ViewPost</title>
		<link rel = "stylesheet" href="post_style.css">
	</head>
	<body>
		<div id="error_block">
			<img  id="error_pt" src="/image/why-unscreen.gif" alt="">
			<p  id="error_countdown"> 發生錯誤 !</p>
		</div>
		<div id="loading_block">
			<img  id="loading_pt" src="/image/message-unscreen.gif" alt="">
		</div>
		<div id="like_block">
			<img  id="like_pt" src="/image/thumb-up-unscreen.gif" alt="">
		</div>
		<div class="top_block">
			<div class="top_block-child_element left">
				<h1 class= "font-fadeIn"> FlavorfulSphere </h1>
			</div>
			<div class="top_block-child_element right">
				<img class="profile-user" src="/image/profile-user.png" alt="">
				<a  href="../logout.php" id="profile-user_text"><?php echo $UserName;?></a>
			</div>
		</div>
		<div class="content">
			<center>
			<div class ="content_view_block">
				<div class="content-section">
					<h2><?php echo $Title; ?> </h2>
					<div  class="content_poster">
						<img src="../image/profile-user.png" alt="">
						<p><?php echo $Poster; ?></p>
						<p>|</p>
						<p id="post_timestamp"><?php echo $Timestamp; ?></p>
					</div>
					<?php
					if (!empty($Description)) { 
						$mapsUrl = "https://www.google.com/maps?q={$Lat},{$Lng}";
					?>
						<div class="food_info">
							<div class="location_info">
								<img src="/image/place.png" alt="">
								&nbsp;
								<?php
								if (!empty($Lat) && !empty($Lng)) {
								?>
									<a href="<?php echo $mapsUrl; ?>" target="_blank"><p>
									<?php 
									if(empty($Locationname)) {
										echo "(", round($Lat, 3), ", ", round($Lng, 3), ")";
									}else {
										echo $Locationname;
									} ?>
									</p></a>
									<?php
								} else {?>
									<p>null</p>
									<?php 
								}
								?>
								<input type="hidden" class="location_data" name="location_data" value="None">
							</div>
							<div class="food_area">
								<div class="food_name"><p><?php echo $Food; ?></p></div>
								<?php
								if (!empty($Price)){?>
									<p>&nbsp;&nbsp;|&nbsp;&nbsp;</p>
									<div class="food_price"><p><?php echo $Price," 元 "; ?></p></div>
								<?php
								}?>
							</div>
							<?php 
							if ($Description > 0 && $Description <= 5) { ?>
							<div class="star_area">
								<?php 
									for ($i = 1; $i <= $Description; $i++) { ?>
										<img src="/image/star-ck.png" alt="">
									<?php
									}
									?>
									<?php 
									for ($i = 1; $i <= 5-$Description; $i++) { ?>
										<img src="/image/star.png" alt="">
									<?php
									}
									?>
							</div>
							<?php
							}?>
						</div>
					<?php 
					} ?>	
					<div id="post_content_area"><p id="post_content"><?php echo $Content; ?></p></div>
					<div  class="content_info">
						<p id="post_LikeCount"><?php echo $LikeCount, " likes"; ?></p>
						<p>&nbsp|&nbsp</p>
						<p id="post_TotalComments"><?php echo $TotalComments, " comments"; ?></p>
					</div>
					<div class="comment">
					</div>
				</div>
			</div>
			</center>
		</div>
		<div class="bottom_block">
			<input id="input_text" name="input_text" placeholder="輸入留言..." onkeydown="detectEnterKey(event)">
			<input type="hidden" id="post_id" name="post_id" value="<?php echo $post_id ?>">
			<input type="hidden" id="user_id" name="user_id" value="<?php echo $user_id ?>">
			<input type = "button" class="submit_button" value = "submit" align="center">
			<input type = "button" class="like_button" value = "submit" align="center">
		</div>
		<script src="post_script.js"></script>
		<script>
			var PostID = "<?php echo $post_id; ?>";
			convertToLinks('post_content');
		</script>
	</body>
</html>

<?php 
	mysqli_close($con); 
?>